<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DeTai;
use App\Models\NamHoc;
use Illuminate\Support\Facades\DB;

use Spatie\SimpleExcel\SimpleExcelReader;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Log;

class DeTaiController extends Controller
{
    /** ============================
     *  DANH SÁCH ĐỀ TÀI CỦA GIẢNG VIÊN
     * ============================ */
    public function index()
    {
        $giangVien = Auth::user()->giangVien;

        if (!$giangVien) {
            return redirect()->route('login')->with('error', 'Không xác định được giảng viên!');
        }

        $detais = DeTai::where('MaGV', $giangVien->MaGV)
            ->orderByDesc('MaDeTai')
            ->get();
        
        // Data for Modal
        $namHocs = NamHoc::orderByDesc('MaNamHoc')->get();
        $khoas = \App\Models\Khoa::all();
        $nganhs = \App\Models\Nganh::all();
        $loaiDeTais = DB::table('DeTai')->select('LoaiDeTai')->distinct()->pluck('LoaiDeTai');

        return view('giangvien.detai.index', compact('detais', 'namHocs', 'khoas', 'nganhs', 'loaiDeTais'));
    }

    /** ============================
     *  FORM THÊM MỚI
     * ============================ */
    public function create()
    {
        $namHocs = NamHoc::orderByDesc('MaNamHoc')->get();

        $linhVucs = DB::table('DeTai')->select('LinhVuc')->distinct()->pluck('LinhVuc');
        $loaiDeTais = DB::table('DeTai')->select('LoaiDeTai')->distinct()->pluck('LoaiDeTai');

        return view('giangvien.detai.create', compact('namHocs', 'linhVucs', 'loaiDeTais'));
    }

