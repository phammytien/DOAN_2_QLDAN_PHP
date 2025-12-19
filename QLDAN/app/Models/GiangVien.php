<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    protected $table = 'GiangVien';
    protected $primaryKey = 'MaGV';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

protected $fillable = [
    'MaGV', 'TenGV', 'GioiTinh', 'NgaySinh', 'TonGiao', 'SDT', 'Email',
    'NoiSinh', 'HKTT', 'DanToc', 'HocVi', 'HocHam', 'ChuyenNganh',
    'MaCCCD', 'MaTK',
    'MaKhoa', 'MaNganh', 'MaNamHoc', 'HinhAnh'
];

protected $casts = [
    'NgaySinh' => 'date',
];


    // 🧩 Quan hệ với bảng DeTai
    public function detais()
    {
        return $this->hasMany(DeTai::class, 'MaGV', 'MaGV');
    }

    // 🧩 Quan hệ với bảng ChamDiem
    public function chamdiems()
    {
        return $this->hasMany(ChamDiem::class, 'MaGV', 'MaGV');
    }

    // 🧩 Quan hệ với bảng TaiKhoan
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTK', 'MaTK');
    }
// 🧩 Quan hệ với bảng Khoa
public function khoa()
{
    return $this->belongsTo(Khoa::class, 'MaKhoa', 'MaKhoa');
}

public function nganh()
{
    return $this->belongsTo(Nganh::class, 'MaNganh', 'MaNganh');
}

public function namhoc()
{
    return $this->belongsTo(NamHoc::class, 'MaNamHoc', 'MaNamHoc');
}


    // 🧩 Quan hệ với bảng CCCD
    public function cccd()
    {
        return $this->belongsTo(CCCD::class, 'MaCCCD', 'MaCCCD');
    }
}