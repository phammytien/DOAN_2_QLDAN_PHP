<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nganh extends Model
{
    use HasFactory;

    protected $table = 'Nganh';
    protected $primaryKey = 'MaNganh';
    public $timestamps = false;

    protected $fillable = ['TenNganh'];

    public function lops()
    {
        return $this->hasMany(Lop::class, 'MaNganh', 'MaNganh');
    }

    public function sinhViens()
    {
        return $this->hasMany(SinhVien::class, 'MaNganh', 'MaNganh');
    }
}
