<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaiKhoan extends Authenticatable
{
    use HasFactory;

    protected $table = 'TaiKhoan';
    protected $primaryKey = 'MaTK';
    public $timestamps = false;

    protected $fillable = [
        'MaSo', 
        'MatKhau', 
        'VaiTro',
        'TrangThai'   // thêm để khóa / mở khóa tài khoản
    ];

    // Dùng trường MatKhau làm password cho Laravel
    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    // Quan hệ
    public function sinhVien()
    {
        return $this->hasOne(SinhVien::class, 'MaSV', 'MaSo');
    }

    // public function giangVien()
    // {
    //     return $this->hasOne(GiangVien::class, 'MaTK', 'MaTK');
    // }
public function giangVien()
{
    return $this->hasOne(GiangVien::class, 'MaGV', 'MaSo');
}

    public function canBoQL()
    {
        return $this->hasOne(CanBoQL::class, 'MaTK', 'MaTK');
    }
}
