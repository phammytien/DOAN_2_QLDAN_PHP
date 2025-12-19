<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    protected $table = 'ThongBao';
    protected $primaryKey = 'MaTB';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false; // 🚀 FIX LỖI updated_at, created_at

protected $fillable = [
    'NoiDung', 'TGDang', 'MaCB',
    'TenFile', 'DoiTuongNhan', 'MucDo', 'MaNguoiNhan'
];


    public function canBo()
    {
        return $this->belongsTo(CanBoQL::class, 'MaCB', 'MaCB');
    }
}