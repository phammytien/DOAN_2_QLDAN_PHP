<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CauHinhHeThong;
use App\Models\NamHoc;

class CauHinhController extends Controller
{
    public function index()
    {
        $namhoc = NamHoc::all();
        $configTheoNam = CauHinhHeThong::with('namhoc')->get();

        return view('admin.cauhinh.index', compact('namhoc', 'configTheoNam'));
    }

    public function edit($id)
    {
        $config = CauHinhHeThong::findOrFail($id);
        $namhoc = NamHoc::all();

        if (request()->ajax()) {
            return view('admin.cauhinh.edit_form', compact('config', 'namhoc'));
        }

        return view('admin.cauhinh.edit', compact('config', 'namhoc'));
    }

    // Cập nhật từ form thêm mới
    public function update(Request $request)
    {
        $request->validate([
            'MaNamHoc' => 'required|exists:namhoc,MaNamHoc',
            'ThoiGianMoDangKy' => 'required|date',
            'ThoiGianDongDangKy' => 'required|date|after:ThoiGianMoDangKy',
        ]);

        $config = CauHinhHeThong::firstOrNew([
            'MaNamHoc' => $request->MaNamHoc,
        ]);

        $config->ThoiGianMoDangKy = $request->ThoiGianMoDangKy;
        $config->ThoiGianDongDangKy = $request->ThoiGianDongDangKy;
        $config->save();

        return back()->with('success', 'Cập nhật cấu hình thành công!');
    }

    // Cập nhật từ trang edit
    public function updateTime(Request $request, $id)
    {
        $request->validate([
            'MaNamHoc' => 'required|exists:namhoc,MaNamHoc',
            'ThoiGianMoDangKy' => 'required|date',
            'ThoiGianDongDangKy' => 'required|date|after:ThoiGianMoDangKy',
        ]);

        $config = CauHinhHeThong::findOrFail($id);

        $config->MaNamHoc = $request->MaNamHoc;
        $config->ThoiGianMoDangKy = $request->ThoiGianMoDangKy;
        $config->ThoiGianDongDangKy = $request->ThoiGianDongDangKy;
        $config->save();

        return redirect()->route('admin.cauhinh.index')->with('success', 'Đã cập nhật thời gian!');
    }

    public function destroy($id)
    {
        CauHinhHeThong::destroy($id);

        return back()->with('success', 'Đã xóa cấu hình');
    }
}
