<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaoCao;
use App\Models\DeTai;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BaoCaoController extends Controller
{
    public function index(Request $request)
    {
        $query = BaoCao::with(['deTai.giangVien', 'sinhVien.lop']); // Eager load quan hệ để tránh N+1 problem

        // Lọc theo đề tài
        if ($request->filled('detai')) {
            $query->where('MaDeTai', $request->detai); 
        }

        // Lọc theo sinh viên
        if ($request->filled('sinhvien')) {
            $query->where('MaSV', $request->sinhvien);
        }

        // Lọc theo trạng thái
        if ($request->filled('trangthai')) {
            $query->where('TrangThai', $request->trangthai);
        }

        $baocaos = $query->orderBy('NgayNop', 'desc')->paginate(15);
        $detais = DeTai::all();
        $sinhviens = SinhVien::all();

        return view('admin.baocao.index', compact('baocaos', 'detais', 'sinhviens'));
    }

    public function create()
    {
        $detais = DeTai::all();
        return view('admin.baocao.create', compact('detais'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'MaDeTai' => 'required|integer|exists:DeTai,MaDeTai',
    //         'file' => 'required|file|max:51200'
    //     ]);

    //     $maSV = Auth::user()->sinhVien->MaSV;

    //     if (!$maSV) {
    //         return back()->with('error', 'Không tìm thấy mã sinh viên của tài khoản này.');
    //     }

    //     $file = $request->file('file');
    //     $fileName = time() . '_' . $file->getClientOriginalName();
    //     $path = $file->storeAs('baocao', $fileName, 'public');

    //     BaoCao::create([
    //         'MaDeTai' => $request->MaDeTai,
    //         'MaSV' => $maSV,
    //         'TenFile' => $file->getClientOriginalName(),
    //         'LinkFile' => $path,
    //         'NgayNop' => now(),
    //         'LanNop' => 1,
    //         'NhanXet' => null,
    //         'TrangThai' => 'Chờ duyệt'
    //     ]);

    //     return redirect()->route('admin.baocao.index')->with('success','Nộp báo cáo thành công');
    // }

    public function edit($id)
    {
        $bc = BaoCao::findOrFail($id);
        $detais = DeTai::all();
        return view('admin.baocao.edit', compact('bc','detais'));
    }

    public function update(Request $request, $id)
    {
        $bc = BaoCao::findOrFail($id);

        $request->validate([
            'MaDeTai' => 'required |integer|exists:DeTai,MaDeTai',
            'file' => 'nullable|file|max:51200' // Tệp tin có thể không được cập nhật
        ]);

        // Nếu có file mới thì cập nhật file
        if ($request->hasFile('file')) {

            // Xóa file cũ
            if ($bc->LinkFile) {
                Storage::disk('public')->delete($bc->LinkFile); 
            }

            $file = $request->file('file');  
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $path = $file->storeAs('baocao', $fileName, 'public');

            $bc->TenFile = $file->getClientOriginalName();
            $bc->LinkFile = $path;
            $bc->NgayNop = now(); 
        }

        $bc->MaDeTai = $request->MaDeTai;
        $bc->LanNop = $request->LanNop ?? $bc->LanNop;
        $bc->NhanXet = $request->NhanXet ?? $bc->NhanXet;

        $bc->save();

        return redirect()->route('admin.baocao.index')->with('success','Cập nhật báo cáo thành công');
    }

    public function duyet($id)
    {
        $bc = BaoCao::findOrFail($id);//tìm báo cáo theo ID
        $bc->TrangThai = 'Đã duyệt'; //cập nhật trạng thái báo cáo thành "Đã duyệt"
        $bc->save();

        return redirect()->route('admin.baocao.index')->with('success','Đã duyệt báo cáo.');
    }

    public function yeuCauChinhSua(Request $request, $id)
    {
        $bc = BaoCao::findOrFail($id); 

        $request->validate([ 
            'NhanXet' => 'required|string'
        ]);  //xác thực rằng nhận xét là bắt buộc và phải là chuỗi

        $bc->NhanXet = $request->NhanXet; 
        $bc->TrangThai = 'Yêu cầu chỉnh sửa'; 
        $bc->save();

        return redirect()->route('admin.baocao.index')->with('success','Đã gửi yêu cầu chỉnh sửa.'); 
    } 

    public function destroy($id)  //xóa báo cáo
    {
        $bc = BaoCao::findOrFail($id);

        if ($bc->LinkFile) { 
            Storage::disk('public')->delete($bc->LinkFile); //xóa tệp tin liên kết với báo cáo khỏi bộ nhớ
        }

        $bc->delete(); 

        return redirect()->route('admin.baocao.index')->with('success','Xóa báo cáo thành công');
    }

    /**
     * Admin duyệt điểm cuối
     */
    public function duyetDiemCuoi($id)
    {
        $baocao = BaoCao::with(['deTai.sinhViens'])->findOrFail($id);
        
        if ($baocao->TrangThai !== 'Chờ duyệt điểm') { 
            return back()->with('error', 'Báo cáo này không ở trạng thái chờ duyệt điểm.'); 
        } 
        
        // Lấy tất cả sinh viên của đề tài
        $sinhViens = $baocao->deTai->sinhViens;
        
        foreach ($sinhViens as $sv) {
            // 1. Tính điểm trung bình các tiến độ (40%)
            $diemTienDo = \App\Models\ChamDiem::where('MaDeTai', $baocao->MaDeTai) // Lấy điểm tiến độ của đề tài
                ->where('MaSV', $sv->MaSV) // Lấy điểm tiến độ của sinh viên
                ->whereNotNull('MaTienDo') // Chỉ lấy điểm tiến độ
                ->avg('Diem'); // Tính điểm trung bình
            
            // 2. Lấy điểm báo cáo cuối (60%) - điểm giảng viên chấm cho báo cáo cuối
            $chamDiemBaoCao = \App\Models\ChamDiem::where('MaDeTai', $baocao->MaDeTai)
                ->where('MaSV', $sv->MaSV) // Lấy điểm báo cáo của sinh viên
                ->whereNull('MaTienDo') // Điểm báo cáo cuối không có MaTienDo
                ->first(); // Lấy bản ghi chấm điểm báo cáo cuối
            
            if (!$chamDiemBaoCao || !$chamDiemBaoCao->Diem) { 
                continue; // Bỏ qua nếu chưa có điểm báo cáo cuối
            }
            
            $diemBaoCao = $chamDiemBaoCao->Diem;
            
            // 3. Tính điểm cuối: 40% tiến độ + 60% báo cáo
            $diemTienDoWeighted = ($diemTienDo ?? 0) * 0.4; 
            $diemBaoCaoWeighted = $diemBaoCao * 0.6; 
            $diemCuoi = $diemTienDoWeighted + $diemBaoCaoWeighted;
            
            // 4. Cập nhật DiemCuoi vào bảng ChamDiem (không làm tròn)
            $chamDiemBaoCao->DiemCuoi = $diemCuoi;
            $chamDiemBaoCao->TrangThai = 'Đã duyệt';
            $chamDiemBaoCao->save();
        }
        
        // Cập nhật trạng thái báo cáo
        $baocao->TrangThai = 'Đã duyệt điểm';
        $baocao->save();
        
        // Đánh dấu đề tài là Hoàn thành
        if ($baocao->deTai) {
            $baocao->deTai->TrangThai = 'Hoàn thành';
            $baocao->deTai->save();
        }
        
        return back()->with('success', 'Đã duyệt điểm cuối thành công. Điểm cuối = (Điểm TB tiến độ × 40%) + (Điểm báo cáo × 60%)');
    }

    /**
     * Admin từ chối điểm, yêu cầu chấm lại
     */
    public function tuChoiDiem(Request $request, $id)
    {
        $baocao = BaoCao::findOrFail($id); 
        
        if ($baocao->TrangThai !== 'Chờ duyệt điểm') {
            return back()->with('error', 'Báo cáo này không ở trạng thái chờ duyệt điểm.');
        }
        
        $request->validate([
            'LyDo' => 'required|string|max:1000'
        ]); // Xác thực lý do từ chối điểm
        
        $baocao->TrangThai = 'Yêu cầu chấm lại';
        $baocao->NhanXet = $request->LyDo;
        $baocao->save();
        
        return back()->with('success', 'Đã yêu cầu giảng viên chấm lại.');
    }

}