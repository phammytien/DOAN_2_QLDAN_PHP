<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeTai extends Model
{
    protected $table = 'DeTai';
    protected $primaryKey = 'MaDeTai';
    public $timestamps = false;

protected $fillable = [
    'TenDeTai', 
    'MoTa', 
    'LinhVuc', 
    'MaKhoa',
    'MaNganh',
    'LoaiDeTai',
    'TrangThai', 
    'MaGV', 
    'MaCB', 
    'MaNamHoc',
    'NamHoc',  // 🔥 thêm dòng này
    'DeadlineBaoCao',  // 🔥 thêm dòng này
    'SoLuongThanhVien'  // 🔥 Số lượng thành viên (1 = cá nhân, >1 = nhóm)
];


    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }

    public function canBo()
    {
        return $this->belongsTo(CanBoQL::class, 'MaCB', 'MaCB');
    }

    public function namHoc()
    {
        return $this->belongsTo(NamHoc::class, 'MaNamHoc', 'MaNamHoc');
    }

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
        return $this->belongsToMany(SinhVien::class, 'DeTai_SinhVien', 'MaDeTai', 'MaSV')
                    ->withPivot('VaiTro', 'TrangThai');
    }

    public function chamdiems()
    {
        return $this->hasMany(ChamDiem::class, 'MaDeTai', 'MaDeTai');
    }

        public function phancongs()
    {
        return $this->hasMany(PhanCong::class, 'MaDeTai', 'MaDeTai');
    }

    public function baocaos()
    {
        return $this->hasMany(BaoCao::class, 'MaDeTai', 'MaDeTai');
    }

    public function tiendos()
    {
        return $this->hasMany(TienDo::class, 'MaDeTai', 'MaDeTai');
    }

    // Cast dates
    protected $casts = [
        'DeadlineBaoCao' => 'datetime',
    ];

    // Accessor: Tự động tính trạng thái dựa trên deadline
    public function getTrangThaiHienThiAttribute()
    {
        // Nếu đã hoàn thành, giữ nguyên
        if (in_array($this->TrangThai, ['Hoàn thành', 'Đã hoàn thành'])) {
            return $this->TrangThai;
        }

        // Nếu đang thực hiện và đã quá deadline
        if ($this->TrangThai === 'Đang thực hiện' && $this->DeadlineBaoCao) {
            if (\Carbon\Carbon::parse($this->DeadlineBaoCao)->isPast()) {
                return 'Trễ hạn';
            }
        }

        // Trả về trạng thái gốc
        return $this->TrangThai;
    }

}