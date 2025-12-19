<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhanBienController extends Controller
{
    public function index()
    {
        
        $maCB = Auth::user()->MaSo;
        
        // Lấy tất cả phân công phản biện do cán bộ này tạo
        $phancongs = \App\Models\PhanCong::where('MaCB', $maCB)
        ->with(['detai', 'giangvien'])
        ->orderBy('NgayPhanCong', 'desc')
        ->get();

        // Lấy danh sách tất cả đề tài để hiện trong modal
        $detais = \App\Models\DeTai::orderBy('MaDeTai', 'desc')->get();

        // Lấy danh sách giảng viên để hiện trong modal
        $giangviens = \App\Models\GiangVien::all();
        
        return view('canbo.phanbien', compact('phancongs', 'detais', 'giangviens'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'MaDeTai' => 'required|exists:DeTai,MaDeTai',
                'MaGV' => 'required|exists:GiangVien,MaGV',
                'VaiTro' => 'required',
                'NgayPhanCong' => 'required|date',
            ], [
                'MaDeTai.required' => 'Vui lòng chọn đề tài',
                'MaDeTai.exists' => 'Đề tài không tồn tại',
                'MaGV.required' => 'Vui lòng chọn giảng viên',
                'MaGV.exists' => 'Giảng viên không tồn tại',
                'VaiTro.required' => 'Vui lòng chọn vai trò',
                'NgayPhanCong.required' => 'Vui lòng chọn ngày phân công',
            ]);

            // Lấy mã cán bộ từ quan hệ user -> canBoQL
            //$maCB = auth()->user()->canBoQL->MaCB ?? auth()->user()->MaSo;
            $maCB = Auth::user()->canBoQL ->addBinding()->MaCB ?? Auth::user()->MaSo;

            // Kiểm tra trùng lặp
            $exists = \App\Models\PhanCong::where('MaDeTai', $request->MaDeTai)
                ->where('MaGV', $request->MaGV)
                ->where('VaiTro', $request->VaiTro)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Giảng viên này đã được phân công với vai trò này cho đề tài này rồi!');
            }

            $pc = new \App\Models\PhanCong();
            $pc->MaDeTai = $request->MaDeTai;
            $pc->MaGV = $request->MaGV;
            $pc->MaCB = $maCB;
            $pc->VaiTro = $request->VaiTro;
            $pc->NgayPhanCong = $request->NgayPhanCong;
            $pc->GhiChu = $request->GhiChu;
            $pc->save();

            return back()->with('success', 'Phân công thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi lưu phân công: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $pc = \App\Models\PhanCong::findOrFail($id);
        
        $request->validate([
            'MaDeTai' => 'required',
            'MaGV' => 'required',
            'VaiTro' => 'required',
            'NgayPhanCong' => 'required|date',
        ]);

        $pc->MaDeTai = $request->MaDeTai;
        $pc->MaGV = $request->MaGV;
        $pc->VaiTro = $request->VaiTro;
        $pc->NgayPhanCong = $request->NgayPhanCong;
        $pc->GhiChu = $request->GhiChu;
        $pc->save();

        return back()->with('success', 'Cập nhật phân công thành công!');
    }

    public function destroy($id)
    {
        $pc = \App\Models\PhanCong::findOrFail($id);
        $pc->delete();
        return back()->with('success', 'Đã xóa phân công!');
    }
}
