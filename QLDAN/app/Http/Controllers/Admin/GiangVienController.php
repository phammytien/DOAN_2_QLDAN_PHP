<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiangVien;
use App\Models\CCCD;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\SinhVien;
use App\Models\Khoa;
use Illuminate\Support\Facades\DB;
use App\Models\Nganh;
use App\Models\NamHoc;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Str;

class GiangVienController extends Controller
{
    // ======= DANH SÁCH GIẢNG VIÊN =======
    // ======= DANH SÁCH GIẢNG VIÊN =======
    public function index(Request $request)
    {
        $query = GiangVien::with(['khoa', 'nganh', 'namhoc', 'taikhoan']);

        // ================== TÌM KIẾM ==================
        if ($request->filled('q')) {
            $key = $request->q;

            $query->where(function($q) use ($key) {
                $q->where('MaGV', 'LIKE', "%$key%")
                  ->orWhere('TenGV', 'LIKE', "%$key%")
                  ->orWhere('Email', 'LIKE', "%$key%")
                  ->orWhere('SDT', 'LIKE', "%$key%")
                  ->orWhere('MaCCCD', 'LIKE', "%$key%");
            });
        }

        // ================== LỌC THEO KHOA ==================
        if ($request->filled('khoa')) {
            $query->where('MaKhoa', $request->khoa);
        }

        $gvs = $query->paginate(10)->appends($request->query());

        // ====== LOAD DỮ LIỆU SELECT CHO MODAL ======
        $khoas = Khoa::all();
        $nganhs = Nganh::all();
        $namhocs = NamHoc::all();
        $taikhoans = TaiKhoan::all();

        return view('admin.giangvien.index', compact('gvs', 'khoas', 'nganhs', 'namhocs', 'taikhoans'));
    }

    // ... (create, store, edit, update, show, destroy methods remain unchanged) ...

