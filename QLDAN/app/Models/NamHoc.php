<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamHoc extends Model
{
    protected $table = 'namhoc';
    protected $primaryKey = 'MaNamHoc';
    public $timestamps = false;

    protected $fillable = [
        'TenNamHoc',
        'TrangThai',
    ];

    public function deTais()
    {
        return $this->hasMany(DeTai::class, 'MaNamHoc', 'MaNamHoc');
    }
    public function cauhinh()
{
    return $this->hasMany(CauHinhHeThong::class, 'MaNamHoc', 'MaNamHoc');
}

}