<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\DeTai;
use App\Models\CauHinhHeThong;
use Carbon\Carbon;

class DeTaiController extends Controller
{
    public function index(Request $request)
    {
        $query = DeTai::with('giangVien')
            ->withCount('sinhViens') // Đếm số sinh viên đã đăng ký
            ->whereIn('TrangThai', ['Đã duyệt', 'Mở đăng ký']);

        // Lọc theo tên giảng viên
        if ($request->filled('giangvien')) {
            $query->whereHas('giangVien', function($q) use ($request) {
                $q->where('TenGV', 'like', '%' . $request->giangvien . '%');
            });
        }

        $detais = $query->orderByDesc('MaDeTai')->get();
        
        // Nhóm đề tài theo giảng viên
        $detaisByGiangVien = $detais->groupBy(function($item) {
            return $item->giangVien ? $item->giangVien->MaGV : 'chua_co';
        });

        $user = Auth::user();
        $sinhvien = $user->sinhvien;
        $maSV = $sinhvien->MaSV ?? null;

        // Lấy cấu hình năm học của sinh viên
        $config = null;
        $now = now();
        if ($sinhvien) {
            $config = CauHinhHeThong::where('MaNamHoc', $sinhvien->MaNamHoc)->first();
        }

        $deTaiDaDangKy = null;
        if ($maSV) {
            $deTaiDaDangKy = DeTai::whereHas('sinhViens', function($q) use ($maSV) {
                $q->where('SinhVien.MaSV', $maSV);
            })->first();
        }

        return view('sinhvien.detai.index', compact('detaisByGiangVien', 'deTaiDaDangKy', 'config', 'now'));
    }

    public function dangKy($id)
    {
        $user = Auth::user();
        $sinhvien = $user->sinhvien;

        if (!$sinhvien) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Không xác định được sinh viên.'], 401);
            }
            return back()->with('error', 'Không xác định được sinh viên.');
        }

        $maSV = $sinhvien->MaSV;

        /*  ⭐⭐ KIỂM TRA THỜI GIAN ĐĂNG KÝ THEO NĂM HỌC SINH VIÊN ⭐⭐ */
        $config = CauHinhHeThong::where('MaNamHoc', $sinhvien->MaNamHoc)->first();

        if (!$config) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Năm học này chưa được mở đăng ký!'], 400);
            }
            return back()->with('error', 'Năm học này chưa được mở đăng ký!');
        }

        $now = now();

        if ($now->lt($config->ThoiGianMoDangKy)) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Chưa đến thời gian mở đăng ký đề tài.'], 400);
            }
            return back()->with('error', 'Chưa đến thời gian mở đăng ký đề tài.');
        }

        if ($now->gt($config->ThoiGianDongDangKy)) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Đã hết thời gian đăng ký đề tài.'], 400);
            }
            return back()->with('error', 'Đã hết thời gian đăng ký đề tài.');
        }

        /*  ⭐ Kiểm tra sinh viên đã có đề tài chưa  */
        $daDangKy = DeTai::whereHas('sinhViens', function($query) use ($maSV) {
            $query->where('SinhVien.MaSV', $maSV);
        })->exists();

        if ($daDangKy) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Bạn đã đăng ký một đề tài khác rồi!'], 400);
            }
            return back()->with('error', 'Bạn đã đăng ký một đề tài khác rồi!');
        }

        /* ⭐ Cho phép đăng ký */
        $detai = DeTai::findOrFail($id);

        if (!in_array($detai->TrangThai, ['Đã duyệt', 'Mở đăng ký'])) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Đề tài này hiện không mở đăng ký.'], 400);
            }
            return back()->with('error', 'Đề tài này hiện không mở đăng ký.');
        }

        /* ⭐ Kiểm tra số lượng thành viên đã đủ chưa */
        $maxMembers = $detai->SoLuongThanhVien ?? 1;
        $currentMembers = $detai->sinhViens()->count();
        
        if ($currentMembers >= $maxMembers) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Đề tài này đã đủ số lượng đăng ký!'], 400);
            }
            return back()->with('error', 'Đề tài này đã đủ số lượng đăng ký!');
        }

        $detai->sinhViens()->attach($maSV, [
            'VaiTro' => 'Sinh viên'
        ]);

        // ⭐ TỰ ĐỘNG TẠO CONVERSATION CHO CHAT
        // Chỉ tạo nếu đề tài có giảng viên hướng dẫn
        if ($detai->MaGV) {
            try {
                \App\Models\Conversation::findOrCreate($maSV, $detai->MaGV, $detai->MaDeTai);
            } catch (\Exception $e) {
                Log::error('Failed to create chat conversation: ' . $e->getMessage());
            }
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => '🎉 Đăng ký đề tài thành công!']);
        }
        return back()->with('success', '🎉 Đăng ký đề tài thành công!');
    }
    public function huyDangKy($id)
{
    $user = Auth::user();
    $sinhvien = $user->sinhvien;
    $maSV = $sinhvien->MaSV;

    // Lấy cấu hình năm học
    $config = CauHinhHeThong::where('MaNamHoc', $sinhvien->MaNamHoc)->first();

    if (!$config) {
        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy cấu hình năm học.'], 400);
        }
        return back()->with('error', 'Không tìm thấy cấu hình năm học.');
    }

    // Kiểm tra hết thời gian đăng ký
    $now = now();

    if ($now->gt($config->ThoiGianDongDangKy)) {
        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => '⛔ Bạn không thể hủy đề tài sau khi đã hết thời gian đăng ký.'], 400);
        }
        return back()->with('error', '⛔ Bạn không thể hủy đề tài sau khi đã hết thời gian đăng ký.');
    }

    // Lấy đề tài
    $detai = DeTai::findOrFail($id);

    // Xóa quan hệ
    $detai->sinhViens()->detach($maSV);

    if (request()->ajax()) {
        return response()->json(['success' => true, 'message' => '🟢 Hủy đăng ký thành công!']);
    }
    return back()->with('success', '🟢 Hủy đăng ký thành công!');
}

}