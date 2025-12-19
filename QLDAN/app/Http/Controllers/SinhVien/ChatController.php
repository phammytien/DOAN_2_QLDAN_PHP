<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\DeTai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewChatMessageMail;
use Illuminate\Support\Facades\Log;


class ChatController extends Controller
{
    /**
     * Get conversation list for student
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $maSV = $user->MaSo;

            // Get student's project first
            $deTai = DeTai::whereHas('sinhViens', function ($q) use ($maSV) {
                $q->where('SinhVien.MaSV', $maSV); // Specify table name to avoid ambiguity
            })->first();

           logger()->error
("Chat Debug - MaSV: $maSV");
           logger()->error
("Chat Debug - DeTai found: " . ($deTai ? "Yes (ID: {$deTai->MaDeTai})" : "No"));

            if (!$deTai) {
                return response()->json([]);
            }

            // Find conversation for this student's project
            $conversation = Conversation::findForStudent($maSV, $deTai->MaDeTai);

           logger()->error
("Chat Debug - Conversation found: " . ($conversation ? "Yes (ID: {$conversation->id})" : "No"));
          logger()->error
("Chat Debug - Looking for: MaSV=$maSV, MaDeTai={$deTai->MaDeTai}");

            if (!$conversation) {
                return response()->json([]);
            }

            // Load relationships
            $conversation->load(['giangVien', 'deTai', 'lastMessage']);

            $isGroup = $conversation->isGroupChat();
            $lastMsg = $conversation->lastMessage;

            return response()->json([[
                'id' => $conversation->id,
                'is_group' => $isGroup,
                'lecturer_name' => optional($conversation->giangVien)->TenGV ?? 'Giảng viên',
                'lecturer_avatar' => optional($conversation->giangVien)->HinhAnh ?? null,
                'project_name' => optional($conversation->deTai)->TenDeTai ?? null,
                'participant_count' => $isGroup ? $conversation->getParticipants()->count() : 2,
                'last_message' => $lastMsg ? $lastMsg->message : '',
                'last_message_time' => $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : '',
                'unread_count' => $conversation->unreadCountForStudent ?? 0,
            ]]);
        } catch (\Exception $e) {
            
            logger()->error
('Error loading student conversations: ' . $e->getMessage());
            logger()->error
($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get messages for a conversation
     */
    public function show($conversationId)
    {
        $user = Auth::user();
        $maSV = $user->MaSo;

        $conversation = Conversation::findOrFail($conversationId);
        $isGroup = $conversation->isGroupChat();

        $messages = Message::forConversation($conversationId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($maSV, $isGroup) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'file_name' => $msg->file_name,
                    'file_url' => $msg->file_url,
                    'sender_name' => $isGroup ? $msg->sender_name : null,
                    'sender_type' => $msg->sender_type,
                    'is_mine' => $msg->sender_type === 'SinhVien' && $msg->sender_id === $maSV,
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
                    : ($conversation->giangVien->TenGV ?? 'Giảng viên'),
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
            'conversation_id' => 'nullable|exists:chat_conversations,id',
            'message' => 'required_without:file|string|max:5000',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $maSV = $user->MaSo;

        // Get or create conversation
        if ($request->conversation_id) {
            // Verify this conversation belongs to student's project
            $conversation = Conversation::where('id', $request->conversation_id)
                ->where(function($q) use ($maSV) {
                    // Either 1-1 chat with this student
                    $q->where('MaSV', $maSV)
                      // Or group chat where student is in the project
                      ->orWhereHas('deTai.sinhViens', function($q2) use ($maSV) {
                          $q2->where('SinhVien.MaSV', $maSV);
                      });
                })
                ->firstOrFail();
        } else {
            // Get student's project and lecturer
            $deTai = DeTai::whereHas('sinhViens', function ($q) use ($maSV) {
                $q->where('SinhVien.MaSV', $maSV); // Specify table name to avoid ambiguity
            })->first();

            if (!$deTai || !$deTai->MaGV) {
                return response()->json(['error' => 'Bạn chưa có đề tài hoặc giảng viên hướng dẫn'], 400);
            }

            $conversation = Conversation::findOrCreate($maSV, $deTai->MaGV, $deTai->MaDeTai);
        }

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
            'sender_type' => 'SinhVien',
            'sender_id' => $maSV,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        // Send email notification to lecturer
        try {
            $lecturer = $conversation->giangVien;
            if ($lecturer && $lecturer->Email) {
                Mail::to($lecturer->Email)->send(new NewChatMessageMail($message, $conversation));
            }
        } catch (\Exception $e) {
           logger()->error('Failed to send chat email: ' . $e->getMessage());
        }

        // Broadcast event (will implement with Pusher later)
        // event(new NewChatMessage($message, $conversation));

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
    public function markAsRead($conversationId)
    {
        $user = Auth::user();
        $maSV = $user->MaSo;

        // Verify this conversation belongs to student's project
        $conversation = Conversation::where('id', $conversationId)
            ->where(function($q) use ($maSV) {
                $q->where('MaSV', $maSV)
                  ->orWhereHas('deTai.sinhViens', function($q2) use ($maSV) {
                      $q2->where('SinhVien.MaSV', $maSV);
                  });
            })
            ->firstOrFail();

        // Mark all messages from lecturer as read
        Message::forConversation($conversationId)
            ->where('sender_type', 'GiangVien')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        $maSV = $user->MaSo;

        $count = Message::whereHas('conversation', function ($q) use ($maSV) {
            // Count messages from conversations where student is participant
            $q->where(function($q2) use ($maSV) {
                // Either 1-1 chat
                $q2->where('MaSV', $maSV)
                   // Or group chat where student is in the project
                   ->orWhereHas('deTai.sinhViens', function($q3) use ($maSV) {
                       $q3->where('SinhVien.MaSV', $maSV);
                   });
            });
        })
        ->where('sender_type', 'GiangVien')
        ->where('is_read', false)
        ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Delete a message
     */
    public function deleteMessage($id)
    {
        $user = Auth::user();
        $maSV = $user->MaSo;

        $message = Message::findOrFail($id);

        // Check permission: only sender can delete
        if ($message->sender_type !== 'SinhVien' || $message->sender_id !== $maSV) {
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
    public function deleteConversation($id)
    {
        $user = Auth::user();
        $maSV = $user->MaSo;

        // Verify this conversation belongs to student's project
        $conversation = Conversation::where('id', $id)
            ->where(function($q) use ($maSV) {
                $q->where('MaSV', $maSV)
                  ->orWhereHas('deTai.sinhViens', function($q2) use ($maSV) {
                      $q2->where('SinhVien.MaSV', $maSV);
                  });
            })
            ->firstOrFail();

        // Delete all messages first
        Message::where('conversation_id', $id)->delete();

        // Delete conversation
        $conversation->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa cuộc trò chuyện']);
    }
}
