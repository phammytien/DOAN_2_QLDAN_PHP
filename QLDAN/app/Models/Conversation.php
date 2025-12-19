<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'chat_conversations';
    
    protected $fillable = [
        'MaSV',
        'MaGV',
        'MaDeTai',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Relationship: Sinh viên
     */
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    /**
     * Relationship: Giảng viên
     */
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }

    /**
     * Relationship: Đề tài
     */
    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'MaDeTai', 'MaDeTai');
    }

    /**
     * Relationship: Messages
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    /**
     * Get last message
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class, 'conversation_id')->latest();
    }

    /**
     * Scope: For student
     */
    public function scopeForStudent($query, $maSV)
    {
        return $query->where('MaSV', $maSV);
    }

    /**
     * Scope: For lecturer
     */
    public function scopeForLecturer($query, $maGV)
    {
        return $query->where('MaGV', $maGV);
    }

    /**
     * Get unread count for student
     */
    public function getUnreadCountForStudentAttribute()
    {
        return $this->messages()
            ->where('sender_type', 'GiangVien')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get unread count for lecturer
     */
    public function getUnreadCountForLecturerAttribute()
    {
        return $this->messages()
            ->where('sender_type', 'SinhVien')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Check if this is a group conversation
     */
    public function isGroupChat()
    {
        return is_null($this->MaSV) && !is_null($this->MaDeTai);
    }

    /**
     * Get all participants (for group chat)
     */
    public function getParticipants()
    {
        if (!$this->isGroupChat()) {
            return collect([$this->sinhVien, $this->giangVien])->filter();
        }

        // Group chat: Get all students in the project + lecturer
        $participants = collect();
        
        if ($this->deTai && $this->deTai->sinhViens) {
            $participants = collect($this->deTai->sinhViens);
        }
        
        if ($this->giangVien) {
            $participants->push($this->giangVien);
        }
        
        return $participants;
    }

    /**
     * Find or create conversation - SMART VERSION
     * Supports both 1-1 chat and group chat
     */
    public static function findOrCreate($maSV, $maGV, $maDeTai = null)
    {
        \Log::info("findOrCreate called - MaSV: $maSV, MaGV: $maGV, MaDeTai: $maDeTai");

        // If no project specified, create 1-1 chat
        if (!$maDeTai) {
            \Log::info("No project - creating 1-1 chat");
            return static::firstOrCreate(
                ['MaSV' => $maSV, 'MaGV' => $maGV],
                ['MaDeTai' => null]
            );
        }

        // Get project info to check if it's group or individual
        $deTai = \App\Models\DeTai::find($maDeTai);
        
        if (!$deTai) {
            \Log::warning("Project not found - MaDeTai: $maDeTai");
            // Fallback to 1-1 if project not found
            return static::firstOrCreate(
                ['MaSV' => $maSV, 'MaGV' => $maGV],
                ['MaDeTai' => $maDeTai]
            );
        }

        $soLuongThanhVien = $deTai->SoLuongThanhVien ?? 1;
        \Log::info("Project found - SoLuongThanhVien: $soLuongThanhVien");

        // Individual project (1 student) - Create 1-1 conversation
        if ($soLuongThanhVien == 1) {
            \Log::info("Creating 1-1 conversation for individual project");
            return static::firstOrCreate(
                ['MaSV' => $maSV, 'MaGV' => $maGV, 'MaDeTai' => $maDeTai]
            );
        }

        // Group project (2+ students) - Create/find group conversation
        // MaSV = NULL for group conversations
        \Log::info("Creating/finding group conversation for team project");
        $conv = static::firstOrCreate(
            ['MaSV' => null, 'MaGV' => $maGV, 'MaDeTai' => $maDeTai]
        );
        \Log::info("Group conversation created/found - ID: {$conv->id}");
        return $conv;
    }

    /**
     * Find conversation for a student in a project
     */
    public static function findForStudent($maSV, $maDeTai)
    {
        // Get project to check if it's group or individual
        $deTai = \App\Models\DeTai::find($maDeTai);
        
        if (!$deTai) {
            \Log::warning("findForStudent - Project not found: $maDeTai");
            return null;
        }

        $soLuongThanhVien = $deTai->SoLuongThanhVien ?? 1;
        \Log::info("findForStudent - MaSV: $maSV, MaDeTai: $maDeTai, SoLuongThanhVien: $soLuongThanhVien");

        // Group project (2+ students) - Find group conversation (MaSV = NULL)
        if ($soLuongThanhVien > 1) {
            \Log::info("findForStudent - Looking for group conversation");
            $conv = static::where('MaDeTai', $maDeTai)
                ->whereNull('MaSV')
                ->first();
            \Log::info("findForStudent - Group conversation " . ($conv ? "found (ID: {$conv->id})" : "NOT found"));
            return $conv;
        }

        // Individual project (1 student) - Find 1-1 conversation
        \Log::info("findForStudent - Looking for 1-1 conversation");
        $conv = static::where('MaSV', $maSV)
            ->where('MaDeTai', $maDeTai)
            ->first();
        \Log::info("findForStudent - 1-1 conversation " . ($conv ? "found (ID: {$conv->id})" : "NOT found"));
        return $conv;
    }
}
