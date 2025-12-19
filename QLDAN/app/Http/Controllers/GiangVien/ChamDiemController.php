<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChamDiem;
use App\Models\DeTai;
use App\Models\BaoCao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChamDiemController extends Controller
{
    public function index()
    {
        $giangVien = Auth::user()->giangVien; 

        if (!$giangVien) { 
            return redirect()->route('login')->with('error', 'Không xác định được giảng viên!');
        }


        /*--------------------------------------------------------------
        | 1. Lấy tất cả đề tài kèm sinh viên –
        --------------------------------------------------------------*/
        $detai = DeTai::with(['sinhviens', 'tiendos']) // Load thêm tiendos để tính điểm TB
            ->whereHas('phancongs', function ($q) use ($giangVien) {
                $q->where('MaGV', $giangVien->MaGV);
            })
            ->get();

        /*--------------------------------------------------------------
        | 2. TẠO BẢN SAO cho danh sách CHƯA CHẤM ĐIỂM BÁO CÁO CUỐI
        |    => tránh làm mất dữ liệu gốc
        --------------------------------------------------------------*/
        $detaiChuaCham = $detai->map(function ($dt) use ($giangVien) {
            $clone = clone $dt;  // Quan trọng: tạo bản sao

            $clone->sinhviens = $dt->sinhviens->filter(function ($sv) use ($giangVien, $dt) {
                // Kiểm tra xem sinh viên đã được chấm ĐIỂM BÁO CÁO CUỐI chưa
                return !ChamDiem::where('MaDeTai', $dt->MaDeTai)
                    ->where('MaSV', $sv->MaSV)
                    ->where('MaGV', $giangVien->MaGV)
                    ->whereNull('MaTienDo') // Chỉ kiểm tra điểm báo cáo cuối
                    ->exists();
            });

            return $clone;
        })->filter(function ($dt) { // Lọc bỏ đề tài không còn sinh viên nào
            return $dt->sinhviens->count() > 0; // Lọc bỏ đề tài không còn sinh viên nào
        });

        /*--------------------------------------------------------------
        | 3. Lấy danh sách sinh viên đã chấm ĐIỂM BÁO CÁO CUỐI
        --------------------------------------------------------------*/
        $chamdiem = ChamDiem::with(['detai', 'sinhvien', 'giangvien'])
            ->where('MaGV', $giangVien->MaGV)
            ->whereNull('MaTienDo') // CHỈ LẤY ĐIỂM BÁO CÁO CUỐI
            ->orderByDesc('NgayCham')
            ->get();

        /*--------------------------------------------------------------
        | 4. Tối ưu lấy điểm TB & báo cáo mới nhất
        --------------------------------------------------------------*/
        $diemTrungBinh = [];
        $latestReports = []; 

        // Lấy tất cả MaDeTai cần xử lý (từ cả danh sách chưa chấm và đã chấm)
        $topicIds = $detai->pluck('MaDeTai')->merge($chamdiem->pluck('MaDeTai'))->unique();

        // Lấy toàn bộ báo cáo
        $allReports = BaoCao::with(['fileBaoCao', 'fileCode'])
            ->whereIn('MaDeTai', $topicIds)
            ->orderByDesc('NgayNop')
            ->get()
            ->groupBy(function ($item) {
                return $item->MaDeTai . '-' . $item->MaSV; 
            });

        // Lấy toàn bộ tiến độ
        $allTienDos = \App\Models\TienDo::with(['fileBaoCao', 'fileCode'])
            ->whereIn('MaDeTai', $topicIds)
            ->get()
            ->groupBy('MaDeTai');

        // Lấy tất cả điểm chi tiết (ChamDiem có MaTienDo)
        $individualScores = ChamDiem::whereIn('MaDeTai', $topicIds)
                            ->whereNotNull('MaTienDo')
                            ->get()
                            ->groupBy(function($item) {
                                return $item->MaTienDo . '-' . $item->MaSV;
                            });

        // Hàm tính toán chung (Closure)
        $calculateStats = function($maDeTai, $maSV) use ($allTienDos, $individualScores, &$diemTrungBinh, &$latestReports, $allReports) {
             
             // --- TÍNH ĐIỂM TRUNG BÌNH ---
             $tiendos = $allTienDos[$maDeTai] ?? collect(); // Lấy tiến độ của đề tài
             $totalScore = 0; 
             $count = 0; // Đếm số tiến độ có điểm
             
             $latestTienDo = $tiendos->whereNotNull('NgayNop')->sortByDesc('NgayNop')->first(); // Tiến độ nộp mới nhất

             foreach ($tiendos as $td) {
                 $score = null;
                 $key = $td->MaTienDo . '-' . $maSV;
                 
                 // Ưu tiên điểm cá nhân
                 if (isset($individualScores[$key])) {
                     $score = $individualScores[$key]->first()->Diem;
                 } 
                 // Fallback sang điểm nhóm
                 elseif ($td->Diem !== null) {
                     $score = $td->Diem;
                 }
                 
                 if ($score !== null) {
                     $totalScore += $score;
                     $count++;
                 }
             }
             
             $diemTrungBinh[$maDeTai][$maSV] = $count > 0 ? round($totalScore / $count, 2) : 0; // Điểm TB (2 chữ số)

             // --- TÌM BÁO CÁO MỚI NHẤT ---
             $keyReport = $maDeTai . '-' . $maSV;
             $latestBaoCao = $allReports[$keyReport][0] ?? null;

             if ($latestBaoCao && $latestTienDo) {
                 $latestReports[$maDeTai][$maSV] = 
                     ($latestBaoCao->NgayNop > $latestTienDo->NgayNop) ? $latestBaoCao : $latestTienDo;
             } elseif ($latestBaoCao) {
                 $latestReports[$maDeTai][$maSV] = $latestBaoCao;
             } else {
                 $latestReports[$maDeTai][$maSV] = $latestTienDo;
             }
        };

        // Áp dụng cho danh sách chưa chấm ($detai)
        foreach ($detai as $dt) {
            foreach ($dt->sinhviens as $sv) {
                $calculateStats($dt->MaDeTai, $sv->MaSV);
            }
        }

        // Áp dụng cho danh sách đã chấm ($chamdiem)
        foreach ($chamdiem as $cd) {
            $calculateStats($cd->MaDeTai, $cd->MaSV);
        }

        return view('giangvien.chamdiem.index', compact(
            'detaiChuaCham',
            'chamdiem',
            'diemTrungBinh',
            'latestReports'
        ));
    }

    /*--------------------------------------------------------------
    | Lưu điểm BÁO CÁO CUỐI
    --------------------------------------------------------------*/
    public function store(Request $request)
    {
        $request->validate([
            'MaDeTai' => 'required|exists:DeTai,MaDeTai',
            'MaSV' => 'required|exists:SinhVien,MaSV',
            'Diem' => 'required|numeric|min:0|max:10',
            'NhanXet' => 'nullable|string|max:500',
        ]);

        ChamDiem::updateOrCreate( // Nếu đã chấm rồi thì update, chưa thì tạo mới
            [
                'MaDeTai' => $request->MaDeTai,
                'MaSV' => $request->MaSV,
                'MaGV' => Auth::user()->giangVien->MaGV,
                'MaTienDo' => null, // QUAN TRỌNG: Đảm bảo đây là điểm báo cáo cuối
            ],
            [
                'Diem' => $request->Diem,
                'NhanXet' => $request->NhanXet,
                'NgayCham' => Carbon::now(),
                'TrangThai' => 'Chờ duyệt',
                'DiemCuoi' => null,
            ]
        );

        return back()->with('success', 'Chấm điểm sinh viên thành công!');
    }

    /*--------------------------------------------------------------
    | Cập nhật điểm
    --------------------------------------------------------------*/
    public function update(Request $request, $id)
    {
        $request->validate([
            'Diem' => 'required|numeric|min:0|max:10',
            'NhanXet' => 'nullable|string|max:500'
        ]);

        $chamdiem = ChamDiem::findOrFail($id); // Lấy bản ghi chấm điểm

        if ($chamdiem->DiemCuoi !== null) {
            return redirect()->back()->with('error', 'Điểm đã được duyệt, không thể chỉnh sửa!');
        }

        $chamdiem->update([
            'Diem' => $request->Diem,
            'NhanXet' => $request->NhanXet,
            'NgayCham' => Carbon::now(),
            'TrangThai' => 'Chờ duyệt',
            'DiemCuoi' => null
        ]);

        return redirect()->back()->with('success', 'Cập nhật điểm sinh viên thành công!');
    }
}