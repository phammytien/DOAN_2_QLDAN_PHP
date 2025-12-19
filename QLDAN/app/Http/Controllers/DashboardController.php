<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BaoCao;
use App\Models\NamHoc;
use App\Models\Khoa;
use App\Models\GiangVien;
use App\Models\Lop;
use App\Models\Nganh;
use App\Models\CanBoQL;
use App\Models\DeTai;
use App\Models\SinhVien;
use App\Models\DeTai_SinhVien;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
        }

        switch ($user->VaiTro) {
            case 'Admin':
                return $this->adminDashboard($request);
            case 'GiangVien':
                return $this->giangvienDashboard();
            case 'CanBo':
                return $this->canboDashboard();
            case 'SinhVien':
                return $this->sinhvienDashboard();
            default:
                abort(403, 'Vai trò không hợp lệ.');
        }
    }

    // ====================== ADMIN DASHBOARD ======================
    public function adminDashboard(Request $request)
    {
        // 1. Lấy dữ liệu cho bộ lọc
        $namhocs = NamHoc::orderBy('TenNamHoc', 'desc')->get();
        $khoas = Khoa::all();
        $gvs = GiangVien::all();
        $lops = Lop::all();
        $nganhs = Nganh::all();
        $cbs = CanBoQL::all();
        $linhvucs = DeTai::select('LinhVuc')->distinct()->pluck('LinhVuc');

        // 2. Xử lý bộ lọc
        $filterNamHoc = $request->get('namhoc');
        $filterKhoa = $request->get('khoa');
        $filterGV = $request->get('giangvien');

        // Base query for filtering DeTai
        $baseDeTaiQuery = DeTai::query();
        if ($filterNamHoc) {
            $baseDeTaiQuery->where('MaNamHoc', $filterNamHoc);
        }
        // For Khoa and GiangVien, we need to join if filtering DeTai
        if ($filterKhoa || $filterGV) {
            $baseDeTaiQuery->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV');
            if ($filterKhoa) {
                $baseDeTaiQuery->where('GiangVien.MaKhoa', $filterKhoa);
            }
            if ($filterGV) {
                $baseDeTaiQuery->where('DeTai.MaGV', $filterGV);
            }
        }
        // Select DeTai.* to avoid column ambiguity after join
        $baseDeTaiQuery->select('DeTai.*');


        // Đếm đề tài chờ duyệt (mới)
        $pendingProjects = DeTai::where('TrangThai', 'Chưa duyệt')->count();
        
        // Đếm điểm chờ duyệt (mới)
        $pendingScores = BaoCao::where('TrangThai', 'Chờ duyệt điểm')->count();

        // 3. Thống kê tổng quan
        // Filter SinhVien
        $querySV = SinhVien::query();
        if ($filterNamHoc) $querySV->where('MaNamHoc', $filterNamHoc);
        if ($filterKhoa) $querySV->where('MaKhoa', $filterKhoa);
        $tongSV = $querySV->count();

        // Filter GiangVien
        $queryGV = GiangVien::query();
        if ($filterKhoa) $queryGV->where('MaKhoa', $filterKhoa);
        $tongGV = $queryGV->count();

        // Filter DeTai
        $tongDT = $baseDeTaiQuery->count();

        $tongCB = CanBoQL::count();

        // 4. Đề tài theo khoa
        $khoaDataQuery = DeTai::query()
            ->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV')
            ->join('Khoa', 'GiangVien.MaKhoa', '=', 'Khoa.MaKhoa');
        
        if ($filterNamHoc) $khoaDataQuery->where('DeTai.MaNamHoc', $filterNamHoc);
        if ($filterKhoa) $khoaDataQuery->where('Khoa.MaKhoa', $filterKhoa);
        if ($filterGV) $khoaDataQuery->where('DeTai.MaGV', $filterGV);

        $khoaData = $khoaDataQuery
            ->select('Khoa.TenKhoa', DB::raw('COUNT(DeTai.MaDeTai) as SoLuong'))
            ->groupBy('Khoa.MaKhoa', 'Khoa.TenKhoa')
            ->get();

        // 5. Kết quả bảo vệ
        // Helper function to apply filters to DeTai related queries
        $applyDeTaiFilters = function($query) use ($filterNamHoc, $filterKhoa, $filterGV) {
            $query->join('DeTai', 'ChamDiem.MaDeTai', '=', 'DeTai.MaDeTai') // Assuming ChamDiem has MaDeTai or linked via SV?
                  ->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV');
            
            if ($filterNamHoc) $query->where('DeTai.MaNamHoc', $filterNamHoc);
            if ($filterKhoa) $query->where('GiangVien.MaKhoa', $filterKhoa);
            if ($filterGV) $query->where('DeTai.MaGV', $filterGV);
        };
        
        // Note: ChamDiem usually links to DeTai or SV. Let's check ChamDiem structure.
        // Assuming ChamDiem has MaSV, and we link to DeTai via DeTai_SinhVien or if ChamDiem has MaDeTai.
        // Let's use a safer approach by joining DeTai_SinhVien if needed, but let's assume ChamDiem -> MaSV -> DeTai_SinhVien -> DeTai for now or check schema.
        // Actually, let's look at the original query: DB::table('ChamDiem')...
        // It doesn't join anything. To filter, we MUST join.
        
        // Re-writing queries for Dat/KhongDat/Cho with joins
        
        // ĐẠT
        $dat = DB::table('ChamDiem')
            ->join('DeTai', 'ChamDiem.MaDeTai', '=', 'DeTai.MaDeTai')
            ->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV')
            ->when($filterNamHoc, fn($q) => $q->where('DeTai.MaNamHoc', $filterNamHoc))
            ->when($filterKhoa, fn($q) => $q->where('GiangVien.MaKhoa', $filterKhoa))
            ->when($filterGV, fn($q) => $q->where('DeTai.MaGV', $filterGV))
            ->where('DiemCuoi', '>=', 5)
            ->distinct()
            ->count('ChamDiem.MaSV');

        // KHÔNG ĐẠT
        $khongdat = DB::table('ChamDiem')
            ->join('DeTai', 'ChamDiem.MaDeTai', '=', 'DeTai.MaDeTai')
            ->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV')
            ->when($filterNamHoc, fn($q) => $q->where('DeTai.MaNamHoc', $filterNamHoc))
            ->when($filterKhoa, fn($q) => $q->where('GiangVien.MaKhoa', $filterKhoa))
            ->when($filterGV, fn($q) => $q->where('DeTai.MaGV', $filterGV))
            ->where('DiemCuoi', '<', 5)
            ->whereNotNull('DiemCuoi')
            ->distinct()
            ->count('ChamDiem.MaSV');

        // CHỜ (Sinh viên có đề tài nhưng chưa có điểm)
        $cho = DB::table('DeTai_SinhVien')
            ->join('DeTai', 'DeTai_SinhVien.MaDeTai', '=', 'DeTai.MaDeTai')
            ->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV')
            ->when($filterNamHoc, fn($q) => $q->where('DeTai.MaNamHoc', $filterNamHoc))
            ->when($filterKhoa, fn($q) => $q->where('GiangVien.MaKhoa', $filterKhoa))
            ->when($filterGV, fn($q) => $q->where('DeTai.MaGV', $filterGV))
            ->whereNotIn('DeTai_SinhVien.MaSV', function($subQuery) {
                $subQuery->select('MaSV')
                    ->from('ChamDiem')
                    ->whereNotNull('DiemCuoi');
            })
            ->distinct()
            ->count('DeTai_SinhVien.MaSV');

        // 6. Real-time stats (Keep as is or apply filters? Usually real-time is global, but let's keep it global for now or apply filters if requested. User said "lọc chính xác", implying the main stats.)
        // Let's leave real-time stats global for now as they are "Today's activity".
        
        $reportsToday = DB::table('BaoCao')
            ->whereDate('NgayNop', today())
            ->count();

        $reportsNotGraded = DB::table('BaoCao')
            ->where('TrangThai', 'Chờ duyệt')
            ->count();

        $studentsNoTopic = DB::table('SinhVien')
            ->whereNotIn('MaSV', function($query) {
                $query->select('MaSV')
                    ->from('DeTai_SinhVien');
            });
            
        // Apply filters to studentsNoTopic?
        if ($filterNamHoc) $studentsNoTopic->where('MaNamHoc', $filterNamHoc);
        if ($filterKhoa) $studentsNoTopic->where('MaKhoa', $filterKhoa);
        
        $studentsNoTopic = $studentsNoTopic->count();

        $totalMilestones = DB::table('TienDo')->count();
        $completedMilestones = DB::table('TienDo')->whereNotNull('NgayNop')->count();
        $avgProgress = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100) : 0;

        // Reports by Year (Năm học)
        $reportsByYearQuery = DB::table('BaoCao')
            ->join('DeTai', 'BaoCao.MaDeTai', '=', 'DeTai.MaDeTai')
            ->join('NamHoc', 'DeTai.MaNamHoc', '=', 'NamHoc.MaNamHoc')
            ->select('NamHoc.TenNamHoc', DB::raw('COUNT(DISTINCT BaoCao.MaSV) as SoLuong'))
            ->groupBy('NamHoc.MaNamHoc', 'NamHoc.TenNamHoc');

        // Apply filters
        if ($filterKhoa) $reportsByYearQuery->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV')->where('GiangVien.MaKhoa', $filterKhoa);
        if ($filterGV) $reportsByYearQuery->where('DeTai.MaGV', $filterGV);

        $reportsByYear = $reportsByYearQuery->orderBy('NamHoc.TenNamHoc', 'desc')->limit(6)->get();

        // 7. Biểu đồ đường: Báo cáo nộp theo 7 ngày gần nhất
        $lineChartLabels = [];
        $lineChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $lineChartLabels[] = $date->format('d/m');
            $count = DB::table('BaoCao')
                ->whereDate('NgayNop', $date->format('Y-m-d'))
                ->count();
            $lineChartData[] = $count;
        }

        // 8. Biểu đồ cột: Đề tài theo trạng thái
        $columnChartLabels = ['Đang thực hiện', 'Hoàn thành', 'Tạm dừng', 'Hủy'];
        $columnChartData = [
            DB::table('DeTai')->where('TrangThai', 'Đang thực hiện')->count(),
            DB::table('DeTai')->where('TrangThai', 'LIKE', '%hoàn thành%')->count(),
            DB::table('DeTai')->where('TrangThai', 'Tạm dừng')->count(),
            DB::table('DeTai')->where('TrangThai', 'Hủy')->count(),
        ];

        // 9. Thống kê đề tài đã đăng ký / đã duyệt
        $approvedProjects = DB::table('DeTai')
            ->where('TrangThai', '!=', 'Chờ duyệt')
            ->where('TrangThai', '!=', 'Từ chối')
            ->count();
        
        $registeredProjects = DB::table('DeTai')
            ->whereIn('MaDeTai', function($query) {
                $query->select('MaDeTai')
                    ->from('DeTai_SinhVien')
                    ->distinct();
            })
            ->count();
        
        $registrationRate = $approvedProjects > 0 ? round(($registeredProjects / $approvedProjects) * 100) : 0;

        // 10. Admin info
    $adminInfo = (object)[
        'name' => Auth::user()->HoTen ?? 'Admin',
        'email' => Auth::user()->Email ?? 'admin@dthu.edu.vn'
    ];

    // 11. Lấy thông báo mới nhất cho Timeline
    $latestNotifications = \App\Models\ThongBao::with('canBo')
        ->orderBy('TGDang', 'desc')
        ->take(5)
        ->get();

    // 12. Đóng gói dữ liệu
    $data = [
        'tongSV' => $tongSV,
        'tongGV' => $tongGV,
        'tongDT' => $tongDT,
        'tongCB' => $tongCB,
        'tenKhoa' => $khoaData->pluck('TenKhoa'),
        'soLuongDT' => $khoaData->pluck('SoLuong'),
        'ketqua' => (object)[
            'Dat' => $dat,
            'KhongDat' => $khongdat,
            'Cho' => $cho
        ],
        'reportsToday' => $reportsToday,
        'reportsNotGraded' => $reportsNotGraded,
        'studentsNoTopic' => $studentsNoTopic,
        'avgProgress' => $avgProgress,
        'reportsByYear' => $reportsByYear,
        // Biểu đồ đường
        'lineChartLabels' => $lineChartLabels,
        'lineChartData' => $lineChartData,
        // Biểu đồ cột
        'columnChartLabels' => $columnChartLabels,
        'columnChartData' => $columnChartData,
        // Thống kê đăng ký
        'approvedProjects' => $approvedProjects,
        'registeredProjects' => $registeredProjects,
        'registrationRate' => $registrationRate,
        // Timeline thông báo
        'latestNotifications' => $latestNotifications,
        // Thông báo chờ duyệt (mới)
        'pendingProjects' => $pendingProjects,
        'pendingScores' => $pendingScores,
    ];

        return view('admin.dashboard', compact(
            'data', 'adminInfo', 'khoas', 'nganhs', 'namhocs', 'gvs', 'cbs', 'lops', 'linhvucs',
            'filterNamHoc', 'filterKhoa', 'filterGV'
        ));
    }

    // ====================== GIẢNG VIÊN ======================
    public function giangvienDashboard()
    {
        $maGV = Auth::user()->MaSo;
        $giangvien = \App\Models\GiangVien::with(['khoa', 'nganh'])->where('MaGV', $maGV)->first();

        // 1. Thống kê
        $detai = DB::table('DeTai')
            ->where('MaGV', $maGV)
            ->select('MaDeTai', 'TenDeTai', 'TrangThai', 'NamHoc', 'DeadlineBaoCao')
            ->get();
            
        $soLuongDeTai = $detai->count();
        $soLuongSinhVien = DB::table('DeTai_SinhVien')
            ->join('DeTai', 'DeTai_SinhVien.MaDeTai', '=', 'DeTai.MaDeTai')
            ->where('DeTai.MaGV', $maGV)
            ->count();
            
        $daChamDiem = DB::table('ChamDiem')
            ->where('MaGV', $maGV)
            ->count();

        // 2. Thông báo mới
        $thongbaos = \App\Models\ThongBao::orderBy('TGDang', 'desc')->take(3)->get();

        // 3. Lịch trình / Deadline sắp tới (Mockup logic based on deadlines)
        $deadlines = $detai->whereNotNull('DeadlineBaoCao')
                           ->sortBy('DeadlineBaoCao')
                           ->take(5);

        // 4. Hoạt động gần đây (Báo cáo sinh viên đã nộp)
        $recentReports = \App\Models\BaoCao::with(['sinhVien', 'deTai'])
            ->whereHas('deTai', function($query) use ($maGV) {
                $query->where('MaGV', $maGV);
            })
            ->orderBy('NgayNop', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                $message = "SV {$item->sinhVien->TenSV} ";
                
                if ($item->TrangThai === 'Xin nộp bổ sung') {
                    $message .= "đã gửi yêu cầu xin nộp bổ sung cho đề tài \"{$item->deTai->TenDeTai}\".";
                    $icon = 'fas fa-exclamation-circle';
                    $color = 'text-warning';
                } elseif ($item->TrangThai === 'Đã duyệt') {
                    $message .= "đã nộp báo cáo cho đề tài \"{$item->deTai->TenDeTai}\".";
                    $icon = 'fas fa-file-alt';
                    $color = 'text-success';
                } else {
                    $message .= "đã nộp báo cáo cho đề tài \"{$item->deTai->TenDeTai}\".";
                    $icon = 'fas fa-file-alt';
                    $color = 'text-primary';
                }
                
                return [
                    'type' => 'report',
                    'content' => $message,
                    'time' => $item->NgayNop,
                    'icon' => $icon,
                    'color' => $color,
                    'baocao' => $item
                ];
            });

        $activities = $recentReports->values();

        return view('giangvien.dashboard', compact('giangvien', 'detai', 'soLuongDeTai', 'soLuongSinhVien', 'daChamDiem', 'thongbaos', 'deadlines', 'activities'));
    }

    // ====================== CÁN BỘ ======================
    public function canboDashboard()
    {
        // Lấy Khoa của Cán bộ
        $maKhoa = Auth::user()->canBoQL->MaKhoa;
        
        // 1. Thống kê số lượng (CHỈ TRONG KHOA)
        $tongDeTai = \App\Models\DeTai::whereHas('giangVien', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->count();
        
        // Count completed projects - handle status variations (CHỈ TRONG KHOA)
        $daHoanThanh = \App\Models\DeTai::whereHas('giangVien', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->where(function($q) {
            $q->where('TrangThai', 'LIKE', '%hoàn thành%')
              ->orWhere('TrangThai', 'LIKE', '%Hoàn thành%');
        })->count();
        
        // Chờ duyệt: bao gồm cả "Chờ duyệt" và "Mới" (CHỈ TRONG KHOA)
        $choDuyet = \App\Models\DeTai::whereHas('giangVien', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->whereIn('TrangThai', ['Chờ duyệt', 'Mới'])->count();
        
        // Sinh viên chưa nộp: Tất cả SV đã đăng ký đề tài nhưng chưa có báo cáo (CHỈ TRONG KHOA)
        $sinhVienChuaNop = DB::table('DeTai_SinhVien')
            ->join('DeTai', 'DeTai.MaDeTai', '=', 'DeTai_SinhVien.MaDeTai')
            ->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV')
            ->where('GiangVien.MaKhoa', $maKhoa)
            ->leftJoin('BaoCao', function($join){
                $join->on('BaoCao.MaDeTai', '=', 'DeTai_SinhVien.MaDeTai')
                     ->on('BaoCao.MaSV', '=', 'DeTai_SinhVien.MaSV');
            })
            ->whereNull('BaoCao.MaBC')
            ->count();

        // 2. Dữ liệu biểu đồ (Trạng thái đề tài) (CHỈ TRONG KHOA)
        $allTopics = \App\Models\DeTai::whereHas('giangVien', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->get();
        
        $chartData = [
            'Moi' => $allTopics->where('TrangThai', 'Mới')->count() + 
                     $allTopics->where('TrangThai', 'Chờ duyệt')->count(),
            'DangLam' => $allTopics->where('TrangThai', 'Đang thực hiện')->count() +
                         $allTopics->where('TrangThai', 'Đang làm')->count(),
            'ChoDuyet' => 0, // Đã tính vào Mới
            'HoanThanh' => $allTopics->filter(function($dt) {
                return stripos($dt->TrangThai, 'hoàn thành') !== false;
            })->count(),
            'TreHan' => $allTopics->where('TrangThai', 'Đang thực hiện')
                        ->filter(function($dt) {
                            return $dt->DeadlineBaoCao && \Carbon\Carbon::parse($dt->DeadlineBaoCao)->isPast();
                        })->count()
        ];

        // 3. Hoạt động gần đây (Lấy từ Báo cáo & Thông báo) (CHỈ TRONG KHOA)
        $recentReports = \App\Models\BaoCao::with(['sinhVien', 'deTai'])
            ->whereHas('deTai.giangVien', function($q) use ($maKhoa) {
                $q->where('MaKhoa', $maKhoa);
            })
            ->orderBy('NgayNop', 'desc')
            ->take(3)
            ->get()
            ->map(function($item) {
                $message = "SV {$item->sinhVien->TenSV} ";
                
                if ($item->TrangThai === 'Xin nộp bổ sung') {
                    $message .= "đã gửi yêu cầu xin nộp bổ sung cho đề tài \"{$item->deTai->TenDeTai}\".";
                } else {
                    $message .= "đã nộp báo cáo cho đề tài \"{$item->deTai->TenDeTai}\".";
                }
                
                return [
                    'type' => 'report',
                    'content' => $message,
                    'time' => $item->NgayNop,
                    'icon' => 'fas fa-file-alt',
                    'color' => 'text-primary'
                ];
            });

        $recentNotices = \App\Models\ThongBao::orderBy('TGDang', 'desc')
            ->take(2)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'notice',
                    'content' => "Thông báo mới: \"{$item->NoiDung}\"",
                    'time' => $item->TGDang,
                    'icon' => 'fas fa-bell',
                    'color' => 'text-warning'
                ];
            });
        
        $activities = $recentReports->merge($recentNotices)->sortByDesc('time')->values();

        // 4. Đề tài cần chú ý (Chờ duyệt hoặc Trễ hạn) (CHỈ TRONG KHOA)
        $attentionTopics = \App\Models\DeTai::with(['sinhViens', 'giangVien'])
            ->whereHas('giangVien', function($q) use ($maKhoa) {
                $q->where('MaKhoa', $maKhoa);
            })
            ->where(function($q) {
                $q->where('TrangThai', 'Chờ duyệt')
                  ->orWhere(function($q2) {
                      $q2->where('TrangThai', 'Đang thực hiện')
                         ->whereDate('DeadlineBaoCao', '<', now());
                  });
            })
            ->orderBy('MaDeTai', 'desc')
            ->take(5)
            ->get();

        // 5. Danh sách sinh viên đã đăng ký đề tài (CHỈ TRONG KHOA)
        $registeredStudents = DB::table('DeTai_SinhVien')
            ->join('SinhVien', 'SinhVien.MaSV', '=', 'DeTai_SinhVien.MaSV')
            ->join('Lop', 'SinhVien.MaLop', '=', 'Lop.MaLop')
            ->join('DeTai', 'DeTai.MaDeTai', '=', 'DeTai_SinhVien.MaDeTai')
            ->join('GiangVien', 'DeTai.MaGV', '=', 'GiangVien.MaGV')
            ->where('GiangVien.MaKhoa', $maKhoa)
            ->select('SinhVien.MaSV', 'SinhVien.TenSV', 'Lop.TenLop', 'DeTai.TenDeTai', 'DeTai.TrangThai')
            ->orderBy('DeTai.MaDeTai', 'desc')
            ->take(10)
            ->get();

        // 6. Dữ liệu biểu đồ hỗn hợp (Đề tài theo Ngành thuộc Khoa của Cán bộ)
        $mixedChartData = DB::table('GiangVien')
            ->where('GiangVien.MaKhoa', $maKhoa)
            ->join('Nganh', 'GiangVien.MaNganh', '=', 'Nganh.MaNganh')
            ->leftJoin('DeTai', 'GiangVien.MaGV', '=', 'DeTai.MaGV')
            ->select(
                'Nganh.TenNganh',
                DB::raw('COUNT(DeTai.MaDeTai) as Total'),
                DB::raw('SUM(CASE WHEN DeTai.TrangThai LIKE "%hoàn thành%" THEN 1 ELSE 0 END) as Completed')
            )
            ->groupBy('Nganh.MaNganh', 'Nganh.TenNganh')
            ->get();

        $data = compact('tongDeTai', 'daHoanThanh', 'choDuyet', 'sinhVienChuaNop', 'chartData', 'activities', 'attentionTopics', 'registeredStudents', 'mixedChartData');

        return view('canbo.dashboard', compact('data'));
    }

    // ====================== SINH VIÊN ======================
    public function sinhvienDashboard()
    {
        $maSV = Auth::user()->MaSo;

        // 1. Thông tin sinh viên đầy đủ
        $sinhvien = \App\Models\SinhVien::with(['khoa', 'nganh', 'lop', 'namhoc'])
            ->where('MaSV', $maSV)
            ->first();

        // 2. Đề tài & Tiến độ
        $detai = \App\Models\DeTai::with('giangVien')
            ->join('DeTai_SinhVien', 'DeTai.MaDeTai', '=', 'DeTai_SinhVien.MaDeTai')
            ->where('DeTai_SinhVien.MaSV', $maSV)
            ->select('DeTai.*')
            ->first();

        $progressPercent = 0;
        $completedTasks = 0;
        $totalTasks = 2; // 2 nhiệm vụ: Tiến độ + Báo cáo cuối

        if ($detai) {
            // Nhiệm vụ 1: Kiểm tra đã nộp tiến độ chưa
            $hasProgress = \App\Models\TienDo::where('MaDeTai', $detai->MaDeTai)
                ->whereNotNull('NgayNop')
                ->exists();
            
            // Nhiệm vụ 2: Kiểm tra đã nộp báo cáo cuối chưa
            $hasFinalReport = \App\Models\BaoCao::where('MaDeTai', $detai->MaDeTai)
                ->where('MaSV', $maSV)
                ->exists();
            
            // Tính số nhiệm vụ hoàn thành
            if ($hasProgress) $completedTasks++;
            if ($hasFinalReport) $completedTasks++;
            
            $progressPercent = round(($completedTasks / $totalTasks) * 100);
        }

        // 3. Điểm số
        $diem = \App\Models\ChamDiem::where('MaSV', $maSV)
            ->where('MaDeTai', $detai->MaDeTai ?? 0)
            ->first();

        // 4. Nhắc nhở (Điểm mới chưa xem)
        $reminders = 0;
        $unreadGrades = collect();
        if ($detai) {
            $unreadGrades = \App\Models\ChamDiem::where('MaSV', $maSV)
                ->where('MaDeTai', $detai->MaDeTai)
                ->where('DaXem', false)
                ->whereNotNull('Diem')
                ->with('giangVien')
                ->get();
            $reminders = $unreadGrades->count();
        }

        // 5. Hạn nộp sắp tới (7 ngày tới)
        $upcomingDeadlines = 0;
        $upcomingTiendos = collect();
        if ($detai) {
            $upcomingTiendos = \App\Models\TienDo::where('MaDeTai', $detai->MaDeTai)
                ->where('HanNop', '>=', now())
                ->where('HanNop', '<=', now()->addDays(7))
                ->whereNull('NgayNop')
                ->orderBy('HanNop', 'asc')
                ->get();
            $upcomingDeadlines = $upcomingTiendos->count();
        }

        return view('sinhvien.dashboard', compact('sinhvien', 'detai', 'progressPercent', 'completedTasks', 'totalTasks', 'diem', 'reminders', 'upcomingDeadlines', 'unreadGrades', 'upcomingTiendos'));
    }
}