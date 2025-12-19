<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamDiem;
use App\Models\DeTai;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChamDiemController extends Controller
{
    // ================================
    // DANH SÁCH
    // ================================
    public function index(Request $request)
    {
        $lops = \App\Models\Lop::all(); 
        $selectedLop = $request->get('lop_id', 'all');

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

        if ($selectedLop !== 'all') { 
            $query->where('SinhVien.MaLop', $selectedLop); 
        }

        // Pagination - 5 rows per page
        $results = $query->orderBy('DeTai.MaDeTai', 'desc')
                        ->orderBy('SinhVien.MaSV', 'asc')
                        ->paginate(5)
                        ->withQueryString();
        
        // Load related data for each result
        $detaiIds = $results->pluck('MaDeTai')->unique(); 
        $detais = DeTai::with([
            'sinhViens.lop',
            'phancongs.giangVien',
            'chamdiems.giangVien'
        ])->whereIn('MaDeTai', $detaiIds)->get()->keyBy('MaDeTai'); 

        // Statistics
        $stats = $this->getStatistics(); // Hàm lấy thống kê    
        return view('admin.chamdiem.index', [  
            'results' => $results,
            'cds' => $detais,
            'lops' => $lops,
            'selectedLop' => $selectedLop,
            'stats' => $stats
        ]); 
    }
    
    private function getStatistics()  
    {
        // Get DISTINCT student-project combinations with final scores
        $finalScores = ChamDiem::whereNotNull('DiemCuoi') 
            ->where('TrangThai', 'Đã duyệt')
            ->whereNull('MaTienDo') // Chỉ lấy điểm báo cáo cuối
            ->select('MaDeTai', 'MaSV', 'DiemCuoi')
            ->distinct()
            ->get();
        
        $total = $finalScores->count(); 
        $approved = ChamDiem::where('TrangThai', 'Đã duyệt')->whereNull('MaTienDo')->distinct()->count('MaSV'); 
        $pending = ChamDiem::where('TrangThai', 'Chờ duyệt')->whereNull('MaTienDo')->distinct()->count('MaSV');
        
        if ($total === 0) {
            return [
                'total' => 0, 
                'approved' => $approved, 
                'pending' => $pending,
                'average' => '0.00', 
                'distribution' => ['under5' => 0, 'from5to9' => 0, 'above9' => 0], // Phân phối điểm
                'by_project' => collect([]) // Trung bình theo đề tài
            ];
        }
        
        // Calculate average from final scores
        $average = $finalScores->avg('DiemCuoi'); // Tính điểm trung bình từ DiemCuoi
        
      // Phân phối điểm
        $distribution = [
            'under5' => $finalScores->where('DiemCuoi', '<', 5)->count(),
            'from5to9' => $finalScores->whereBetween('DiemCuoi', [5, 8.9])->count(),
            'above9' => $finalScores->where('DiemCuoi', '>=', 9)->count(),
        ];
        
        // Trung bình điểm theo đề tài
        $byProject = ChamDiem::whereNotNull('DiemCuoi') // Chỉ lấy các bản ghi có DiemCuoi
            ->where('TrangThai', 'Đã duyệt')
            ->whereNull('MaTienDo')
            ->with('detai') 
            ->select('MaDeTai', 'MaSV', 'DiemCuoi') 
            ->distinct()
            ->get()
            ->groupBy('MaDeTai') // Nhóm theo đề tài
            ->map(function($group) {
                return [
                    'name' => $group->first()->detai->TenDeTai ?? 'N/A', 
                    'average' => round($group->avg('DiemCuoi'), 2), // Tính trung bình DiemCuoi
                    'student_count' => $group->count() // Số sinh viên
                ];
            })
            ->sortByDesc('average') // Sắp xếp giảm dần theo điểm trung bình
            ->values()
            ->take(10);
        
        return [
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'average' => number_format($average, 2),
            'distribution' => $distribution,
            'by_project' => $byProject
        ];
    }

    // ================================
    // CREATE
    // ================================
    public function create() 
    {
        $detais = DeTai::all(); 
        $gvs    = GiangVien::all(); 

        return view('admin.chamdiem.create', compact('detais','gvs'));
    }

    // ================================
    // STORE
    // ================================
    public function store(Request $request) 
    {
        $request->validate([
            'MaDeTai' => 'required|integer|exists:DeTai,MaDeTai',
            'MaGV'    => 'required|integer|exists:GiangVien,MaGV',
            'MaSV'    => 'required|integer',
            'Diem'    => 'required|numeric|min:0|max:10',
        ]);

        // Xác định vai trò GV
        $vaiTroDB = DB::table('PhanCong')
                        ->where('MaDeTai', $request->MaDeTai)
                        ->where('MaGV', $request->MaGV)
                        ->value('VaiTro');

        $vaiTro = $vaiTroDB === 'Hướng dẫn chính' ? 'GVHD' : 'GVPB';

        ChamDiem::create([ 
            'MaDeTai' => $request->MaDeTai,
            'MaGV'    => $request->MaGV,
            'MaSV'    => $request->MaSV,
            'Diem'    => $request->Diem,
            'NhanXet' => $request->NhanXet,
            'NgayCham'=> now(),
            'VaiTro'  => $vaiTro,
            'TrangThai' => 'Chờ duyệt',
            'DiemCuoi' => null,
        ]);

        return redirect()->route('admin.chamdiem.index')
            ->with('success','✅ Thêm chấm điểm thành công!');
    }

    // ================================
    // LẤY GVPB + GVHD THEO ĐỀ TÀI + SV
    // ================================
    private function getGVHD($MaDeTai, $MaSV) 
    {
        return ChamDiem::where('MaDeTai', $MaDeTai)
                        ->where('MaSV', $MaSV)
                        ->where('VaiTro', 'GVHD')
                        ->first();
    }

    private function getGVPB($MaDeTai, $MaSV)
    {
        return ChamDiem::where('MaDeTai', $MaDeTai)
                        ->where('MaSV', $MaSV)
                        ->where('VaiTro', 'GVPB')
                        ->first();
    }

    // ================================
    // EDIT
    // ================================
    public function edit($id)
    {
        $cd = ChamDiem::with(['detai','sinhvien','giangVien'])->findOrFail($id); // Lấy bản ghi chấm điểm

        $gvhd = $this->getGVHD($cd->MaDeTai, $cd->MaSV); 
        $gvpb = $this->getGVPB($cd->MaDeTai, $cd->MaSV);

        $detais = DeTai::all();

        return view('admin.chamdiem.edit', compact('cd','gvhd','gvpb','detais'));
    }

    // ================================
    // UPDATE
    // ================================
    public function update(Request $request, $id) 
    {
        $cd = ChamDiem::findOrFail($id); 
        $MaDeTai = $cd->MaDeTai;
        $MaSV    = $cd->MaSV;

        $gvhd = $this->getGVHD($MaDeTai, $MaSV); 
        $gvpb = $this->getGVPB($MaDeTai, $MaSV);

        // Cập nhật GVHD
        if($gvhd){ //
            $gvhd->Diem = $request->DiemGVHD ?? $gvhd->Diem; 
            $gvhd->NhanXet = $request->NhanXetGVHD ?? $gvhd->NhanXet; 
            $gvhd->save();
        }

        // Cập nhật GVPB
        if($gvpb){
            $gvpb->Diem = $request->DiemGVPB ?? $gvpb->Diem;
            $gvpb->NhanXet = $request->NhanXetGVPB ?? $gvpb->NhanXet;
            $gvpb->save();
        }

        // Tính điểm TB theo công thức (40% tiến độ + 60% báo cáo cuối)
        $diemTB = $this->calculateFinalGrade($MaDeTai, $MaSV); 

        // Nếu admin duyệt thì lưu DiemCuoi cho ALL record
        if($request->TrangThai === 'Đã duyệt'){
            ChamDiem::where('MaDeTai', $MaDeTai)
                    ->where('MaSV', $MaSV)
                    ->update([
                        'DiemCuoi' => $diemTB,
                        'TrangThai' => 'Đã duyệt'
                    ]);
            
            // Cập nhật trạng thái đề tài thành "Đã hoàn thành"
            $deTai = DeTai::find($MaDeTai);
            if ($deTai) {
                $deTai->TrangThai = 'Đã hoàn thành';
                $deTai->save();
            }
        } else {
            // Update ALL records with new status
            $newStatus = $request->TrangThai ?? $cd->TrangThai ?? 'Chưa xác nhận'; 
            ChamDiem::where('MaDeTai', $MaDeTai)
                    ->where('MaSV', $MaSV)
                    ->update([
                        'DiemCuoi' => null,
                        'TrangThai' => $newStatus
                    ]);
        }

        return redirect()->route('admin.chamdiem.index')
            ->with('success','✅ Cập nhật chấm điểm thành công!');
    }

    // ================================
    // DUYỆT
    // ================================
    public function approve($id) 
    {
        $cd = ChamDiem::findOrFail($id); // Lấy bản ghi chấm điểm
        $MaDeTai = $cd->MaDeTai; 
        $MaSV    = $cd->MaSV;

        // Tính điểm TB theo công thức mới
        $diemTB = $this->calculateFinalGrade($MaDeTai, $MaSV);

        ChamDiem::where('MaDeTai', $MaDeTai) 
                ->where('MaSV', $MaSV) 
                ->update([
                    'DiemCuoi' => $diemTB,
                    'TrangThai' => 'Đã duyệt'
                ]);

        // Cập nhật trạng thái đề tài thành "Đã hoàn thành"
        $deTai = DeTai::find($MaDeTai);
        if ($deTai) {
            $deTai->TrangThai = 'Đã hoàn thành';
            $deTai->save();
        }

        return back()->with('success','✔ Điểm đã được duyệt!');
    }

    // ================================
    // UPDATE STATUS
    // ================================
    public function updateStatus(Request $request, $id) 
    {
        $cd = ChamDiem::findOrFail($id);

        $MaDeTai = $cd->MaDeTai;
        $MaSV    = $cd->MaSV;

        // Tính điểm TB theo công thức mới
        $diemTB = $this->calculateFinalGrade($MaDeTai, $MaSV);

        if ($request->TrangThai === 'Đã duyệt') {  
            // Update ALL records for this student-project
            ChamDiem::where('MaDeTai', $MaDeTai) 
                    ->where('MaSV', $MaSV)
                    ->update([ 
                        'DiemCuoi' => $diemTB, // Lưu điểm TB vào DiemCuoi
                        'TrangThai' => 'Đã duyệt'
                    ]);
            
            // Cập nhật trạng thái đề tài thành "Đã hoàn thành"
            $deTai = DeTai::find($MaDeTai);
            if ($deTai) { 
                $deTai->TrangThai = 'Đã hoàn thành'; 
                $deTai->save();
            }
        } else {
            // Update ALL records
            ChamDiem::where('MaDeTai', $MaDeTai) 
                    ->where('MaSV', $MaSV) 
                    ->update([
                        'DiemCuoi' => null,
                        'TrangThai' => $request->TrangThai
                    ]);
        }

        return back()->with('success', 'Cập nhật trạng thái thành công!');

    }

    // ================================
    // UPDATE ROLE
    // ================================
    public function updateRole(Request $request, $id)
    {
        $cd = ChamDiem::findOrFail($id);
        $cd->VaiTro = $request->VaiTro; 
        $cd->save();

        return back()->with('success', '✅ Cập nhật vai trò thành công!'); 
    }

    // ================================
    // SHOW
    // ================================
    public function show($id)
    {
        $cd = ChamDiem::with(['detai','sinhvien','giangVien'])->findOrFail($id); 

        $MaDeTai = $cd->MaDeTai;
        $MaSV    = $cd->MaSV;

        // LẤY FULL DANH SÁCH GIẢNG VIÊN CHẤM
        $listGV = ChamDiem::where('MaDeTai', $MaDeTai)
                    ->where('MaSV', $MaSV) 
                    ->with('giangVien')
                    ->get();

        // Get PhanCong to determine roles
        $phancongs = \App\Models\PhanCong::where('MaDeTai', $MaDeTai) 
                    ->with('giangVien') 
                    ->get(); // Lấy phân công giảng viên cho đề tài
        
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
        
        // Tách GVHD + GVPB based on PhanCong roles
        $gvhd = $listGV->first(function($cham) {
            return str_contains(strtolower($cham->VaiTroDisplay ?? ''), 'hướng dẫn'); 
        });
        
        $gvpb = $listGV->first(function($cham) {
            return str_contains(strtolower($cham->VaiTroDisplay ?? ''), 'phản biện');
        });

        // Điểm TB
        $diemTB = $listGV->avg('Diem');

        return view('admin.chamdiem.show', compact(
            'cd','listGV','gvhd','gvpb','diemTB'
        ));
    }


    // ================================
    // DELETE
    // ================================
    public function destroy($id)
    {
        ChamDiem::destroy($id);
        return redirect()->route('admin.chamdiem.index')
                         ->with('success','🗑️ Xóa chấm điểm thành công!');
    }

    // ================================
    // HELPER: TÍNH ĐIỂM TỔNG KẾT
    // ================================
    private function calculateFinalGrade($MaDeTai, $MaSV)
    {
        // 1. Lấy danh sách tiến độ của đề tài
        $tiendos = \App\Models\TienDo::where('MaDeTai', $MaDeTai)->get();

        // 2. Lấy danh sách chấm điểm của sinh viên
        $chamdiems = ChamDiem::where('MaDeTai', $MaDeTai)
                             ->where('MaSV', $MaSV)
                             ->get();
        
        // 3. Tính TB Tiến độ (40%)
        $totalProgress = 0;
        $countProgress = 0;
        foreach ($tiendos as $td) {
            $score = null;
            // Ưu tiên điểm cá nhân
            $personal = $chamdiems->firstWhere('MaTienDo', $td->MaTienDo);
            if ($personal) { 
                $score = $personal->Diem; 
            } 
            // Fallback điểm nhóm
            elseif ($td->Diem !== null) {
                $score = $td->Diem;
            }

            if ($score !== null) {
                $totalProgress += $score;
                $countProgress++;
            }
        }
        $avgProgress = $countProgress > 0 ? ($totalProgress / $countProgress) : 0; 

        // 4. Tính TB Báo cáo cuối (60%)
        // Chỉ lấy các điểm KHÔNG thuộc tiến độ (MaTienDo is null)
        $finalReports = $chamdiems->whereNull('MaTienDo'); 
        $avgFinal = $finalReports->count() > 0 ? $finalReports->avg('Diem') : 0;

        // 5. Tổng kết
        return ($avgProgress * 0.4) + ($avgFinal * 0.6);
    }
}