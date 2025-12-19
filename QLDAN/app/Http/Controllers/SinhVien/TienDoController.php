<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TienDo;
use App\Models\ChamDiem;
use App\Models\DeTai;
use Illuminate\Support\Facades\Auth;

class TienDoController extends Controller
{
    /**
     * Hiển thị danh sách tiến độ của sinh viên
     */
    public function index()
    {
        $maSV = Auth::user()->MaSo;

        // Lấy đề tài của sinh viên
        $detai = DeTai::whereHas('sinhViens', function ($q) use ($maSV) {
                $q->where('DeTai_SinhVien.MaSV', $maSV);
            })
            ->with(['sinhViens', 'tiendos'])
            ->first();

        // Nếu không có đề tài
        if (!$detai) {
            return view('sinhvien.tiendo.index', [
                'detai' => null,
                'tiendos' => collect(),
                'chamdiems' => collect()
            ]);
        }

        // Lấy tiến độ của đề tài
        $tiendos = $detai->tiendos;

        // Lấy điểm chấm của sinh viên này
        $chamdiems = ChamDiem::where('MaSV', $maSV)
            ->where('MaDeTai', $detai->MaDeTai)
            ->get()
            ->keyBy('MaTienDo');

        return view('sinhvien.tiendo.index', compact('detai', 'tiendos', 'chamdiems'));
    }

    /**
     * Nộp báo cáo tiến độ
     */
    public function update(Request $request, $id)
    {
        $tiendo = \App\Models\TienDo::findOrFail($id);
        
        // Upload file báo cáo
        if ($request->hasFile('file_baocao')) {
            $fileBaoCao = \App\Helpers\FileHelper::uploadFile($request->file('file_baocao'), 'baocao', 'storage');
            $tiendo->LinkFile = $fileBaoCao->path;
            $tiendo->TenFile = $fileBaoCao->name;
        }
        
        // Upload file code
        if ($request->hasFile('file_code')) {
            $fileCode = \App\Helpers\FileHelper::uploadFile($request->file('file_code'), 'code', 'storage');
            $tiendo->FileCodeID = $fileCode->id;
        }
        
        // Cập nhật ngày nộp
        $tiendo->NgayNop = now();
        $tiendo->save();
        
        return redirect()->route('sinhvien.baocao.index')
            ->with('success', 'Nộp báo cáo tiến độ thành công!');
    }

    /**
     * Xem chi tiết tiến độ
     */
    public function show($id)
    {
        $maSV = Auth::user()->MaSo;
        
        // Lấy tiến độ
        $tiendo = TienDo::findOrFail($id);
        
        // Kiểm tra quyền truy cập
        $detai = DeTai::join('DeTai_SinhVien', 'DeTai.MaDeTai', '=', 'DeTai_SinhVien.MaDeTai')
            ->where('DeTai_SinhVien.MaSV', $maSV)
            ->where('DeTai.MaDeTai', $tiendo->MaDeTai)
            ->select('DeTai.*')
            ->first();
        
        if (!$detai) {
            abort(403, 'Bạn không có quyền xem tiến độ này.');
        }
        
        // Kiểm tra đã nộp báo cáo chưa (dựa vào NgayNop của TienDo)
        $baocao = null;
        if ($tiendo->NgayNop) {
            // Đã nộp - lấy thông tin file nếu có
            $baocao = (object)[
                'NgayNop' => $tiendo->NgayNop,
                'LinkFile' => $tiendo->LinkFile,
                'TenFile' => $tiendo->TenFile
            ];
        }
        
        return view('sinhvien.tiendo.show', compact('tiendo', 'detai', 'baocao'));
    }
    /**
     * Yêu cầu nộp bổ sung
     */
    public function requestLate($id)
    {
        $tiendo = TienDo::findOrFail($id);
        $tiendo->TrangThai = 'Xin nộp bổ sung';
        $tiendo->save();

        return redirect()->back()->with('success', 'Đã gửi yêu cầu nộp bổ sung thành công!');
    }
}
