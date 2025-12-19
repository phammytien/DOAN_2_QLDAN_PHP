<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaoCao;
use App\Models\DeTai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TienDo;

class BaoCaoController extends Controller
{
    public function index()
    {
        $maGV = Auth::user()?->MaSo;
        $baocaos = BaoCao::whereHas('deTai', function($q) use ($maGV){
            $q->where('MaGV', $maGV);
        })->with(['deTai', 'sinhVien'])->orderBy('NgayNop', 'desc')->paginate(15);

        return view('giangvien.baocao.index', compact('baocaos'));
    }

    public function approveLate($id)
    {
        $baocao = BaoCao::findOrFail($id);
        
        // Check ownership
        if ($baocao->deTai->MaGV !== Auth::user()->MaSo) {
             return back()->with('error', 'Bạn không có quyền duyệt báo cáo này.');
        }

        if ($baocao->TrangThai !== 'Xin nộp bổ sung') {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        $baocao->TrangThai = 'Được nộp bổ sung';
        $baocao->save();

        return back()->with('success', 'Đã duyệt yêu cầu nộp bổ sung.');
    }
    
    public function rejectLate($id)
    {
        $baocao = BaoCao::findOrFail($id);

        // Check ownership
        if ($baocao->deTai->MaGV !== Auth::user()->MaSo) {
             return back()->with('error', 'Bạn không có quyền từ chối báo cáo này.');
        }
        
        if ($baocao->TrangThai !== 'Xin nộp bổ sung') {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        $baocao->TrangThai = 'Từ chối nộp bù';
        $baocao->save();

        return back()->with('success', 'Đã từ chối yêu cầu nộp bổ sung.');
    }

    public function approve(Request $request, $id)
    {
        $baocao = BaoCao::findOrFail($id);
        
        // Check ownership
        if ($baocao->deTai->MaGV !== Auth::user()->MaSo) {
             return back()->with('error', 'Bạn không có quyền duyệt báo cáo này.');
        }

        if ($baocao->TrangThai !== 'Chờ duyệt') {
            return back()->with('error', 'Báo cáo này không ở trạng thái chờ duyệt.');
        }

        $baocao->TrangThai = 'Đã duyệt';
        if ($request->filled('NhanXet')) {
            $baocao->NhanXet = $request->NhanXet;
        }
        $baocao->save();

        return back()->with('success', 'Đã duyệt báo cáo thành công.');
    }

    public function comment(Request $request, $id)
    {
        $baocao = BaoCao::findOrFail($id);
        
        // Check ownership
        if ($baocao->deTai->MaGV !== Auth::user()->MaSo) {
             return back()->with('error', 'Bạn không có quyền nhận xét báo cáo này.');
        }

        $request->validate([
            'NhanXet' => 'required|string|max:1000'
        ]);

        $baocao->NhanXet = $request->NhanXet;
        $baocao->save();

        return back()->with('success', 'Đã lưu nhận xét thành công.');
    }

    public function destroy($id)
    {
        $baocao = BaoCao::findOrFail($id);
        
        // Check ownership
        if ($baocao->deTai->MaGV !== Auth::user()->MaSo) {
             return back()->with('error', 'Bạn không có quyền xóa báo cáo này.');
        }

        // Delete file if exists
        // Delete files if exist
        if ($baocao->fileBaoCao) {
            if (Storage::disk('public')->exists(str_replace('storage/', '', $baocao->fileBaoCao->path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $baocao->fileBaoCao->path));
            }
            $baocao->fileBaoCao->delete();
        }

        if ($baocao->fileCode) {
            if (Storage::disk('public')->exists(str_replace('storage/', '', $baocao->fileCode->path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $baocao->fileCode->path));
            }
            $baocao->fileCode->delete();
        }

        $baocao->delete();

        return back()->with('success', 'Đã xóa báo cáo thành công.');
    }

    /**
     * Hiển thị form tổng hợp điểm
     */
    public function tongHopDiem($maDeTai, $maSV)
    {
        $maGV = Auth::user()?->MaSo;
        
        // Lấy đề tài và kiểm tra quyền
        $deTai = DeTai::where('MaDeTai', $maDeTai)
            ->where('MaGV', $maGV)
            ->firstOrFail();
        
        // Kiểm tra đề tài đã hoàn thành chưa
        if (in_array($deTai->TrangThai, ['Hoàn thành', 'Đã hoàn thành'])) {
            return back()->with('error', 'Đề tài đã hoàn thành. Không thể tính điểm lại.');
        }
        
        // Lấy tất cả tiến độ đã chấm
        $tiendos = TienDo::where('MaDeTai', $maDeTai)
            ->whereNotNull('Diem')
            ->get();
        
        // Lấy báo cáo cuối
        $baocao = BaoCao::where('MaDeTai', $maDeTai)
            ->where('MaSV', $maSV)
            ->first();
        
        return view('giangvien.baocao.tonghop', compact('deTai', 'tiendos', 'baocao', 'maSV'));
    }

    /**
     * Tính tổng điểm và gửi Admin duyệt
     */
    public function tinhDiemTong(Request $request)
    {
        $maGV = Auth::user()?->MaSo;
        
        $request->validate([
            'MaDeTai' => 'required|exists:DeTai,MaDeTai',
            'MaSV' => 'required|exists:SinhVien,MaSV',
            'DiemBaoCaoCuoi' => 'required|numeric|min:0|max:10'
        ]);
        
        // Kiểm tra quyền
        $deTai = DeTai::where('MaDeTai', $request->MaDeTai)
            ->where('MaGV', $maGV)
            ->firstOrFail();
        
        // Kiểm tra đề tài đã hoàn thành chưa
        if (in_array($deTai->TrangThai, ['Hoàn thành', 'Đã hoàn thành'])) {
            return back()->with('error', 'Đề tài đã hoàn thành. Không thể tính điểm lại.');
        }
        
        // Tính điểm tiến độ (trung bình tất cả đợt × 40%)
        $tiendos = TienDo::where('MaDeTai', $request->MaDeTai)
            ->whereNotNull('Diem')
            ->get();
        
        if ($tiendos->isEmpty()) {
            return back()->with('error', 'Chưa có điểm tiến độ nào được chấm.');
        }
        
        // Tính trung bình điểm sau khi trừ trễ
        $diemTrungBinh = $tiendos->avg('DiemSauTruTre');
        $diemTienDo = $diemTrungBinh * 0.4; // 40%
        
        // Điểm báo cáo cuối × 60%
        $diemBaoCaoCuoi = $request->DiemBaoCaoCuoi * 0.6;
        
        // Tổng điểm
        $diemTong = $diemTienDo + $diemBaoCaoCuoi;
        
        // Cập nhật hoặc tạo báo cáo
        $baocao = BaoCao::updateOrCreate(
            [
                'MaDeTai' => $request->MaDeTai,
                'MaSV' => $request->MaSV
            ],
            [
                'DiemTienDo' => $diemTienDo,
                'DiemBaoCaoCuoi' => $diemBaoCaoCuoi,
                'DiemTong' => $diemTong,
                'TrangThai' => 'Chờ duyệt điểm'
            ]
        );
        
        return redirect()->route('giangvien.baocao.index')
            ->with('success', 'Đã tính điểm và gửi Admin duyệt. Tổng điểm: ' . number_format($diemTong, 2));
    }
}
