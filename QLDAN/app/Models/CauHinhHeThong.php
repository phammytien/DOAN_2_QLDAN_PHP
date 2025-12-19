<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHinhHeThong extends Model
{
    protected $table = 'cauhinhhethong';
    protected $primaryKey = 'id';

    protected $fillable = [
        'MaNamHoc',
        'ThoiGianMoDangKy',
        'ThoiGianDongDangKy',
    ];

    public function namhoc()
    {
        return $this->belongsTo(NamHoc::class, 'MaNamHoc', 'MaNamHoc');
    }
}
