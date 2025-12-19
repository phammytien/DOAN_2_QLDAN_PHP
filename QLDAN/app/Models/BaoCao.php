<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCao extends Model
{
    protected $table = 'BaoCao';
    protected $primaryKey = 'MaBC';
    public $timestamps = false;
    protected $fillable = [
        'MaDeTai', 'MaSV', 'FileID', 'FileCodeID',
        'NgayNop', 'LanNop', 'NhanXet', 'TrangThai', 'Deadline',
        
        // Phân tích điểm
        'DiemTienDo',      // 40%
        'DiemBaoCaoCuoi',  // 60%
        'DiemTong'         // Tổng điểm
    ];

    // Quan hệ
    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'MaDeTai', 'MaDeTai');
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function fileBaoCao()
    {
        return $this->belongsTo(File::class, 'FileID');
    }

    public function fileCode()
    {
        return $this->belongsTo(File::class, 'FileCodeID');
    }

    // Relationship với bảng ChamDiem
    public function chamDiems()
    {
        return $this->hasMany(ChamDiem::class, 'MaDeTai', 'MaDeTai')
                    ->where('MaSV', $this->MaSV);
    }

    // COMMENTED OUT - Bảng BaoCao không có cột MaTienDo
    // Lấy điểm tiến độ trung bình
    // public function getDiemTienDoAttribute()
    // {
    //     return ChamDiem::where('MaDeTai', $this->MaDeTai)
    //         ->where('MaSV', $this->MaSV)
    //         ->whereNotNull('MaTienDo')
    //         ->avg('Diem');
    // }

    // Lấy điểm báo cáo cuối
    // public function getDiemBaoCaoCuoiAttribute()
    // {
    //     $chamDiem = ChamDiem::where('MaDeTai', $this->MaDeTai)
    //         ->where('MaSV', $this->MaSV)
    //         ->whereNull('MaTienDo')
    //         ->first();
    //     
    //     return $chamDiem ? $chamDiem->Diem : null;
    // }

    // Tính tổng điểm
    // public function getDiemTongAttribute()
    // {
    //     $diemTienDo = $this->DiemTienDo;
    //     $diemBaoCao = $this->DiemBaoCaoCuoi;
    //     
    //     if ($diemTienDo !== null && $diemBaoCao !== null) {
    //         return ($diemTienDo * 0.4) + ($diemBaoCao * 0.6);
    //     }
    //     
    //     return null;
    // }
}