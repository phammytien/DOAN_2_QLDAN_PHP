<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Auth;

class ThongBaoController extends Controller
{
public function index()
{
    $maGV = Auth::user()->MaSo;
    
    $thongbao = ThongBao::where(function($query) use ($maGV) {
        $query->whereIn('DoiTuongNhan', ['GV', 'TatCa'])
              ->whereNull('MaNguoiNhan'); // Thông báo chung
    })
    ->orWhere(function($query) use ($maGV) {
        $query->where('MaNguoiNhan', $maGV); // Thông báo riêng cho giảng viên này
    })
    ->orderByDesc('TGDang')
    ->get();

    return view('giangvien.thongbao.index', compact('thongbao'));
}

}