<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = [
        'conversation_id',
        'sender_type',
        'sender_id',
        'message',
        'file_path',
        'file_name',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Relationship: Conversation
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Get sender (polymorphic-like)
     */
    public function getSenderAttribute()
    {
        if ($this->sender_type === 'SinhVien') {
            return SinhVien::find($this->sender_id);
        } elseif ($this->sender_type === 'GiangVien') {
            return GiangVien::find($this->sender_id);
        }
        return null;
    }

    /**
     * Get sender name
     */
    public function getSenderNameAttribute()
    {
        $sender = $this->sender;
        if (!$sender) return 'Unknown';
        
        if ($this->sender_type === 'SinhVien') {
            return $sender->TenSV ?? 'Sinh viên';
        } elseif ($this->sender_type === 'GiangVien') {
            return $sender->TenGV ?? 'Giảng viên';
        }
        return 'Unknown';
    }

    /**
     * Scope: Unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: For conversation
     */
    public function scopeForConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true]);
        }
    }

    /**
     * Check if message has file attachment
     */
    public function hasFile()
    {
        return !empty($this->file_path);
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->hasFile()) {
            return asset($this->file_path);
        }
        return null;
    }
}
