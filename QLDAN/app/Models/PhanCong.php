<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanCong extends Model
{
    use HasFactory;

    protected $table = 'PhanCong';
    protected $primaryKey = 'MaPC';
    public $timestamps = false;

    protected $fillable = [
        'MaDeTai',
        'MaGV',
        'MaCB',
        'VaiTro',
        'NgayPhanCong',
        'GhiChu'
    ];

    // Quan hệ tới đề tài
    public function detai()
    {
        return $this->belongsTo(DeTai::class, 'MaDeTai', 'MaDeTai');
    }

    // Quan hệ tới giảng viên
    public function giangvien()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }

    // Quan hệ tới cán bộ
    public function canbo()
    {
        return $this->belongsTo(CanBoQL::class, 'MaCB', 'MaCB');
    }
}
