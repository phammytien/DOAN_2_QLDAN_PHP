<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class ThongBaoController extends Controller
{
    public function index()
    {
        $maCB = Auth::user()->MaSo;

        
        // Lấy tất cả thông báo do cán bộ này tạo
        $thongbaos = ThongBao::where('MaCB', $maCB)
            ->orderBy('TGDang', 'desc')
            ->get();
        
        return view('canbo.thongbao', compact('thongbaos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NoiDung' => 'required|string|max:500',
            'DoiTuongNhan' => 'required|in:TatCa,SinhVien,GiangVien',
            'MucDo' => 'required|in:Khẩn,Quan trọng,Bình thường',
            'TenFile' => 'nullable|file|max:5120' // 5MB
        ]);

        $data = [
            'NoiDung' => $request->NoiDung,
            'TGDang' => now(),
            'MaCB' => Auth::user()->MaSo,
            'DoiTuongNhan' => $request->DoiTuongNhan,
            'MucDo' => $request->MucDo
        ];

        // Handle file upload
        if ($request->hasFile('TenFile')) {
            $file = $request->file('TenFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/thongbao', $fileName, 'public');
            $data['TenFile'] = $fileName;
        }

        ThongBao::create($data);
        
        return redirect()->route('canbo.thongbao.index')->with('success', 'Thêm thông báo thành công!');
    }

    public function edit($id)
    {
        $tb = ThongBao::findOrFail($id);
        
        // Check ownership
        if ($tb->MaCB !== Auth::user()->MaSo) {
            abort(403, 'Bạn không có quyền chỉnh sửa thông báo này.');
        }

        // Return HTML for AJAX
        if (request()->ajax()) {
            return view('canbo.thongbao.edit-form', compact('tb'))->render();
        }

        return view('canbo.thongbao.edit', compact('tb'));
    }

    public function update(Request $request, $id)
    {
        $tb = ThongBao::findOrFail($id);
        
        // Check ownership
        if ($tb->MaCB !== Auth::user()->MaSo) {
            return back()->with('error', 'Bạn không có quyền chỉnh sửa thông báo này.');
        }

        $request->validate([
            'NoiDung' => 'required|string|max:500',
            'DoiTuongNhan' => 'required|in:TatCa,SinhVien,GiangVien',
            'MucDo' => 'required|in:Khẩn,Quan trọng,Bình thường',
            'TenFile' => 'nullable|file|max:5120'
        ]);

        $data = [
            'NoiDung' => $request->NoiDung,
            'DoiTuongNhan' => $request->DoiTuongNhan,
            'MucDo' => $request->MucDo
        ];

        // Handle file upload
        if ($request->hasFile('TenFile')) {
            // Delete old file
            if ($tb->TenFile && Storage::disk('public')->exists('uploads/thongbao/' . $tb->TenFile)) {
                Storage::disk('public')->delete('uploads/thongbao/' . $tb->TenFile);
            }

            $file = $request->file('TenFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/thongbao', $fileName, 'public');
            $data['TenFile'] = $fileName;
        }

        $tb->update($data);
        
        return redirect()->route('canbo.thongbao.index')->with('success', 'Cập nhật thông báo thành công!');
    }

    public function destroy($id)
    {
        $tb = ThongBao::findOrFail($id);
        
        // Check ownership
        if ($tb->MaCB !== Auth::user()->MaSo) {
            return back()->with('error', 'Bạn không có quyền xóa thông báo này.');
        }

        // Delete file if exists
        if ($tb->TenFile && Storage::disk('public')->exists('uploads/thongbao/' . $tb->TenFile)) {
            Storage::disk('public')->delete('uploads/thongbao/' . $tb->TenFile);
        }

        $tb->delete();
        
        return redirect()->route('canbo.thongbao.index')->with('success', 'Xóa thông báo thành công!');
    }
}