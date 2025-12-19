<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCCD extends Model
{
    use HasFactory;

    protected $table = 'cccd'; // ⚠️ nên viết thường cho chuẩn SQL
    protected $primaryKey = 'MaCCCD';
    public $incrementing = false; // Vì MaCCCD là chuỗi, không phải auto increment
    public $timestamps = false;

    // ⚙️ Thêm HoTen để controller có thể lưu được khi tạo CCCD
    protected $fillable = [
        'MaCCCD',
        'NgayCap',
        'NoiCap'
    ];

    protected $casts = [
        'NgayCap' => 'date',
    ];

    // ================== QUAN HỆ ==================

    public function sinhVien()
    {
        return $this->hasOne(SinhVien::class, 'MaCCCD', 'MaCCCD');
    }

    public function giangVien()
    {
        return $this->hasOne(GiangVien::class, 'MaCCCD', 'MaCCCD');
    }

    public function canBoQL()
    {
        return $this->hasOne(CanBoQL::class, 'MaCCCD', 'MaCCCD');
    }
}
