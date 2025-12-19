<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\GiangVien;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\TaiKhoan;
use App\Models\CCCD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;

class GiangVienController extends Controller
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
        
        // Chỉ lấy giảng viên thuộc khoa
        $query = GiangVien::where('MaKhoa', $maKhoa)
            ->with(['khoa', 'nganh']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('TenGV', 'like', "%{$search}%")
                  ->orWhere('MaGV', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%");
            });
        }

        $giangviens = $query->paginate(20)->appends($request->query());
        
        // Lấy ngành thuộc khoa
        $nganhs = Nganh::whereHas('lops', function($q) use ($maKhoa) {
            $q->where('MaKhoa', $maKhoa);
        })->get();
        
        return view('canbo.giangvien.index', compact('giangviens', 'nganhs'));
    }

    public function store(Request $request)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa được phân công quản lý khoa nào.'], 403);
        }
        
        $request->validate([
            'TenGV' => 'required|string|max:200',
            'MaCCCD' => 'required|string|size:12|unique:GiangVien,MaCCCD',
            'SDT' => 'required|string|size:10',
            'Email' => 'required|email|max:200',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'MaNganh' => 'nullable|exists:Nganh,MaNganh',
        ]);

        DB::beginTransaction();
        
        try {
            // Tự sinh Mã GV
            $lastGV = GiangVien::orderBy('MaGV', 'desc')->first();
            $newNumber = $lastGV ? intval(substr($lastGV->MaGV, 2)) + 1 : 1;
            $MaGV = 'GV' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // Tạo CCCD
            CCCD::updateOrCreate(
                ['MaCCCD' => $request->MaCCCD],
                ['NgayCap' => now(), 'NoiCap' => 'Chưa cập nhật']
            );

            // Tạo tài khoản
            $taikhoan = TaiKhoan::create([
                'MaSo' => $MaGV,
                'MatKhau' => Hash::make($MaGV),
                'VaiTro' => 'GiangVien',
                'active' => true
            ]);

            // Tạo giảng viên - Tự động gán MaKhoa
            $giangvien = GiangVien::create([
                'MaGV' => $MaGV,
                'MaTK' => $taikhoan->MaTK,
                'TenGV' => $request->TenGV,
                'GioiTinh' => $request->GioiTinh,
                'NgaySinh' => $request->NgaySinh,
                'MaCCCD' => $request->MaCCCD,
                'SDT' => $request->SDT,
                'Email' => $request->Email,
                'MaKhoa' => $maKhoa,
                'MaNganh' => $request->MaNganh,
                'HocVi' => $request->HocVi,
                'HocHam' => $request->HocHam,
            ]);

            DB::commit();
            
            return response()->json(['success' => true, 'giangvien' => $giangvien, 'message' => "Thêm thành công! Mã GV: $MaGV, mật khẩu: $MaGV"]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra giảng viên có thuộc khoa không
        $giangvien = GiangVien::where('MaKhoa', $maKhoa)
            ->with(['khoa', 'nganh'])
            ->findOrFail($id);
        
        return response()->json(['giangvien' => $giangvien]);
    }

    public function detail($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        // Kiểm tra giảng viên có thuộc khoa không
        $giangvien = GiangVien::where('MaKhoa', $maKhoa)
            ->with(['khoa', 'nganh', 'cccd'])
            ->findOrFail($id);
            
        // Lấy các lớp chủ nhiệm
        $lops = \App\Models\Lop::where('MaGV', $id)->get();
        
        // Lấy đề tài hướng dẫn
        $huongdans = \App\Models\DeTai::where('MaGV', $id)->with(['namHoc', 'sinhviens'])->get();
        
        // Lấy đề tài phản biện (nếu có bảng phancong hoặc check logic phản biện)
        // Giả sử có bảng phancong hoặc quan hệ phanbien
        $phanbiens = DB::table('phancong')
            ->join('detai', 'phancong.MaDeTai', '=', 'detai.MaDeTai')
            ->join('namhoc', 'detai.MaNamHoc', '=', 'namhoc.MaNamHoc')
            ->where('phancong.MaGV', $id)
            ->select('detai.*', 'namhoc.TenNamHoc', 'phancong.VaiTro')
            ->get();
            
        return view('canbo.giangvien.detail', compact('giangvien', 'lops', 'huongdans', 'phanbiens'));
    }

    public function update(Request $request, $id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $request->validate([
            'TenGV' => 'required|string|max:200',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:200',
            'SDT' => 'required|digits:10',
            'MaCCCD' => 'required|digits:12',
            'MaNganh' => 'nullable|exists:Nganh,MaNganh',
        ]);

        // Chỉ cho phép sửa giảng viên thuộc khoa
        $giangvien = GiangVien::where('MaKhoa', $maKhoa)->findOrFail($id);

        DB::beginTransaction();
        
        try {
            // Cập nhật CCCD
            CCCD::updateOrCreate(
                ['MaCCCD' => $request->MaCCCD],
                ['NgayCap' => now(), 'NoiCap' => 'Chưa cập nhật']
            );

            $giangvien->update([
                'TenGV' => $request->TenGV,
                'GioiTinh' => $request->GioiTinh,
                'NgaySinh' => $request->NgaySinh,
                'MaCCCD' => $request->MaCCCD,
                'SDT' => $request->SDT,
                'Email' => $request->Email,
                'MaNganh' => $request->MaNganh,
                'HocVi' => $request->HocVi,
                'HocHam' => $request->HocHam,
            ]);

            DB::commit();
            
            return response()->json(['success' => true, 'giangvien' => $giangvien]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $maKhoa = $this->getCanBoKhoa();
        
        try {
            DB::beginTransaction();
            
            // Chỉ cho phép xóa giảng viên thuộc khoa
            $giangvien = GiangVien::where('MaKhoa', $maKhoa)->findOrFail($id);
            $maTK = $giangvien->MaTK;
            
            // Xóa các bản ghi liên quan
            DB::table('phancong')->where('MaGV', $id)->delete();
            DB::table('chamdiem')->where('MaGV', $id)->delete();
            DB::table('lop')->where('MaGV', $id)->update(['MaGV' => null]);
            DB::table('detai')->where('MaGV', $id)->update(['MaGV' => null]);
            
            // Xóa giảng viên
            $giangvien->delete();
            
            // Xóa tài khoản
            if ($maTK) {
                TaiKhoan::where('MaTK', $maTK)->delete();
            }
            
            DB::commit();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Không thể xóa: ' . $e->getMessage()], 500);
        }
    }

    public function export()
    {
        $maKhoa = $this->getCanBoKhoa();
        
        $filePath = storage_path('app/public/giangvien_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã GV', 'Tên GV', 'Email', 'SĐT', 'CCCD', 'Ngày sinh', 'Giới tính', 'Ngành', 'Học vị', 'Học hàm']);

        // Chỉ export giảng viên thuộc khoa
        $giangviens = GiangVien::where('MaKhoa', $maKhoa)->with('nganh')->get();

        foreach ($giangviens as $gv) {
            $writer->addRow([
                $gv->MaGV,
                $gv->TenGV,
                $gv->Email,
                $gv->SDT,
                $gv->MaCCCD,
                $gv->NgaySinh,
                $gv->GioiTinh,
                $gv->nganh->TenNganh ?? '',
                $gv->HocVi,
                $gv->HocHam
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

        $lastGV = GiangVien::orderBy('MaGV', 'desc')->first();
        $currentMaxId = $lastGV ? intval(substr($lastGV->MaGV, 2)) : 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $tenGV = trim($row['TenGV'] ?? $row['Họ tên'] ?? '');

                if (!$tenGV) {
                    $errors[] = "Dòng " . ($index + 2) . ": Thiếu Tên GV";
                    continue;
                }

                // Tự sinh Mã GV
                $currentMaxId++;
                $maGV = 'GV' . str_pad($currentMaxId, 3, '0', STR_PAD_LEFT);

                // Tạo CCCD nếu có
                $cccdVal = isset($row['CCCD']) ? trim($row['CCCD']) : null;
                if (!empty($cccdVal)) {
                    CCCD::firstOrCreate(
                        ['MaCCCD' => $cccdVal],
                        ['NgayCap' => now(), 'NoiCap' => 'Chưa cập nhật']
                    );
                }

                // Tìm ngành (nếu có)
                $maNganh = null;
                if (!empty($row['Ngành'])) {
                    $nganh = Nganh::where('TenNganh', $row['Ngành'])->first();
                    $maNganh = $nganh?->MaNganh;
                }

                $gv = GiangVien::create([
                    'MaGV' => $maGV,
                    'TenGV' => $tenGV,
                    'Email' => $row['Email'] ?? null,
                    'SDT' => $row['SDT'] ?? null,
                    'MaCCCD' => $cccdVal,
                    'NgaySinh' => $row['NgaySinh'] ?? null,
                    'GioiTinh' => $row['GioiTinh'] ?? 'Nam',
                    'MaKhoa' => $maKhoa,
                    'MaNganh' => $maNganh,
                    'HocVi' => $row['HocVi'] ?? null,
                    'HocHam' => $row['HocHam'] ?? null,
                ]);

                $tk = TaiKhoan::create([
                    'MaSo' => $maGV,
                    'MatKhau' => Hash::make($maGV),
                    'VaiTro' => 'GiangVien',
                    'active' => true
                ]);
                
                $gv->update(['MaTK' => $tk->MaTK]);

                $count++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import thành công {$count} giảng viên!" . (count($errors) ? " Có " . count($errors) . " dòng lỗi." : "")
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Import thất bại: ' . $e->getMessage()], 500);
        }
    }
}