    /** ============================
     *  LƯU ĐỀ TÀI
     * ============================ */
    public function store(Request $request)
    {
        $giangVien = Auth::user()->giangVien;

        if (!$giangVien) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Không xác định được giảng viên!'], 401);
            }
            return redirect()->route('login')->with('error', 'Không xác định được giảng viên!');
        }

        $validated = $request->validate([
            'TenDeTai' => 'required|string|max:255',
            'MoTa' => 'nullable|string|max:1000',
            'MaKhoa' => 'required|exists:khoa,MaKhoa',
            'MaNganh' => 'required|exists:nganh,MaNganh',
            'LoaiDeTai' => 'required|string|max:20',
            'SoLuongThanhVien' => 'required|integer|min:1|max:5',
            'MaNamHoc' => 'required|exists:namhoc,MaNamHoc',
        ]);

        // Get NamHoc name
        // Get NamHoc name
        $namHoc = NamHoc::where('MaNamHoc', $validated['MaNamHoc'])->first();
        Log::info('NamHoc Query Result:', ['namHoc' => $namHoc, 'MaNamHoc' => $validated['MaNamHoc']]);
        $tenNamHoc = $namHoc ? $namHoc->TenNamHoc : null;
        Log::info('TenNamHoc:', ['tenNamHoc' => $tenNamHoc]);

        $detai = DeTai::create([
            'TenDeTai' => $validated['TenDeTai'],
            'MoTa' => $validated['MoTa'] ?? null,
            'MaKhoa' => $validated['MaKhoa'],
            'MaNganh' => $validated['MaNganh'],
            'LoaiDeTai' => $validated['LoaiDeTai'],
            'SoLuongThanhVien' => $validated['SoLuongThanhVien'],
            'MaNamHoc' => $validated['MaNamHoc'],
            'NamHoc' => $tenNamHoc,
            'TrangThai' => 'Chưa duyệt', // Quan trọng !!!
            'MaGV' => $giangVien->MaGV,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Đề tài đã được thêm và đang chờ duyệt!',
                'detai' => $detai
            ]);
        }

        return redirect()->route('giangvien.detai.index')
            ->with('success', 'Đề tài đã được thêm và đang chờ duyệt!');
    }

    /** ============================
     *  IMPORT EXCEL
     * ============================ */
    public function import(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,csv']);
        $giangVien = Auth::user()->giangVien;

        if (!$giangVien) {
            return response()->json(['success' => false, 'message' => 'Không xác định được giảng viên!'], 401);
        }

        $fileModel = FileHelper::uploadFile($request->file('excel_file'), 'excel');
        $filePath = public_path('img/uploads/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fileModel->path));

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy file'], 404);
        }

        try {
            $rows = SimpleExcelReader::create($filePath)->getRows();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi đọc Excel: ' . $e->getMessage()], 500);
        }

        $count = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                // Support both formats: with spaces and without spaces
                $tenDeTai = trim($row['TenDeTai'] ?? $row['Tên Đề Tài'] ?? '');
                $khoaStr = trim($row['Khoa'] ?? '');
                $nganhStr = trim($row['Nganh'] ?? $row['Ngành'] ?? '');
                $loaiDeTai = trim($row['LoaiDeTai'] ?? $row['Loại Đề Tài'] ?? '');
                $soLuongThanhVien = (int)trim($row['SoLuongThanhVien'] ?? $row['Số Lượng Thành Viên'] ?? '1');
                $namHocStr = trim($row['NamHoc'] ?? $row['Năm Học'] ?? '');
                $moTa = trim($row['MoTa'] ?? $row['Mô Tả'] ?? '');

                if (!$tenDeTai || !$khoaStr || !$nganhStr || !$loaiDeTai || !$namHocStr) {
                    $lineNumber = (int)$index + 2;
                    $errors[] = "Dòng {$lineNumber}: Thiếu thông tin bắt buộc (TenDeTai, Khoa, Nganh, LoaiDeTai, NamHoc).";
                    continue;
                }

                // Validate SoLuongThanhVien
                if ($soLuongThanhVien < 1 || $soLuongThanhVien > 5) {
                    $soLuongThanhVien = 1; // Default to 1 if invalid
                }

                // Find or Create Khoa
                $khoa = \App\Models\Khoa::where('TenKhoa', $khoaStr)->first();
                if (!$khoa) {
                    $khoa = \App\Models\Khoa::create(['TenKhoa' => $khoaStr]);
                }

                // Find or Create Nganh
                $nganh = \App\Models\Nganh::where('TenNganh', $nganhStr)->first();
                if (!$nganh) {
                    $nganh = \App\Models\Nganh::create(['TenNganh' => $nganhStr]);
                }

                // Find or Create NamHoc
                $namHoc = NamHoc::where('TenNamHoc', $namHocStr)->first();
                if (!$namHoc) {
                    $namHoc = NamHoc::create(['TenNamHoc' => $namHocStr]);
                }

                DeTai::create([
                    'TenDeTai' => $tenDeTai,
                    'MaKhoa' => $khoa->MaKhoa,
                    'MaNganh' => $nganh->MaNganh,
                    'LoaiDeTai' => $loaiDeTai,
                    'SoLuongThanhVien' => $soLuongThanhVien,
                    'MaNamHoc' => $namHoc->MaNamHoc,
                    'NamHoc' => $namHoc->TenNamHoc,
                    'MoTa' => $moTa,
                    'TrangThai' => 'Chưa duyệt',
                    'MaGV' => $giangVien->MaGV,
                ]);
                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import thành công {$count} đề tài!" . (count($errors) ? " Có " . count($errors) . " dòng lỗi." : "")
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Import thất bại: ' . $e->getMessage()], 500);
        }
    }

    /** ============================
     *  FORM CHỈNH SỬA
     * ============================ */
    public function edit($id)
    {
        $detai = DeTai::findOrFail($id);

        // Giảng viên KHÔNG được sửa khi đề tài đã duyệt hoặc mở đăng ký
        if (in_array($detai->TrangThai, ['Mở đăng ký', 'Đã duyệt'])) {
            return back()->with('error', 'Đề tài đã được duyệt, không thể chỉnh sửa!');
        }

        $namHocs = NamHoc::orderByDesc('MaNamHoc')->get();
        $linhVucs = DB::table('DeTai')->select('LinhVuc')->distinct()->pluck('LinhVuc');
        $loaiDeTais = DB::table('DeTai')->select('LoaiDeTai')->distinct()->pluck('LoaiDeTai');

        return view('giangvien.detai.edit', compact('detai', 'namHocs', 'linhVucs', 'loaiDeTais'));
    }

    /** ============================
     *  CẬP NHẬT
     * ============================ */
    public function update(Request $request, $id)
    {
        $detai = DeTai::findOrFail($id);

        if (in_array($detai->TrangThai, ['Mở đăng ký', 'Đã duyệt'])) {
            return back()->with('error', 'Đề tài đã duyệt, không thể cập nhật!');
        }

        $validated = $request->validate([
            'TenDeTai' => 'required|string|max:255',
            'MoTa' => 'nullable|string|max:1000',
            'MaKhoa' => 'required|exists:khoa,MaKhoa',
            'MaNganh' => 'required|exists:nganh,MaNganh',
            'LoaiDeTai' => 'required|string|max:20',
            'SoLuongThanhVien' => 'required|integer|min:1|max:5',
            'MaNamHoc' => 'required|exists:namhoc,MaNamHoc',
        ]);

        // Get NamHoc name
        $namHoc = NamHoc::where('MaNamHoc', $validated['MaNamHoc'])->first();
        $tenNamHoc = $namHoc ? $namHoc->TenNamHoc : null;

        $detai->update([
            'TenDeTai' => $validated['TenDeTai'],
            'MoTa' => $validated['MoTa'] ?? null,
            'MaKhoa' => $validated['MaKhoa'],
            'MaNganh' => $validated['MaNganh'],
            'LoaiDeTai' => $validated['LoaiDeTai'],
            'SoLuongThanhVien' => $validated['SoLuongThanhVien'],
            'MaNamHoc' => $validated['MaNamHoc'],
            'NamHoc' => $tenNamHoc,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Cập nhật đề tài thành công!',
                'detai' => $detai
            ]);
        }

        return redirect()->route('giangvien.detai.index')
            ->with('success', 'Cập nhật đề tài thành công!');
    }

    /** ============================
     *  XEM CHI TIẾT
     * ============================ */
    public function show($id)
    {
        $detai = DeTai::findOrFail($id);
        return view('giangvien.detai.show', compact('detai'));
    }

    /** ============================
     *  XÓA
     * ============================ */
    public function destroy($id)
    {
        $detai = DeTai::findOrFail($id);

        // Giảng viên không được xóa đề tài đã duyệt
        if (in_array($detai->TrangThai, ['Mở đăng ký', 'Đã duyệt'])) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Không thể xóa đề tài đã duyệt!'], 403);
            }
            return back()->with('error', 'Không thể xóa đề tài đã duyệt!');
        }

        $detai->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Xóa đề tài thành công!']);
        }

        return redirect()->route('giangvien.detai.index')
            ->with('success', 'Xóa đề tài thành công!');
    }


    /** ============================
 *  GIẢNG VIÊN ĐẶT DEADLINE BÁO CÁO
 * ============================ */
public function setDeadline(Request $request, $id)
{
    $request->validate([
        'DeadlineBaoCao' => 'required|date'
    ]);

    $detai = DeTai::findOrFail($id);

    // Chỉ GV tạo đề tài được chỉnh deadline
    if ($detai->MaGV != Auth::user()->giangVien->MaGV) {
        return back()->with('error', 'Bạn không có quyền chỉnh deadline của đề tài này!');
    }

    $detai->DeadlineBaoCao = $request->DeadlineBaoCao;
    $detai->save();

    return back()->with('success', 'Đã cập nhật deadline báo cáo!');
}

}