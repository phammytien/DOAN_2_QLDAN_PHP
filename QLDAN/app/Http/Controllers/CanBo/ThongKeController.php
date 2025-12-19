<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThongKeController extends Controller
{
    public function index()
    {

        $maCB = Auth::user()->MaSo;

        
        // Lấy tất cả báo cáo của các đề tài do cán bộ này quản lý
        $baocaos = \App\Models\BaoCao::whereHas('deTai', function($q) use ($maCB) {
            $q->where('MaCB', $maCB);
        })
        ->with(['deTai', 'sinhVien'])
        ->orderBy('NgayNop', 'desc')
        ->get();
        
        // Thống kê
        $tongDeTai = \App\Models\DeTai::where('MaCB', $maCB)->count();
        $deTaiHoanThanh = \App\Models\DeTai::where('MaCB', $maCB)->where('TrangThai', 'Hoàn thành')->count();
        $deTaiDangThucHien = \App\Models\DeTai::where('MaCB', $maCB)->where('TrangThai', 'Đang thực hiện')->count();
        
        return view('canbo.thongke', compact('baocaos', 'tongDeTai', 'deTaiHoanThanh', 'deTaiDangThucHien'));
    }
}