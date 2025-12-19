<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewChatMessageMail;
use Illuminate\Support\Facades\Log;


class ChatController extends Controller
{
    /**
     * Get conversation list for lecturer
     */
    public function index()
    {
        $user = Auth::user();
        $maGV = $user->MaSo;

        $conversations = Conversation::forLecturer($maGV)
            ->with(['sinhVien', 'deTai', 'deTai.sinhViens', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $conversations = $conversations->map(function ($conv) {
            $isGroup = $conv->isGroupChat();
            
            return [
                'id' => $conv->id,
                'is_group' => $isGroup,
                'student_name' => $isGroup 
                    ? ($conv->deTai->TenDeTai ?? 'Nhóm') 
                    : ($conv->sinhVien->TenSV ?? 'Sinh viên'),
                'student_avatar' => $isGroup 
                    ? null 
                    : ($conv->sinhVien->HinhAnh ?? null),
                'project_name' => $conv->deTai->TenDeTai ?? null,
                'participant_count' => $isGroup 
                    ? $conv->getParticipants()->count() 
                    : 2,
                'last_message' => $conv->lastMessage->message ?? '',
                'last_message_time' => $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '',
                'unread_count' => $conv->unreadCountForLecturer,
            ];
        });

        return response()->json($conversations);
    }

    /**
     * Get messages for a conversation
     */
    public function show($conversationId)
    {
        $user = Auth::user();
        $maGV = $user->MaSo;

        $conversation = Conversation::forLecturer($maGV)->findOrFail($conversationId);
        $isGroup = $conversation->isGroupChat();

        $messages = Message::forConversation($conversationId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($maGV, $isGroup) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'file_name' => $msg->file_name,
                    'file_url' => $msg->file_url,
                    'sender_name' => $isGroup ? $msg->sender_name : null, // Show name in group chat
                    'sender_type' => $msg->sender_type,
                    'is_mine' => $msg->sender_type === 'GiangVien' && $msg->sender_id === $maGV,
                    'created_at' => $msg->created_at->format('H:i'),
                    'is_read' => $msg->is_read,
                ];
            });

        // Get participants for group chat
        $participants = [];
        if ($isGroup) {
            $participants = $conversation->getParticipants()->map(function ($p) {
                if ($p instanceof \App\Models\SinhVien) {
                    return [
                        'type' => 'student',
                        'id' => $p->MaSV,
                        'name' => $p->TenSV,
                        'avatar' => $p->HinhAnh ?? null,
                    ];
                } else if ($p instanceof \App\Models\GiangVien) {
                    return [
                        'type' => 'lecturer',
                        'id' => $p->MaGV,
                        'name' => $p->TenGV,
                        'avatar' => $p->HinhAnh ?? null,
                    ];
                }
            })->filter()->values();
        }

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'is_group' => $isGroup,
                'name' => $isGroup 
                    ? ($conversation->deTai->TenDeTai ?? 'Nhóm')
                    : ($conversation->sinhVien->TenSV ?? 'Sinh viên'),
                'participants' => $participants,
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * Send a new message
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required_without:file|string|max:5000',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $maGV = $user->MaSo;

        $conversation = Conversation::forLecturer($maGV)->findOrFail($request->conversation_id);

        // Handle file upload
        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('chat_files', $fileName, 'public');
            $filePath = 'storage/' . $filePath;
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'GiangVien',
            'sender_id' => $maGV,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        // Send email notification to student
        try {
            $student = $conversation->sinhVien;
            if ($student && $student->Email) {
                Mail::to($student->Email)->send(new NewChatMessageMail($message, $conversation));
            }
        } catch (\Exception $e) {
    Log::error('Failed to send chat email: ' . $e->getMessage());
}


        return response()->json([ 
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'file_name' => $message->file_name,
                'file_url' => $message->file_url,
                'sender_name' => $message->sender_name,
                'is_mine' => true,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($conversationId) // Gọi khi mở cuộc trò chuyện
    {
        $user = Auth::user();
        $maGV = $user->MaSo;

        $conversation = Conversation::forLecturer($maGV)->findOrFail($conversationId);

        // Mark all messages from student as read
        Message::forConversation($conversationId)
            ->where('sender_type', 'SinhVien')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function unreadCount() // Gọi định kỳ để cập nhật số tin nhắn chưa đọc
    {
        $user = Auth::user();
        $maGV = $user->MaSo;

        $count = Message::whereHas('conversation', function ($q) use ($maGV) {
            $q->where('MaGV', $maGV);
        })
        ->where('sender_type', 'SinhVien')
        ->where('is_read', false)
        ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Delete a message
     */
    public function deleteMessage($id) // Xóa tin nhắn
    {
        $user = Auth::user();
        $maGV = $user->giangVien->MaGV;

        $message = Message::findOrFail($id);

        // Check permission: only sender can delete
        if ($message->sender_type !== 'GiangVien' || $message->sender_id !== $maGV) {
            return response()->json(['error' => 'Bạn không có quyền xóa tin nhắn này'], 403);
        }

        // Delete file if exists
        if ($message->file_path && file_exists(public_path($message->file_path))) {
            unlink(public_path($message->file_path));
        }

        $message->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa tin nhắn']);
    }

    /**
     * Delete a conversation
     */
    public function deleteConversation($id) // Xóa cuộc trò chuyện và tất cả tin nhắn liên quan
    {
        $user = Auth::user();
        $maGV = $user->giangVien->MaGV;

        $conversation = Conversation::forLecturer($maGV)->findOrFail($id);

        // Delete all messages first
        Message::where('conversation_id', $id)->delete();

        // Delete conversation
        $conversation->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa cuộc trò chuyện']);
    }
}