<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChamDiem extends Model
{
    protected $table = 'ChamDiem';
    protected $primaryKey = 'MaCham';
    public $timestamps = false;

protected $fillable = [
    'MaDeTai',
    'MaTienDo',  // Thêm cột MaTienDo
    'MaSV',
    'MaGV',
    'Diem',
    'NhanXet',
    'NgayCham',
    'TrangThai',
    'VaiTro',
    'DiemCuoi'
];


    public function detai()
    {
        return $this->belongsTo(DeTai::class, 'MaDeTai', 'MaDeTai');
    }

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function giangvien()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }
}