<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    use HasFactory;

    protected $table = 'Khoa';
    protected $primaryKey = 'MaKhoa';
    public $timestamps = false;

    protected $fillable = ['TenKhoa'];

    public function lops()
    {
        return $this->hasMany(Lop::class, 'MaKhoa', 'MaKhoa');
    }

    public function sinhViens()
    {
        return $this->hasMany(SinhVien::class, 'MaKhoa', 'MaKhoa');
    }
}
