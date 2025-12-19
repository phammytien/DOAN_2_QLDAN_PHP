<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lop extends Model
{
    use HasFactory;

    protected $table = 'Lop';
    protected $primaryKey = 'MaLop';
    public $timestamps = false;

    protected $fillable = ['TenLop', 'MaKhoa', 'MaNganh', 'MaGV', 'MaCB'];

    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'MaKhoa', 'MaKhoa');
    }

    public function nganh()
    {
        return $this->belongsTo(Nganh::class, 'MaNganh', 'MaNganh');
    }

    public function sinhViens()
    {
        return $this->hasMany(SinhVien::class, 'MaLop', 'MaLop');
    }
}
