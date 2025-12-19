<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThongBao;
use App\Models\CanBoQL;
use Illuminate\Http\Request;

class ThongBaoController extends Controller
{
    public function index(Request $request)
    {
        $query = ThongBao::with('canBo')
                        ->orderByRaw("FIELD(MucDo,'Khan','QuanTrong','BinhThuong')")
                        ->orderBy('TGDang', 'desc');

        // Lọc theo đối tượng nhận
        if ($request->filled('doituong')) {
            $query->where('DoiTuongNhan', $request->doituong);
        }

        $tbs = $query->get();
        $cbs = CanBoQL::all();

        return view('admin.thongbao.index', compact('tbs', 'cbs'));
    }

    public function create()
    {
        $cbs = CanBoQL::all();
        return view('admin.thongbao.create', compact('cbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NoiDung'       => 'required|string|max:300',
            'MaCB'          => 'nullable|string|exists:CanBoQL,MaCB',
            'DoiTuongNhan'  => 'required|in:SV,GV,TatCa',
            'MucDo'         => 'required|in:Khan,QuanTrong,BinhThuong',
            'TenFile'       => 'nullable|file|max:5120'
        ]);

        $fileName = null;

        if ($request->hasFile('TenFile')) {
            $fileName = time() . '_' . $request->file('TenFile')->getClientOriginalName();
            $request->file('TenFile')->storeAs('uploads/thongbao', $fileName, 'public');
        }

        ThongBao::create([
            'NoiDung'       => $request->NoiDung,
            'TGDang'        => now(),
            'MaCB'          => $request->MaCB,
            'TenFile'       => $fileName,
            'DoiTuongNhan'  => $request->DoiTuongNhan,
            'MucDo'         => $request->MucDo,
        ]);



        return redirect()
            ->back()
            ->with('success', 'Đăng thông báo thành công');
    }

    public function edit($id)
    {
        $tb = ThongBao::findOrFail($id);
        $cbs = CanBoQL::all();

        if (request()->ajax()) {
            return view('admin.thongbao.edit_form', compact('tb', 'cbs'));
        }

        return view('admin.thongbao.edit', compact('tb', 'cbs'));
    }

    public function update(Request $request, $id)
    {
        $tb = ThongBao::findOrFail($id);

        $request->validate([
            'NoiDung'       => 'required|string|max:300',
            'MaCB'          => 'nullable|string|exists:CanBoQL,MaCB',
            'DoiTuongNhan'  => 'required|in:SV,GV,TatCa',
            'MucDo'         => 'required|in:Khan,QuanTrong,BinhThuong',
            'TenFile'       => 'nullable|file|max:5120'
        ]);

        $fileName = $tb->TenFile;

        if ($request->hasFile('TenFile')) {

            if ($fileName && file_exists(storage_path("app/public/uploads/thongbao/" . $fileName))) {
                unlink(storage_path("app/public/uploads/thongbao/" . $fileName));
            }

            $fileName = time() . '_' . $request->file('TenFile')->getClientOriginalName();
            $request->file('TenFile')->storeAs('uploads/thongbao', $fileName, 'public');
        }

        $tb->update([
            'NoiDung'       => $request->NoiDung,
            'MaCB'          => $request->MaCB,
            'TenFile'       => $fileName,
            'DoiTuongNhan'  => $request->DoiTuongNhan,
            'MucDo'         => $request->MucDo
        ]);

        return redirect()
            ->route('admin.thongbao.index')
            ->with('success', 'Cập nhật thông báo thành công');
    }

    public function destroy($id)
    {
        $tb = ThongBao::findOrFail($id);

        if ($tb->TenFile && file_exists(storage_path("app/public/uploads/thongbao/" . $tb->TenFile))) {
            unlink(storage_path("app/public/uploads/thongbao/" . $tb->TenFile));
        }

        $tb->delete();

        return redirect()
            ->route('admin.thongbao.index')
            ->with('success', 'Xóa thông báo thành công');
    }
}
