<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhanCong;
use App\Models\DeTai;
use App\Models\GiangVien;
use App\Models\CanBoQL;
use Illuminate\Support\Facades\DB;

class PhanCongController extends Controller
{
    /**
     * Hiển thị danh sách phân công
     */
    public function index()
{
    // Load phân công kèm đề tài, sinh viên, giảng viên, cán bộ
    $phancongs = PhanCong::with([
        'detai.sinhviens', 
        'giangvien', 
      //  'canbo'
    ])->paginate(10);

    // ⚠️ THÊM 3 DÒNG NÀY ĐỂ TRÁNH BỊ LỖI
    $detais = DeTai::all();
    $giangviens = GiangVien::all();
   // $canbos = CanBo::all(); // nếu form có dùng

    return view('admin.phancong.index', compact(
        'phancongs', 
        'detais', 
        'giangviens',
       // 'canbos'
    ));
}


    /**
     * Form thêm mới phân công
     */
    public function create()
    {
        $detais = DeTai::all();
        $giangviens = GiangVien::all();
        $canbos = CanBoQL::all();

        // Vai trò lấy từ DB nếu có bảng VaiTroGV, hoặc dùng array tạm
        // $vaiTros = DB::table('VaiTroGV')->pluck('TenVaiTro');
        $vaiTros = ['Hướng dẫn chính','Phản biện','Phụ'];

        return view('admin.phancongs.create', compact('detais', 'giangviens', 'canbos', 'vaiTros'));
    }

    /**
     * Lưu phân công mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'MaDeTai' => 'required|exists:DeTai,MaDeTai',
            'MaGV' => 'required|exists:GiangVien,MaGV',
            'VaiTro' => 'required'
        ]);

        // Tránh duplicate: 1 GV không được phân công cùng đề tài cùng vai trò
        $exists = PhanCong::where('MaDeTai', $request->MaDeTai)
                          ->where('MaGV', $request->MaGV)
                          ->where('VaiTro', $request->VaiTro)
                          ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Giảng viên này đã được phân công với vai trò này cho đề tài này!');
        }

        PhanCong::create($request->all());

        return redirect()->route('admin.phancong.index')
                         ->with('success', 'Thêm phân công thành công!');
    }

    /**
     * Form sửa phân công
     */
    public function edit($id)
    {
        $phancong = PhanCong::findOrFail($id);
        $detais = DeTai::all();
        $giangviens = GiangVien::all();
        $canbos = CanBoQL::all();
        $vaiTros = ['Hướng dẫn chính','Phản biện','Phụ'];

        return view('admin.phancong.edit', compact('phancong', 'detais', 'giangviens', 'canbos', 'vaiTros'));
    }

    /**
     * Cập nhật phân công
     */
    public function update(Request $request, $id)
    {
        $phancong = PhanCong::findOrFail($id);

        $request->validate([
            'MaDeTai' => 'required|exists:DeTai,MaDeTai',
            'MaGV' => 'required|exists:GiangVien,MaGV',
            'VaiTro' => 'required'
        ]);

        // Tránh duplicate khi update
        $exists = PhanCong::where('MaDeTai', $request->MaDeTai)
                          ->where('MaGV', $request->MaGV)
                          ->where('VaiTro', $request->VaiTro)
                          ->where('MaPC', '!=', $id)
                          ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Giảng viên này đã được phân công với vai trò này cho đề tài này!');
        }

        $phancong->update($request->all());

        return redirect()->route('admin.phancong.index')
                         ->with('success', 'Cập nhật phân công thành công!');
    }

    /**
     * Xóa phân công
     */
    public function destroy($id)
    {
        $phancong = PhanCong::findOrFail($id);
        $phancong->delete();

        return redirect()->route('admin.phancong.index')
                         ->with('success', 'Xóa phân công thành công!');
    }
}