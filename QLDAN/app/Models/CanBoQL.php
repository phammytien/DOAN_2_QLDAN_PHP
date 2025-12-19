<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanBoQL extends Model
{
    protected $table = 'CanBoQL';
    protected $primaryKey = 'MaCB';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['MaCB','TenCB','GioiTinh','NgaySinh','MaCCCD','TonGiao','SDT','Email','NoiSinh','HKTT','DanToc','HocVi','HocHam','ChuyenNganh','MaTK','MaKhoa'];

    protected $casts = [
        'NgaySinh' => 'date',
    ];

    public function detais(){ return $this->hasMany(DeTai::class,'MaCB','MaCB'); }
    public function thongbaos(){ return $this->hasMany(ThongBao::class,'MaCB','MaCB'); }
    public function taikhoan(){ return $this->belongsTo(TaiKhoan::class,'MaTK','MaTK'); }
    public function khoa(){ return $this->belongsTo(Khoa::class,'MaKhoa','MaKhoa'); }
}
