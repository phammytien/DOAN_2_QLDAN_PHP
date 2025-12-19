<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeTai_SinhVien extends Model
{
    protected $table = 'DeTai_SinhVien';
    protected $fillable = ['MaDeTai', 'MaSV', 'TrangThai'];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function detai()
    {
        return $this->belongsTo(DeTai::class, 'MaDeTai', 'MaDeTai');
    }
}
