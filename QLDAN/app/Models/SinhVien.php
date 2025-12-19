<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $table = 'SinhVien';
    protected $primaryKey = 'MaSV';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'MaSV', 'TenSV', 'GioiTinh', 'NgaySinh', 'MaCCCD', 'TonGiao', 'SDT', 'Email',
        'NoiSinh', 'HKTT', 'DanToc', 'BacDaoTao', 'MaKhoa', 'MaNganh', 'MaLop',
        'MaNamHoc', 'MaTK', 'TrangThai', 'HinhAnh'
    ];

    protected $casts = [
        'NgaySinh' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sinhvien) {
            // Nếu đã có MaSV (do import hoặc nhập tay) thì KHÔNG sinh tự động
            if (!empty($sinhvien->MaSV)) {
                return;
            }

            if (!$sinhvien->MaNamHoc) {
                throw new \Exception('Vui lòng chọn Năm học trước khi tạo Sinh viên.');
            }

            $namhoc = \App\Models\NamHoc::find($sinhvien->MaNamHoc);
            if (!$namhoc) {
                throw new \Exception('Không tìm thấy năm học có mã: ' . $sinhvien->MaNamHoc);
            }

            // Xử lý tên năm học: "2024–2025" hoặc "2024-2025"
            $years = preg_split('/[-–]/', $namhoc->TenNamHoc);
            $startYear = isset($years[0]) ? trim($years[0]) : date('Y');
            $endYear = isset($years[1]) ? trim($years[1]) : $startYear + 1;

            // Ví dụ: 002245 (prefix)
            $prefix = '002' . substr($startYear, -2) . substr($endYear, -2);

            // Lấy sinh viên cuối cùng cùng prefix
            $last = self::where('MaSV', 'like', $prefix . '%')
                ->orderBy('MaSV', 'desc')
                ->first();

            $nextNumber = $last ? intval(substr($last->MaSV, -3)) + 1 : 1;
            $sinhvien->MaSV = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    // ===== Quan hệ Eloquent =====
    public function khoa()     { return $this->belongsTo(Khoa::class, 'MaKhoa', 'MaKhoa'); }
    public function nganh()    { return $this->belongsTo(Nganh::class, 'MaNganh', 'MaNganh'); }
    public function lop()
    {
        return $this->belongsTo(Lop::class, 'MaLop', 'MaLop');
    }
    public function namhoc()   { return $this->belongsTo(NamHoc::class, 'MaNamHoc', 'MaNamHoc'); }

    public function taiKhoan()
    {
        return $this->hasOne(TaiKhoan::class, 'MaSo', 'MaSV');
    }

    public function deTai()
    {
        // Lấy đề tài đầu tiên của sinh viên từ bảng DeTai_SinhVien
        return $this->belongsToMany(DeTai::class, 'DeTai_SinhVien', 'MaSV', 'MaDeTai')
            ->withPivot('VaiTro', 'TrangThai')
            ->limit(1);
    }

    public function baoCao()
    {
        return $this->hasMany(BaoCao::class, 'MaSV', 'MaSV');
    }

    public function detais()
    {
        return $this->belongsToMany(DeTai::class, 'DeTai_SinhVien', 'MaSV', 'MaDeTai')
            ->withPivot('VaiTro', 'TrangThai');
    }
    public function diems()
    {
        return $this->hasMany(ChamDiem::class, 'MaSV', 'MaSV');
    }
}