    // ================== EXPORT ==================
    public function export(Request $request)
    {
        $filePath = storage_path('app/public/giangvien_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['MaGV', 'TenGV', 'Email', 'SDT', 'CCCD', 'NgaySinh', 'GioiTinh', 'Khoa', 'Nganh', 'HocVi', 'HocHam']);

        $query = GiangVien::with(['khoa', 'nganh']);

        // Lọc theo khoa nếu có
        if ($request->filled('khoa')) {
            $query->where('MaKhoa', $request->khoa);
        }

        $gvs = $query->get();

        foreach ($gvs as $gv) {
            $writer->addRow([
                $gv->MaGV,
                $gv->TenGV,
                $gv->Email,
                $gv->SDT,
                $gv->MaCCCD,
                $gv->NgaySinh,
                $gv->GioiTinh,
                $gv->khoa->TenKhoa ?? '',
                $gv->nganh->TenNganh ?? '',
                $gv->HocVi,
                $gv->HocHam
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }



    // ======= FORM THÊM =======
public function create()
{
    $taikhoans = TaiKhoan::all();
    $khoas = Khoa::all();
    $nganhs = Nganh::all();
    $namhocs = NamHoc::all();

    return view('admin.giangvien.create',
        compact('taikhoans','khoas','nganhs','namhocs')
    );
}


// ======= LƯU GIẢNG VIÊN MỚI =======
public function store(Request $request)
{
    $request->validate([
        'TenGV' => 'required|string|max:200',
        'MaCCCD' => 'required|string|size:12|regex:/^[0-9]{12}$/|unique:GiangVien,MaCCCD',
        'SDT' => 'required|string|size:10|regex:/^[0-9]{10}$/',
        'Email' => 'required|email|max:200',
        'NgaySinh' => 'required|date',
        'GioiTinh' => 'required|in:Nam,Nữ',
        'MaKhoa' => 'required',
        'MaNganh' => 'required'
    ], [
        'TenGV.required' => 'Tên giảng viên không được để trống',
        'MaCCCD.required' => 'CCCD không được để trống',
        'MaCCCD.size' => 'CCCD phải có đúng 12 số',
        'MaCCCD.regex' => 'CCCD chỉ được chứa số',
        'MaCCCD.unique' => 'CCCD này đã tồn tại',
        'SDT.required' => 'Số điện thoại không được để trống',
        'SDT.size' => 'Số điện thoại phải có đúng 10 số',
        'SDT.regex' => 'Số điện thoại chỉ được chứa số',
        'Email.required' => 'Email không được để trống',
        'Email.email' => 'Email không hợp lệ',
        'NgaySinh.required' => 'Ngày sinh không được để trống',
        'GioiTinh.required' => 'Giới tính không được để trống',
        'MaKhoa.required' => 'Khoa không được để trống',
        'MaNganh.required' => 'Ngành không được để trống'
    ]);

    // ===== Xử lý Dynamic Data (Khoa, Ngành) =====
    $maKhoa = $this->findOrCreate(Khoa::class, 'MaKhoa', 'TenKhoa', $request->MaKhoa);
    $maNganh = $this->findOrCreate(Nganh::class, 'MaNganh', 'TenNganh', $request->MaNganh);

    // ===== Tự sinh Mã GV =====
    $lastGV = GiangVien::orderBy('MaGV', 'desc')->first();
    $newNumber = $lastGV ? intval(substr($lastGV->MaGV, 2)) + 1 : 1;
    $MaGV = 'GV' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    // ===== Tạo tài khoản mặc định =====
    $taikhoan = TaiKhoan::create([
        'MaSo'    => $MaGV,
        'MatKhau' => Hash::make($MaGV),
        'VaiTro'  => 'GiangVien',
        'active'  => true
    ]);

    // ===== Tạo/kiểm tra bảng CCCD =====
    if (!empty($request->MaCCCD)) {
        $maCCCD = trim($request->MaCCCD);
        CCCD::updateOrCreate(
            ['MaCCCD' => $maCCCD],
            [
                'NgayCap' => $request->NgayCap ?? now(),
                'NoiCap'  => $request->NoiCap ?? 'Chưa cập nhật'
            ]
        );
    }

    // ===== Lưu thông tin Giảng viên =====
    $giangvien = GiangVien::create([
        'MaGV'        => $MaGV,
        'MaTK'        => $taikhoan->MaTK,
        'TenGV'       => $request->TenGV,
        'GioiTinh'    => $request->GioiTinh,
        'NgaySinh'    => $request->NgaySinh,
        'MaCCCD'      => $request->MaCCCD,
        'TonGiao'     => $request->TonGiao,
        'SDT'         => $request->SDT,
        'Email'       => $request->Email,
        'NoiSinh'     => $request->NoiSinh,
        'HKTT'        => $request->HKTT,
        'DanToc'      => $request->DanToc,
        'HocVi'       => $request->HocVi,
        'HocHam'      => $request->HocHam,
        'ChuyenNganh' => $request->ChuyenNganh,

        // ❗ Những trường modal có nhưng trước đây KHÔNG LƯU
        'MaKhoa'      => $maKhoa,
        'MaNganh'     => $maNganh,
        'MaNamHoc'    => $request->MaNamHoc,
    ]);

    // Lấy tên Khoa và Ngành để hiển thị trong thông báo
    $tenKhoa = Khoa::find($maKhoa)->TenKhoa ?? 'Chưa xác định';
    $tenNganh = Nganh::find($maNganh)->TenNganh ?? 'Chưa xác định';

    $successMessage = "✅ Thêm giảng viên thành công!<br><br>" .
                      "<strong>📋 Thông tin:</strong><br>" .
                      "• Mã GV: {$MaGV}<br>" .
                      "• Họ tên: {$request->TenGV}<br>" .
                      "• Khoa: {$tenKhoa}<br>" .
                      "• Ngành: {$tenNganh}<br>" .
                      "• Email: {$request->Email}<br><br>" .
                      "<strong>🔑 Tài khoản đăng nhập:</strong><br>" .
                      "• Tên đăng nhập: {$MaGV}<br>" .
                      "• Mật khẩu mặc định: {$MaGV}";

    if ($request->has('redirect_to')) {
        return redirect($request->redirect_to)->with('success', $successMessage);
    }

    return redirect()->route('admin.giangvien.index')
        ->with('success', $successMessage);
}


    // ======= FORM SỬA =======
public function edit($id)
{
    $gv = GiangVien::findOrFail($id);
    $taikhoans = TaiKhoan::all();
    $khoas = Khoa::all();
    $nganhs = Nganh::all();
    $namhocs = NamHoc::all();

    // Nếu là AJAX request, trả về partial view
    if (request()->ajax()) {
        return view('admin.giangvien.edit_form', compact('gv', 'taikhoans', 'khoas', 'nganhs', 'namhocs'));
    }

    return view('admin.giangvien.edit', compact('gv', 'taikhoans', 'khoas', 'nganhs', 'namhocs'));
}

    // ======= CẬP NHẬT =======
// ======= CẬP NHẬT GIẢNG VIÊN =======
public function update(Request $request, $id)
{
    $gv = GiangVien::findOrFail($id); // ✅ sửa từ SinhVien -> GiangVien

        $request->validate([
            'TenGV' => 'required|string|max:200',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:200',
            'SDT' => 'required|digits:10',
            'MaCCCD' => 'required|digits:12',
            'MaKhoa' => 'required',
            'MaNganh' => 'required'
        ], [
            'TenGV.required' => 'Tên giảng viên không được để trống',
            'GioiTinh.required' => 'Giới tính không được để trống',
            'NgaySinh.required' => 'Ngày sinh không được để trống',
            'Email.required' => 'Email không được để trống',
            'Email.email' => 'Email không hợp lệ',
            'SDT.required' => 'Số điện thoại không được để trống',
            'SDT.digits' => 'Số điện thoại phải có đúng 10 số',
            'MaCCCD.required' => 'CCCD không được để trống',
            'MaCCCD.digits' => 'CCCD phải có đúng 12 số',
            'MaKhoa.required' => 'Khoa không được để trống',
            'MaNganh.required' => 'Ngành không được để trống'
        ]);

    DB::beginTransaction();

    try {
        // 🔍 Cập nhật thông tin CCCD (bao gồm Ngày cấp và Nơi cấp)
        if (!empty($request->MaCCCD)) {
            CCCD::updateOrCreate(
                ['MaCCCD' => $request->MaCCCD],
                [
                    'NgayCap' => $request->NgayCap ?? null,
                    'NoiCap' => $request->NoiCap ?? null
                ]
            );
        }

        // ===== Xử lý Dynamic Data (Khoa, Ngành) =====
        $maKhoa = $this->findOrCreate(Khoa::class, 'MaKhoa', 'TenKhoa', $request->MaKhoa);
        $maNganh = $this->findOrCreate(Nganh::class, 'MaNganh', 'TenNganh', $request->MaNganh);

        // 🔄 Cập nhật giảng viên
        $updateData = $request->only([
            'TenGV', 'GioiTinh', 'NgaySinh', 'MaCCCD', 'TonGiao', 'SDT',
            'Email', 'NoiSinh', 'HKTT', 'DanToc', 'HocVi', 'HocHam',
            'ChuyenNganh', 'MaNamHoc'
        ]);
        
        $updateData['MaKhoa'] = $maKhoa;
        $updateData['MaNganh'] = $maNganh;

        $gv->update($updateData);


        DB::commit();

        return redirect()->route('admin.giangvien.index')
            ->with('success', 'Cập nhật giảng viên thành công!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage()]);
    }
}

    // ======= XEM CHI TIẾT =======
    public function show($id)
    {
        $gv = GiangVien::with(['khoa', 'nganh', 'taiKhoan', 'cccd', 'detais'])->findOrFail($id);
        
        // Nếu là AJAX request, trả về partial view
        if (request()->ajax()) {
            return view('admin.giangvien.show_partial', compact('gv'));
        }
        
        return view('admin.giangvien.show', compact('gv'));
    }

    // ======= XÓA =======
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $gv = GiangVien::findOrFail($id);
            $maTK = $gv->MaTK; // Lưu MaTK trước khi xóa
            
            // 1. Xóa các bản ghi phân công (PhanCong)
            DB::table('phancong')->where('MaGV', $id)->delete();
            
            // 2. Xóa các bản ghi chấm điểm (ChamDiem)
            DB::table('chamdiem')->where('MaGV', $id)->delete();
            
            // 3. Cập nhật các lớp đang có giảng viên này làm GVCN
            DB::table('lop')->where('MaGV', $id)->update(['MaGV' => null]);
            
            // 4. Cập nhật các đề tài đang có giảng viên này hướng dẫn
            DB::table('detai')->where('MaGV', $id)->update(['MaGV' => null]);
            
            // 5. XÓA GIẢNG VIÊN TRƯỚC (vì GiangVien.MaTK tham chiếu đến TaiKhoan.MaTK)
            $gv->delete();
            
            // 6. Sau đó mới xóa tài khoản
            if ($maTK) {
                TaiKhoan::where('MaTK', $maTK)->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.giangvien.index')
                ->with('success', 'Xóa giảng viên và các dữ liệu liên quan thành công');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.giangvien.index')
                ->with('error', 'Không thể xóa giảng viên: ' . $e->getMessage());
        }
    }


    // ================== IMPORT ==================
    public function import(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,csv']);

        $fileModel = FileHelper::uploadFile($request->file('excel_file'), 'excel');
        $filePath = public_path('img/uploads/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fileModel->path));

        if (!file_exists($filePath)) {
            return back()->with('error', 'Không tìm thấy file: ' . $filePath);
        }

        try {
            $rows = SimpleExcelReader::create($filePath)->getRows();
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi đọc Excel: ' . $e->getMessage());
        }

        $count = 0;
        $errors = [];

        // Lấy số thứ tự lớn nhất hiện tại để tự sinh mã nếu cần
        $lastGV = GiangVien::orderBy('MaGV', 'desc')->first();
        $currentMaxId = $lastGV ? intval(substr($lastGV->MaGV, 2)) : 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $tenGV = trim($row['TenGV'] ?? $row['Họ tên'] ?? '');

                // Nếu không có tên thì bỏ qua
                if (!$tenGV) {
                    $errors[] = "Dòng " . ($index + 2) . ": Thiếu Tên GV";
                    continue;
                }

                // Tự sinh Mã GV (không cần nhập trong file Excel)
                $currentMaxId++;
                $maGV = 'GV' . str_pad($currentMaxId, 3, '0', STR_PAD_LEFT);

                // Xử lý Dynamic Data
                $maKhoa = $this->findOrCreate(Khoa::class, 'MaKhoa', 'TenKhoa', $row['Khoa'] ?? '');
                $maNganh = $this->findOrCreate(Nganh::class, 'MaNganh', 'TenNganh', $row['Nganh'] ?? $row['Ngành'] ?? '');

                // 🛠️ FIX: Tạo CCCD trước để tránh lỗi Foreign Key
                // 🛠️ FIX: Tạo CCCD trước để tránh lỗi Foreign Key
                $cccdVal = isset($row['CCCD']) ? trim($row['CCCD']) : null;
                if (!empty($cccdVal)) {
                    \App\Models\CCCD::firstOrCreate(
                        ['MaCCCD' => $cccdVal],
                        [
                            'NgayCap' => now(),
                            'NoiCap'  => 'Chưa cập nhật'
                        ]
                    );
                }

                $gvData = [
                    'MaGV' => $maGV,
                    'TenGV' => $tenGV,
                    'Email' => $row['Email'] ?? null,
                    'SDT' => $row['SDT'] ?? null,
                    'MaCCCD' => $cccdVal,
                    'NgaySinh' => isset($row['NgaySinh']) ? (
                        $row['NgaySinh'] instanceof \DateTimeInterface 
                            ? $row['NgaySinh']->format('Y-m-d') 
                            : (\Carbon\Carbon::hasFormat($row['NgaySinh'], 'd/m/Y') ? \Carbon\Carbon::createFromFormat('d/m/Y', $row['NgaySinh'])->format('Y-m-d') : null)
                    ) : null,
                    'GioiTinh' => $row['GioiTinh'] ?? null,
                    'MaKhoa' => $maKhoa,
                    'MaNganh' => $maNganh,
                    'HocVi' => $row['HocVi'] ?? null,
                    'HocHam' => $row['HocHam'] ?? null,
                ];

                $gv = GiangVien::updateOrCreate(['MaGV' => $maGV], $gvData);

                // Tạo tài khoản nếu chưa có
                $tk = TaiKhoan::firstOrCreate(
                    ['MaSo' => $maGV],
                    [
                        'MatKhau' => Hash::make($maGV),
                        'VaiTro' => 'GiangVien',
                        'active' => true
                    ]
                );
                
                $gv->update(['MaTK' => $tk->MaTK]);

                $count++;
            }

            DB::commit();

            return redirect()->route('admin.giangvien.index')
                ->with('success', "Import thành công {$count} giảng viên!")
                ->with('warning', count($errors) ? "Có " . count($errors) . " dòng lỗi." : null);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }


    /**
     * Helper: Tìm hoặc Tạo mới record dựa trên ID hoặc Tên
     */
    private function findOrCreate($modelClass, $primaryKey, $nameColumn, $value, $extraData = [])
    {
        if (empty($value)) return null;

        // 1. Check nếu value là ID (số) đã tồn tại
        if (is_numeric($value)) {
            $existsById = $modelClass::where($primaryKey, $value)->exists();
            if ($existsById) return $value;
        }

        // 2. Check nếu value là Tên đã tồn tại
        $recordByName = $modelClass::where($nameColumn, $value)->first();
        if ($recordByName) return $recordByName->$primaryKey;

        // 3. Tạo mới (sử dụng auto-increment)
        $data = array_merge([
            $nameColumn => $value
        ], $extraData);

        $newRecord = $modelClass::create($data);

        return $newRecord->$primaryKey;
    }
}