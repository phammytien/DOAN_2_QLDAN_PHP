<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ChamDiem;
use App\Models\DeTai;
use App\Models\GiangVien;
use App\Models\Lop;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Auth;

class DiemController extends Controller
{
    public function index(Request $request)
    {
        
        $maCB = Auth::user()->MaSo;
        
        // Query theo DeTai_SinhVien để phân trang đúng số dòng
        $query = DB::table('DeTai_SinhVien')
            ->join('DeTai', 'DeTai_SinhVien.MaDeTai', '=', 'DeTai.MaDeTai')
            ->join('SinhVien', 'DeTai_SinhVien.MaSV', '=', 'SinhVien.MaSV')
            ->leftJoin('Lop', 'SinhVien.MaLop', '=', 'Lop.MaLop')
            ->select(
                'DeTai.MaDeTai',
                'DeTai.TenDeTai',
                'SinhVien.MaSV',
                'SinhVien.TenSV',
                'Lop.MaLop',
                'Lop.TenLop'
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('DeTai.TenDeTai', 'like', "%{$search}%")
                  ->orWhere('SinhVien.TenSV', 'like', "%{$search}%");
            });
        }
        
        // Filter by project
        if ($request->filled('detai')) {
            $query->where('DeTai.MaDeTai', $request->detai);
        }
        
        // Filter by class
        if ($request->filled('lop')) {
            $query->where('SinhVien.MaLop', $request->lop);
        }
        
        // Pagination - 5 rows per page
        $results = $query->orderBy('DeTai.MaDeTai', 'desc')
                        ->orderBy('SinhVien.MaSV', 'asc')
                        ->paginate(5)
                        ->withQueryString();
        
        // Load related data for each result
        $detaiIds = $results->pluck('MaDeTai')->unique();
        $detais = DeTai::with([
            'sinhViens', 
            'phancongs.giangVien', 
            'chamdiems.giangvien',
            'chamdiems.sinhvien'
        ])->whereIn('MaDeTai', $detaiIds)->get()->keyBy('MaDeTai');
        
        // Get filter options
        $allDetais = DeTai::all();
        $allLops = Lop::orderBy('TenLop')->get();
        
        // Statistics
        $stats = $this->getStatistics($maCB);
        
        return view('canbo.diem', compact('results', 'detais', 'allDetais', 'allLops', 'stats'));
    }
    
    public function updateScore(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ChamDiem,MaCham',
            'field' => 'required|in:Diem,NhanXet,DiemCuoi',
            'value' => 'nullable'
        ]);
        
        $chamdiem = ChamDiem::findOrFail($request->id);
        
        // Check permission
        
        $maCB = Auth::user()->MaSo;
        if ($chamdiem->detai->MaCB !== $maCB) {
            return response()->json(['success' => false, 'message' => 'Không có quyền'], 403);
        }
        
        $chamdiem->{$request->field} = $request->value;
        $chamdiem->save();
        
        return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
    }
    
    public function batchApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:ChamDiem,MaCham'
        ]);
        
       // $maCB = auth()->user()->MaSo;
        $maCB = Auth::user()->MaSo;
        
        $updated = ChamDiem::whereIn('MaCham', $request->ids)
            ->whereHas('detai', function($q) use ($maCB) {
                $q->where('MaCB', $maCB);
            })
            ->update(['TrangThai' => 'Đã duyệt']);
        
        return response()->json([
            'success' => true, 
            'message' => "Đã duyệt {$updated} bản ghi"
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $cd = ChamDiem::findOrFail($id);
        
        // Check permission - Temporarily disabled for testing
        // $maCB = auth()->user()->MaSo;
        // if ($cd->detai->MaCB !== $maCB) {
        //     return back()->with('error', 'Không có quyền');
        // }
        
        $MaDeTai = $cd->MaDeTai;
        $MaSV = $cd->MaSV;
        
        $list = ChamDiem::where('MaDeTai', $MaDeTai)
                        ->where('MaSV', $MaSV)
                        ->get();
        
        // Tính điểm tiến độ trung bình (MaTienDo IS NOT NULL)
        $diemTienDoList = $list->whereNotNull('MaTienDo')->where('Diem', '>', 0);
        $diemTienDoTB = $diemTienDoList->count() > 0 ? $diemTienDoList->avg('Diem') : 0;
        
        // Tính điểm báo cáo cuối trung bình (MaTienDo IS NULL)
        $diemBaoCaoList = $list->whereNull('MaTienDo')->where('Diem', '>', 0);
        $diemBaoCaoTB = $diemBaoCaoList->count() > 0 ? $diemBaoCaoList->avg('Diem') : 0;
        
        // Nếu không có điểm tiến độ, điểm cuối = điểm báo cáo (100%)
        // Nếu có điểm tiến độ, điểm cuối = (Điểm tiến độ × 40%) + (Điểm báo cáo × 60%)
        if ($diemTienDoTB == 0) {
            $diemCuoi = $diemBaoCaoTB;
        } else {
            $diemCuoi = ($diemTienDoTB * 0.4) + ($diemBaoCaoTB * 0.6);
        }
        
        if ($request->TrangThai === 'Đã duyệt') {
            // Update all records for this student-project combination
            ChamDiem::where('MaDeTai', $MaDeTai)
                    ->where('MaSV', $MaSV)
                    ->update([
                        'DiemCuoi' => $diemCuoi,
                        'TrangThai' => 'Đã duyệt'
                    ]);
            
            // Cập nhật trạng thái đề tài thành "Đã hoàn thành"
            $deTai = DeTai::find($MaDeTai);
            if ($deTai) {
                $deTai->TrangThai = 'Đã hoàn thành';
                $deTai->save();
            }
        } else {
            // Update all records
            ChamDiem::where('MaDeTai', $MaDeTai)
                    ->where('MaSV', $MaSV)
                    ->update([
                        'DiemCuoi' => null,
                        'TrangThai' => $request->TrangThai
                    ]);
        }
        
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
    
    public function show($id)
    {
        $cd = ChamDiem::with(['detai', 'sinhvien', 'giangvien'])->findOrFail($id);
        
        // Check permission - Temporarily disabled for testing
        // $maCB = auth()->user()->MaSo;
        // if ($cd->detai->MaCB !== $maCB) {
        //     abort(403, 'Không có quyền');
        // }
        
        $MaDeTai = $cd->MaDeTai;
        $MaSV = $cd->MaSV;
        
        // Get all scoring records
        $listGV = ChamDiem::where('MaDeTai', $MaDeTai)
                    ->where('MaSV', $MaSV)
                    ->with('giangvien')
                    ->get();
        
        // Get PhanCong to determine roles
        $phancongs = \App\Models\PhanCong::where('MaDeTai', $MaDeTai)
                    ->with('giangVien')
                    ->get();
        
        // Map VaiTro from PhanCong to each ChamDiem record
        $listGV = $listGV->map(function($cham) use ($phancongs) {
            $phancong = $phancongs->firstWhere('MaGV', $cham->MaGV);
            if ($phancong) {
                $cham->VaiTroDisplay = $phancong->VaiTro;
            } else {
                $cham->VaiTroDisplay = $cham->VaiTro ?? 'N/A';
            }
            return $cham;
        });
        
        // Separate GVHD and GVPB based on PhanCong roles
        $gvhd = $listGV->first(function($cham) {
            return str_contains(strtolower($cham->VaiTroDisplay ?? ''), 'hướng dẫn');
        });
        
        $gvpb = $listGV->first(function($cham) {
            return str_contains(strtolower($cham->VaiTroDisplay ?? ''), 'phản biện');
        });
        
        // Lấy tất cả tiendos của đề tài để tính điểm
        $detai = DeTai::with(['tiendos'])->find($MaDeTai);
        $diemTienDoTotals = 0;
        $countTienDo = 0;
        
        if ($detai && $detai->tiendos) {
            foreach ($detai->tiendos as $tiendo) {
                // Ưu tiên điểm riêng trong ChamDiem
                $chamDiemRieng = $listGV->first(function($item) use ($tiendo) {
                     return $item->MaTienDo == $tiendo->MaTienDo;
                });
                
                if ($chamDiemRieng && $chamDiemRieng->Diem) {
                    $diemTienDoTotals += $chamDiemRieng->Diem;
                    $countTienDo++;
                } 
                // Nếu không có điểm riêng thì lấy điểm chung (nếu có)
                elseif ($tiendo->Diem) {
                    $diemTienDoTotals += $tiendo->Diem;
                    $countTienDo++;
                }
            }
        }
        
        // Tính điểm tiến độ trung bình
        $diemTienDoTB = $countTienDo > 0 ? ($diemTienDoTotals / $countTienDo) : 0;
        
        // Tính điểm báo cáo cuối trung bình (MaTienDo IS NULL)
        $diemBaoCaoList = $listGV->whereNull('MaTienDo')->where('Diem', '>', 0);
        $diemBaoCaoTB = $diemBaoCaoList->count() > 0 ? $diemBaoCaoList->avg('Diem') : 0;
        
        // Nếu không có điểm tiến độ, điểm cuối = điểm báo cáo (100%)
        // Nếu có điểm tiến độ, điểm cuối = (Điểm tiến độ × 40%) + (Điểm báo cáo × 60%)
        if ($diemTienDoTB == 0 && $countTienDo == 0) {
            $diemCuoi = $diemBaoCaoTB;
        } else {
            $diemCuoi = ($diemTienDoTB * 0.4) + ($diemBaoCaoTB * 0.6);
        }
        
        // Average score (for backward compatibility)
        $diemTB = $listGV->avg('Diem');
        
        return view('canbo.diem-show', compact('cd', 'listGV', 'gvhd', 'gvpb', 'diemTB', 'diemTienDoTB', 'diemBaoCaoTB', 'diemCuoi'));
    }
    
    public function exportExcel(Request $request)
    {
        //$maCB = auth()->user()->MaSo;
        $maCB = Auth::user()->MaSo;
        
        $chamdiems = ChamDiem::whereHas('detai', function($q) use ($maCB) {
            $q->where('MaCB', $maCB);
        })->with(['detai', 'sinhvien', 'giangvien'])->get();
        
        // Simple CSV export
        $filename = 'diem_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($chamdiems) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            fputcsv($file, ['Mã chấm', 'Đề tài', 'Sinh viên', 'Giảng viên', 'Vai trò', 'Điểm', 'Điểm cuối', 'Nhận xét', 'Ngày chấm', 'Trạng thái']);
            
            foreach ($chamdiems as $cd) {
                fputcsv($file, [
                    $cd->MaCham,
                    $cd->detai->TenDeTai ?? '',
                    $cd->sinhvien->TenSV ?? '',
                    $cd->giangvien->TenGV ?? '',
                    $cd->VaiTro ?? '',
                    $cd->Diem ?? '',
                    $cd->DiemCuoi ?? '',
                    $cd->NhanXet ?? '',
                    $cd->NgayCham ?? '',
                    $cd->TrangThai ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function exportExcelByClass(Request $request)
    {
        $request->validate([
            'lop' => 'required|exists:Lop,MaLop'
        ]);
        
        //$maCB = auth()->user()->MaSo;
        $maCB = Auth::user()->MaSo;
        $maLop = $request->lop;
        
        // Get class info
        $lop = Lop::find($maLop);
        
        // Get scores for students in this class
        $chamdiems = ChamDiem::whereHas('sinhvien', function($q) use ($maLop) {
            $q->where('MaLop', $maLop);
        })
        ->with(['detai', 'sinhvien.lop', 'giangvien'])
        ->get();
        
        // Simple CSV export
        $filename = 'diem_lop_' . ($lop->TenLop ?? $maLop) . '_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($chamdiems, $lop) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Header with class info
            fputcsv($file, ['Bảng điểm lớp: ' . ($lop->TenLop ?? '')]);
            fputcsv($file, ['Ngày xuất: ' . date('d/m/Y H:i:s')]);
            fputcsv($file, []); // Empty row
            
            fputcsv($file, ['Mã chấm', 'Đề tài', 'Sinh viên', 'Mã SV', 'Lớp', 'Giảng viên', 'Vai trò', 'Điểm', 'Điểm cuối', 'Nhận xét', 'Ngày chấm', 'Trạng thái']);
            
            foreach ($chamdiems as $cd) {
                fputcsv($file, [
                    $cd->MaCham,
                    $cd->detai->TenDeTai ?? '',
                    $cd->sinhvien->TenSV ?? '',
                    $cd->sinhvien->MaSV ?? '',
                    $cd->sinhvien->lop->TenLop ?? '',
                    $cd->giangvien->TenGV ?? '',
                    $cd->VaiTro ?? '',
                    $cd->Diem ?? '',
                    $cd->DiemCuoi ?? '',
                    $cd->NhanXet ?? '',
                    $cd->NgayCham ?? '',
                    $cd->TrangThai ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function getStatistics($maCB)
    {
        // Get DISTINCT student-project combinations with final scores
        $finalScores = ChamDiem::whereHas('detai', function($q) use ($maCB) {
            // $q->where('MaCB', $maCB); // Temporarily disabled for testing
        })
        ->whereNotNull('DiemCuoi')
        ->where('TrangThai', 'Đã duyệt')
        ->select('MaDeTai', 'MaSV', 'DiemCuoi')
        ->distinct()
        ->get();
        
        $total = $finalScores->count();
        
        if ($total === 0) {
            return [
                'average' => '0.00',
                'distribution' => ['under5' => 0, 'from5to9' => 0, 'above9' => 0],
                'by_project' => collect([])
            ];
        }
        
        // Calculate average from final scores
        $average = $finalScores->avg('DiemCuoi');
        
        // Calculate distribution from final scores
        $distribution = [
            'under5' => $finalScores->where('DiemCuoi', '<', 5)->count(),
            'from5to9' => $finalScores->whereBetween('DiemCuoi', [5, 8.9])->count(),
            'above9' => $finalScores->where('DiemCuoi', '>=', 9)->count(),
        ];
        
        // Average by project (using final scores)
        $byProject = ChamDiem::whereHas('detai', function($q) use ($maCB) {
            // $q->where('MaCB', $maCB); // Temporarily disabled for testing
        })
        ->whereNotNull('DiemCuoi')
        ->where('TrangThai', 'Đã duyệt')
        ->with('detai')
        ->select('MaDeTai', 'MaSV', 'DiemCuoi')
        ->distinct()
        ->get()
        ->groupBy('MaDeTai')
        ->map(function($group) {
            return [
                'name' => $group->first()->detai->TenDeTai ?? 'N/A',
                'average' => round($group->avg('DiemCuoi'), 2),
                'student_count' => $group->count()
            ];
        })
        ->sortByDesc('average')
        ->values()
        ->take(10);
        
        return [
            'average' => number_format($average, 2),
            'distribution' => $distribution,
            'by_project' => $byProject
        ];
    }
}