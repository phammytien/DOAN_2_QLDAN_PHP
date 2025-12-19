<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TienDo extends Model
{
    protected $table = 'TienDo';
    protected $primaryKey = 'MaTienDo';
    public $timestamps = false;

    protected $fillable = [
        'MaDeTai',
        'NoiDung',
        'ThoiGianCapNhat',
        'TrangThai',
        'GhiChu',

        // Thêm các trường mới
        'Deadline',
        'NgayNop',
        'LinkFile',
        'TenFile',
        'FileCodeID',
        
        // Chấm điểm tiến độ
        'Diem',
        'NhanXet',
        'NgayCham',
        'MaGV'
    ];

    // Quan hệ
    public function fileBaoCao()
    {
        return $this->belongsTo(File::class, 'file_baocao_id');
    }

    public function fileCode()
    {
        return $this->belongsTo(File::class, 'FileCodeID');
    }

    // Quan hệ
    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'MaDeTai', 'MaDeTai');
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }

    /**
     * Tính điểm sau khi trừ 10% nếu nộp trễ
     */
    public function getDiemSauTruTreAttribute()
    {
        if (!$this->Diem) {
            return null;
        }

        // Kiểm tra nộp trễ
        if ($this->Deadline && $this->NgayNop && Carbon::parse($this->NgayNop)->gt(Carbon::parse($this->Deadline))) {
            return $this->Diem * 0.9; // Trừ 10%
        }

        return $this->Diem;
    }

    /**
     * Tự động tính trạng thái dựa trên deadline và ngày nộp
     */
    public function getTrangThaiTuDongAttribute()
    {
        if (!$this->NgayNop) {
            return 'Chưa nộp';
        }

        if ($this->Deadline && Carbon::parse($this->NgayNop)->gt(Carbon::parse($this->Deadline))) {
            return 'Trễ hạn';
        }

        return 'Nộp đúng hạn';
    }

    // Format ngày
    protected $casts = [
        'Deadline' => 'datetime',
        'NgayNop' => 'datetime'
    ];
}
