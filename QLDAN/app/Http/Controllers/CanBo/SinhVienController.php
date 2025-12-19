<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use App\Models\Lop;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;

class SinhVienController extends Controller
{
    // Lấy MaKhoa của cán bộ đang đăng nhập
    private function getCanBoKhoa()
    {
        $user = Auth::user();
        $canbo = $user->canBoQL;
        return $canbo ? $canbo->MaKhoa : null;
    }

    public function index(Request $request)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            abort(403, 'Bạn chưa được phân công quản lý khoa nào.');
        }
        
        // Chỉ lấy sinh viên thuộc các lớp của khoa
        $query = SinhVien::whereHas('lop', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->with(['lop', 'nganh', 'khoa']);

        // Filter theo lớp
        if ($request->filled('lop')) {
            $query->where('MaLop', $request->lop);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('TenSV', 'like', "%{$search}%")
                  ->orWhere('MaSV', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%");
            });
        }

        $sinhviens = $query->paginate(20)->appends($request->query());
        
        // Chỉ lấy lớp thuộc khoa của cán bộ
        $lops = Lop::where('MaKhoa', $maKhoa)->get();
        
        return view('canbo.sinhvien.index', compact('sinhviens', 'lops'));
    }

    public function store(Request $request)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa được phân công quản lý khoa nào.'], 403);
        }
        
        $request->validate([
            'TenSV' => 'required|string|max:200',
            'MaCCCD' => 'required|string|size:12|unique:SinhVien,MaCCCD',
            'SDT' => 'required|string|size:10',
            'Email' => 'required|email|max:200',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'MaLop' => 'required|exists:Lop,MaLop',
        ]);

        // Kiểm tra lớp có thuộc khoa của cán bộ không
        $lop = Lop::where('MaLop', $request->MaLop)->where('MaKhoa', $maKhoa)->first();
        
        if (!$lop) {
            return response()->json(['success' => false, 'message' => 'Lớp không thuộc khoa của bạn'], 403);
        }

        DB::beginTransaction();
        
        try {
            // Tạo CCCD nếu chưa có
            \App\Models\CCCD::updateOrCreate(
                ['MaCCCD' => $request->MaCCCD],
                ['NgayCap' => now(), 'NoiCap' => 'Chưa cập nhật']
            );

            // Tạo sinh viên
            $sinhvien = SinhVien::create([
                'TenSV' => $request->TenSV,
                'GioiTinh' => $request->GioiTinh,
                'NgaySinh' => $request->NgaySinh,
                'MaCCCD' => $request->MaCCCD,
                'SDT' => $request->SDT,
                'Email' => $request->Email,
                'MaLop' => $lop->MaLop,
                'MaKhoa' => $lop->MaKhoa,
                'MaNganh' => $lop->MaNganh,
                'TrangThai' => 'Đang học'
            ]);

            // Tạo tài khoản
            $taikhoan = TaiKhoan::create([
                'MaSo' => $sinhvien->MaSV,
                'MatKhau' => bcrypt($sinhvien->MaSV),
                'VaiTro' => 'SinhVien',
            ]);

            // Gán MaTK
            $sinhvien->update(['MaTK' => $taikhoan->MaTK]);

            DB::commit();
            
            return response()->json(['success' => true, 'sinhvien' => $sinhvien]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra sinh viên có thuộc khoa của cán bộ không
        $sinhvien = SinhVien::whereHas('lop', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->with(['lop', 'nganh', 'khoa'])->findOrFail($id);
        
        return response()->json(['sinhvien' => $sinhvien]);
    }

    public function detail($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra sinh viên có thuộc khoa không
        $sinhvien = SinhVien::whereHas('lop', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->with(['lop', 'nganh', 'khoa', 'namhoc'])->findOrFail($id);
        
        // Lấy đề tài của sinh viên
        $deTai = \App\Models\DeTai::whereHas('sinhviens', function($q) use ($id) {
            $q->where('SinhVien.MaSV', $id);
        })->with(['giangVien', 'canBo', 'namHoc'])->first();
        
        // Lấy báo cáo
        $baoCaos = $deTai ? \App\Models\BaoCao::where('MaDeTai', $deTai->MaDeTai)->get() : collect();
        
        // Lấy điểm (bao gồm cả điểm nhóm trong TienDo nếu chưa có điểm riêng)
        $diems = $deTai ? \App\Models\ChamDiem::where('MaDeTai', $deTai->MaDeTai)->with('giangvien')->get() : collect();
        
        // Lấy tiến độ
        $tiendos = $deTai ? \App\Models\TienDo::where('MaDeTai', $deTai->MaDeTai)->orderBy('Deadline')->with('giangVien')->get() : collect();

        // Merge group grades from TienDo into $diems if individual grade doesn't exist
        if ($deTai) {
            foreach ($tiendos as $tiendo) {
                if ($tiendo->Diem) {
                    // Check if an individual grade exists for this progress report
                    $individualGrade = $diems->where('MaTienDo', $tiendo->MaTienDo)->first();
                    
                    if (!$individualGrade) {
                        // Create a dummy ChamDiem object for display
                        $dummyChamDiem = new \App\Models\ChamDiem();
                        $dummyChamDiem->MaCham = 'G-' . $tiendo->MaTienDo; // Mock ID
                        $dummyChamDiem->MaDeTai = $tiendo->MaDeTai;
                        $dummyChamDiem->MaTienDo = $tiendo->MaTienDo;
                        $dummyChamDiem->MaGV = $tiendo->MaGV;
                        $dummyChamDiem->Diem = $tiendo->Diem;
                        $dummyChamDiem->NhanXet = $tiendo->NhanXet;
                        $dummyChamDiem->NgayCham = $tiendo->NgayCham;
                        // Manual relation loading
                        $dummyChamDiem->setRelation('giangvien', $tiendo->giangVien);
                        
                        $diems->push($dummyChamDiem);
                    }
                }
            }
        }
        
        return view('canbo.sinhvien.detail', compact('sinhvien', 'deTai', 'baoCaos', 'diems', 'tiendos'));
    }

    public function update(Request $request, $id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $request->validate([
            'TenSV' => 'required|string|max:200',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:200',
            'SDT' => 'required|digits:10',
            'MaCCCD' => 'required|digits:12',
            'MaLop' => 'required|exists:Lop,MaLop',
        ]);

        // Kiểm tra sinh viên có thuộc khoa không
        $sinhvien = SinhVien::whereHas('lop', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->findOrFail($id);

        // Kiểm tra lớp mới có thuộc khoa không
        $lop = Lop::where('MaLop', $request->MaLop)->where('MaKhoa', $maKhoa)->first();
        
        if (!$lop) {
            return response()->json(['success' => false, 'message' => 'Lớp không thuộc khoa của bạn'], 403);
        }

        DB::beginTransaction();
        
        try {
            // Cập nhật CCCD
            \App\Models\CCCD::updateOrCreate(
                ['MaCCCD' => $request->MaCCCD],
                ['NgayCap' => now(), 'NoiCap' => 'Chưa cập nhật']
            );

            $sinhvien->update([
                'TenSV' => $request->TenSV,
                'GioiTinh' => $request->GioiTinh,
                'NgaySinh' => $request->NgaySinh,
                'MaCCCD' => $request->MaCCCD,
                'SDT' => $request->SDT,
                'Email' => $request->Email,
                'MaLop' => $lop->MaLop,
                'MaKhoa' => $lop->MaKhoa,
                'MaNganh' => $lop->MaNganh,
            ]);

            DB::commit();
            
            return response()->json(['success' => true, 'sinhvien' => $sinhvien]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Chỉ cho phép xóa sinh viên thuộc khoa của mình
        $sinhvien = SinhVien::whereHas('lop', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->findOrFail($id);
        
        $maTK = $sinhvien->MaTK;
        
        $sinhvien->delete();
        
        if ($maTK) {
            TaiKhoan::where('MaTK', $maTK)->delete();
        }
        
        return response()->json(['success' => true]);
    }

    public function export()
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $filePath = storage_path('app/public/sinhvien_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã SV', 'Tên SV', 'Giới tính', 'Ngày sinh', 'Email', 'SĐT', 'Lớp', 'Ngành']);

        // Chỉ export sinh viên thuộc khoa của cán bộ
        $sinhviens = SinhVien::whereHas('lop', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->with(['lop', 'nganh'])->get();

        foreach ($sinhviens as $sv) {
            $writer->addRow([
                $sv->MaSV,
                $sv->TenSV,
                $sv->GioiTinh,
                $sv->NgaySinh,
                $sv->Email,
                $sv->SDT,
                $sv->lop->TenLop ?? '',
                $sv->nganh->TenNganh ?? '',
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,csv']);

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
                $tenSV = trim($row['Tên sinh viên'] ?? $row['TenSV'] ?? '');

                if (!$tenSV) {
                    $lineNumber = (int)$index + 2;
                    $errors[] = "Dòng {$lineNumber}: Thiếu Tên sinh viên.";
                    continue;
                }

                // Tìm lớp thuộc khoa của cán bộ
                $lop = Lop::where('TenLop', $row['Lớp'] ?? '')
                    ->where('MaKhoa', $maKhoa)
                    ->first();

                if (!$lop) {
                    $lineNumber = (int)$index + 2;
                    $errors[] = "Dòng {$lineNumber}: Lớp không thuộc khoa của bạn.";
                    continue;
                }

                $sinhvien = SinhVien::create([
                    'TenSV' => $tenSV,
                    'GioiTinh' => $row['Giới tính'] ?? 'Nam',
                    'NgaySinh' => $row['Ngày sinh'] ?? null,
                    'Email' => $row['Email'] ?? null,
                    'SDT' => $row['SDT'] ?? null,
                    'MaLop' => $lop->MaLop,
                    'MaKhoa' => $lop->MaKhoa,
                    'MaNganh' => $lop->MaNganh,
                    'TrangThai' => 'Đang học'
                ]);

                $tk = TaiKhoan::create([
                    'MaSo' => $sinhvien->MaSV,
                    'MatKhau' => bcrypt($sinhvien->MaSV),
                    'VaiTro' => 'SinhVien'
                ]);

                $sinhvien->update(['MaTK' => $tk->MaTK]);

                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import thành công {$count} sinh viên!" . (count($errors) ? " Có " . count($errors) . " dòng lỗi." : "")
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Import thất bại: ' . $e->getMessage()], 500);
        }
    }
}
