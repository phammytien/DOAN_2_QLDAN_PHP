<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\NamHoc;
use App\Models\SinhVien;
use App\Models\DeTai;
use App\Models\BaoCao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DiemTheoLopExport;

class BaoCaoThongKeController extends Controller
{
    public function index(Request $request)
    {
        $lops = Lop::orderBy('TenLop')->get();
        $namhocs = NamHoc::orderBy('TenNamHoc', 'desc')->get();
        
        $sinhviens = null;
        $lopName = '';
        
        if ($request->filled('lop')) {
            $query = SinhVien::where('MaLop', $request->lop)
                ->with(['deTai', 'baoCao.fileBaoCao']);
            
            // Lấy tên lớp
            $lop = Lop::find($request->lop);
            $lopName = $lop ? $lop->TenLop : '';
            
            // Lọc theo năm học nếu có
            if ($request->filled('namhoc')) {
                $query->whereHas('deTai', function($q) use ($request) {
                    $q->where('MaNamHoc', $request->namhoc);
                });
            }
            
            $sinhviens = $query->get();
            
            // Tính điểm trung bình cho mỗi sinh viên
            foreach ($sinhviens as $sv) {
                // Lấy đề tài đầu tiên (vì deTai() trả về collection)
                $deTaiFirst = $sv->deTai->first();
                
                if ($deTaiFirst) {
                    $sv->deTai = $deTaiFirst; // Gán lại để dùng như object
                    
                    // Lấy điểm từ bảng ChamDiem
                    // Lưu ý: Đây là lấy điểm của một lần chấm bất kỳ. 
                    // Nếu cần điểm trung bình hoặc điểm cụ thể (GVHD/GVPB), cần logic phức tạp hơn.
                    $diem = DB::table('ChamDiem')
                        ->where('MaDeTai', $deTaiFirst->MaDeTai)
                        ->orderBy('Diem', 'desc') // Lấy điểm cao nhất nếu có nhiều điểm (tạm thời)
                        ->first();
                    
                    if ($diem && isset($diem->Diem)) {
                        $sv->diemTrungBinh = $diem->Diem;
                    } else {
                        $sv->diemTrungBinh = null;
                    }
                    
                    // Lấy báo cáo mới nhất
                    $sv->baoCao = BaoCao::where('MaSV', $sv->MaSV)
                        ->where('MaDeTai', $deTaiFirst->MaDeTai)
                        ->with('fileBaoCao')
                        ->orderBy('NgayNop', 'desc')
                        ->first();
                } else {
                    $sv->deTai = null;
                    $sv->diemTrungBinh = null;
                    $sv->baoCao = null;
                }
            }
        }

        
        return view('admin.baocao.thongke', compact('lops', 'namhocs', 'sinhviens', 'lopName'));
    }
    
    public function exportDiem(Request $request)
    {
        $lopId = $request->get('lop');
        $namhocId = $request->get('namhoc');
        
        if (!$lopId) {
            return back()->with('error', 'Vui lòng chọn lớp để xuất file');
        }
        
        $lop = Lop::find($lopId);
        $fileName = 'Diem_' . ($lop ? $lop->TenLop : 'Lop') . '_' . date('Y-m-d') . '.xls';
        
        // Logic lấy dữ liệu (giống index)
        $query = SinhVien::where('MaLop', $lopId)
            ->with(['deTai', 'baoCao.fileBaoCao'])
            ->orderBy('TenSV');
        
        if ($namhocId) {
            $query->whereHas('deTai', function($q) use ($namhocId) {
                $q->where('MaNamHoc', $namhocId);
            });
        }
        
        $sinhviens = $query->get();
        
        foreach ($sinhviens as $sv) {
            $deTaiFirst = $sv->deTai->first();
            
            if ($deTaiFirst) {
                $sv->deTai = $deTaiFirst;
                
                $diem = DB::table('ChamDiem')
                    ->where('MaDeTai', $deTaiFirst->MaDeTai)
                    ->orderBy('Diem', 'desc')
                    ->first();
                
                $sv->diemTrungBinh = ($diem && isset($diem->Diem)) ? $diem->Diem : null;
            } else {
                $sv->deTai = null;
                $sv->diemTrungBinh = null;
            }
        }
        
        return response(view('admin.baocao.export_diem', compact('sinhviens')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}