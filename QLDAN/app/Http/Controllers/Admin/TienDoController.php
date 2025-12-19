<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeTai;
use App\Models\SinhVien;
use App\Models\TienDo;
use Illuminate\Http\Request;

class TienDoController extends Controller
{
    // Trang danh sách đề tài có sinh viên đăng ký
    public function index(Request $request)
    {
        // Lấy danh sách đề tài có sinh viên đăng ký
        $query = DeTai::with(['giangVien', 'sinhViens.lop', 'tiendos'])
            ->whereHas('sinhViens'); // Chỉ lấy đề tài đã có sinh viên đăng ký
        
        // Lọc theo tên đề tài
        if ($request->filled('detai')) {
            $query->where('TenDeTai', 'like', '%' . $request->detai . '%');
        }
        
        // Lọc theo sinh viên
        if ($request->filled('sinhvien')) {
            $query->whereHas('sinhViens', function($q) use ($request) {
                $q->where('TenSV', 'like', '%' . $request->sinhvien . '%')
                  ->orWhere('HoTen', 'like', '%' . $request->sinhvien . '%');
            });
        }
        
        // Lọc theo trạng thái (có tiến độ hay chưa)
        if ($request->filled('trangthai')) {
            $status = $request->trangthai;
            if ($status == 'Có tiến độ') {
                $query->has('tiendos');
            } elseif ($status == 'Chưa có tiến độ') {
                $query->doesntHave('tiendos');
            }
        }
        
        $detais = $query->orderByDesc('MaDeTai')->paginate(15);
        
        return view('admin.tiendo.index', compact('detais'));
    }

    // Trang xem chi tiết
public function show($MaTienDo)
{
    $t = TienDo::with('deTai')->findOrFail($MaTienDo);
    return view('admin.tiendo.show', compact('t'));
}

}