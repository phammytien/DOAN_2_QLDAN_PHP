<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\Lop;
use App\Models\NamHoc;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use App\Models\DeTai;
use App\Models\BaoCao;
use App\Models\ChamDiem;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SinhVienController extends Controller
{
    /** 📄 Danh sách (Phân theo lớp) */
    public function index(Request $request)
    {
        // Lấy danh sách lớp, kèm sinh viên
        $query = Lop::with(['sinhviens' => function ($q) use ($request) {
            // Filter sinh viên trong lớp (nếu có search)
            if ($request->filled('keyword')) {
                $q->where(function($sub) use ($request) {
                    $sub->where('TenSV', 'like', '%' . $request->keyword . '%')
                        ->orWhere('MaSV', 'like', '%' . $request->keyword . '%');
                });
            }
            // Filter theo trạng thái
            if ($request->filled('trangthai')) {
                $q->where('TrangThai', $request->trangthai);
            }
        }, 'khoa', 'nganh']);

        // Filter theo lớp (nếu chọn dropdown)
        if ($request->filled('lop')) {
            $query->where('MaLop', $request->lop);
        }

        // Filter theo ngành
        if ($request->filled('nganh')) {
            $query->whereHas('nganh', function ($q) use ($request) {
                $q->where('TenNganh', 'like', '%' . $request->nganh . '%');
            });
        }

        // Nếu có từ khóa tìm kiếm, chỉ lấy những lớp CÓ sinh viên thỏa mãn
        if ($request->filled('keyword')) {
            $query->whereHas('sinhviens', function ($q) use ($request) {
                $q->where('TenSV', 'like', '%' . $request->keyword . '%')
                  ->orWhere('MaSV', 'like', '%' . $request->keyword . '%');
            });
        }

        // Nếu có filter trạng thái, chỉ lấy những lớp CÓ sinh viên thỏa mãn
        if ($request->filled('trangthai')) {
            $query->whereHas('sinhviens', function ($q) use ($request) {
                $q->where('TrangThai', $request->trangthai);
            });
        }

        $lops = $query->withCount('sinhviens')->paginate(10)->appends($request->all());

        // Dropdown data
        $khoas = Khoa::all();
        $nganhs = Nganh::all();
        $allLops = Lop::all(); // Cho dropdown filter
        $namhocs = NamHoc::all();

        return view('admin.sinhvien.index', compact('lops', 'khoas', 'nganhs', 'allLops', 'namhocs'));
    }

    /** 💾 Lưu mới + tạo tài khoản + gán MaTK */
    public function store(Request $request)
    {
        $request->validate([
            'TenSV' => 'required|string|max:200',
            'MaCCCD' => 'required|string|size:12|regex:/^[0-9]{12}$/|unique:SinhVien,MaCCCD',
            'SDT' => 'required|string|size:10|regex:/^[0-9]{10}$/',
            'Email' => 'required|email|max:200',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'MaKhoa' => 'required',
            'MaNganh' => 'required',
            'MaLop' => 'required',
            'MaNamHoc' => 'required|exists:NamHoc,MaNamHoc',
        ], [
            'TenSV.required' => 'Tên sinh viên không được để trống',
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
            'MaNganh.required' => 'Ngành không được để trống',
            'MaLop.required' => 'Lớp không được để trống',
            'MaNamHoc.required' => 'Năm học không được để trống'
        ]);

        try {
            // ===== Xử lý Dynamic Data (Khoa, Ngành, Lớp) =====
            $maKhoa = null;
            $maNganh = null;
            $maLop = null;
            
            // Chỉ xử lý nếu có giá trị
            if (!empty($request->MaKhoa)) {
                $maKhoa = $this->findOrCreate(Khoa::class, 'MaKhoa', 'TenKhoa', $request->MaKhoa);
            }
            
            if (!empty($request->MaNganh)) {
                $maNganh = $this->findOrCreate(Nganh::class, 'MaNganh', 'TenNganh', $request->MaNganh);
            }
            
            // Lớp cần MaKhoa và MaNganh để tạo mới (hoặc ít nhất một trong hai)
            if (!empty($request->MaLop)) {
                $extraData = [];
                if ($maKhoa) $extraData['MaKhoa'] = $maKhoa;
                if ($maNganh) $extraData['MaNganh'] = $maNganh;
                
                $maLop = $this->findOrCreate(Lop::class, 'MaLop', 'TenLop', $request->MaLop, $extraData);
            }

            // ===== Tạo/kiểm tra bảng CCCD =====
            if (!empty($request->MaCCCD)) {
                $maCCCD = trim($request->MaCCCD);
                \App\Models\CCCD::updateOrCreate(
                    ['MaCCCD' => $maCCCD],
                    [
                        'NgayCap' => $request->NgayCap ?? now(),
                        'NoiCap'  => $request->NoiCap ?? 'Chưa cập nhật'
                    ]
                );
            }

            /** @var array $svData */
            $svData = (array) $request->only([
                'TenSV','GioiTinh','NgaySinh','MaCCCD','TonGiao','SDT',
                'Email','NoiSinh','HKTT','DanToc','BacDaoTao','MaNamHoc','TrangThai'
            ]);
            
            // Gán các ID đã xử lý
            $svData['MaKhoa'] = $maKhoa;
            $svData['MaNganh'] = $maNganh;
            $svData['MaLop'] = $maLop;

            // Tạo sinh viên
            $sinhvien = SinhVien::create($svData);

            // Tạo tài khoản
            $taikhoan = TaiKhoan::create([
                'MaSo'   => $sinhvien->MaSV,
                'MatKhau'=> bcrypt($sinhvien->MaSV),
                'VaiTro' => 'SinhVien',
            ]);

            // GÁN MaTK vào bảng sinhvien
            $sinhvien->update(['MaTK' => $taikhoan->MaTK]);

            // Lấy thông tin để hiển thị trong thông báo
            $tenLop = Lop::find($maLop)->TenLop ?? 'Chưa xác định';
            $tenKhoa = Khoa::find($maKhoa)->TenKhoa ?? 'Chưa xác định';
            $tenNganh = Nganh::find($maNganh)->TenNganh ?? 'Chưa xác định';

            $successMessage = "✅ Thêm sinh viên thành công!<br><br>" .
                              "<strong>📋 Thông tin:</strong><br>" .
                              "• Mã SV: {$sinhvien->MaSV}<br>" .
                              "• Họ tên: {$request->TenSV}<br>" .
                              "• Lớp: {$tenLop}<br>" .
                              "• Khoa: {$tenKhoa}<br>" .
                              "• Ngành: {$tenNganh}<br>" .
                              "• Email: {$request->Email}<br><br>" .
                              "<strong>🔑 Tài khoản đăng nhập:</strong><br>" .
                              "• Tên đăng nhập: {$sinhvien->MaSV}<br>" .
                              "• Mật khẩu mặc định: {$sinhvien->MaSV}";

            if ($request->has('redirect_to')) {
                return redirect($request->redirect_to)->with('success', $successMessage);
            }

            return redirect()->route('admin.sinhvien.index')
                ->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating student: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'])->withInput();
        }
    }

    /** ✏️ Lấy thông tin sinh viên để sửa (JSON) */
    public function edit($id)
    {
        $sv = SinhVien::with(['lop', 'nganh', 'khoa'])->findOrFail($id);
        
        // Convert to array to avoid Carbon serialization
        $svArray = $sv->toArray();
        
        // Format NgaySinh to Y-m-d string to avoid timezone issues
        if (isset($svArray['NgaySinh']) && $sv->NgaySinh) {
            $svArray['NgaySinh'] = $sv->NgaySinh->format('Y-m-d');
        }
        
        return response()->json($svArray);
    }

    /** 🔄 Cập nhật (KHÔNG CHO SỬA MaTK) */
    public function update(Request $request, $id)
    {
        $sv = SinhVien::findOrFail($id);

        $request->validate([
            'TenSV' => 'required|string|max:200',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:200',
            'SDT' => 'required|digits:10',
            'MaCCCD' => 'required|digits:12',
            'MaLop' => 'required',
            'MaKhoa' => 'required',
            'MaNganh' => 'required',
            'MaNamHoc' => 'required'
        ], [
            'TenSV.required' => 'Tên sinh viên không được để trống',
            'GioiTinh.required' => 'Giới tính không được để trống',
            'NgaySinh.required' => 'Ngày sinh không được để trống',
            'Email.required' => 'Email không được để trống',
            'Email.email' => 'Email không hợp lệ',
            'SDT.required' => 'Số điện thoại không được để trống',
            'SDT.digits' => 'Số điện thoại phải có đúng 10 số',
            'MaCCCD.required' => 'CCCD không được để trống',
            'MaCCCD.digits' => 'CCCD phải có đúng 12 số',
            'MaLop.required' => 'Lớp không được để trống',
            'MaKhoa.required' => 'Khoa không được để trống',
            'MaNganh.required' => 'Ngành không được để trống',
            'MaNamHoc.required' => 'Năm học không được để trống'
        ]);

        DB::beginTransaction();

        try {
            // 🔍 Kiểm tra CCCD tồn tại chưa
            $cccd = DB::table('cccd')->where('MaCCCD', $request->MaCCCD)->first();
            if (!$cccd) {
                // ➕ Tự thêm mới tránh lỗi FK
                DB::table('cccd')->insert(['MaCCCD' => $request->MaCCCD]);
            }

            // ===== Xử lý Dynamic Data (Khoa, Ngành, Lớp) =====
            $maKhoa = null;
            $maNganh = null;
            $maLop = null;
            
            if (!empty($request->MaKhoa)) {
                $maKhoa = $this->findOrCreate(Khoa::class, 'MaKhoa', 'TenKhoa', $request->MaKhoa);
            }
            
            if (!empty($request->MaNganh)) {
                $maNganh = $this->findOrCreate(Nganh::class, 'MaNganh', 'TenNganh', $request->MaNganh);
            }
            
            if (!empty($request->MaLop)) {
                $extraData = [];
                if ($maKhoa) $extraData['MaKhoa'] = $maKhoa;
                if ($maNganh) $extraData['MaNganh'] = $maNganh;
                
                $maLop = $this->findOrCreate(Lop::class, 'MaLop', 'TenLop', $request->MaLop, $extraData);
            }

            /** @var array $updateData */
            $updateData = (array) $request->only([
                'TenSV','GioiTinh','NgaySinh','MaCCCD','TonGiao','SDT',
                'Email','NoiSinh','HKTT','DanToc','BacDaoTao',
                'MaNamHoc','TrangThai'
            ]);
            
            $updateData['MaKhoa'] = $maKhoa;
            $updateData['MaNganh'] = $maNganh;
            $updateData['MaLop'] = $maLop;

            $sv->update($updateData);

            DB::commit();

            // Lấy thông tin để hiển thị
            $tenLop = Lop::find($maLop)->TenLop ?? 'Chưa xác định';

            $successMessage = "✅ Cập nhật sinh viên thành công!<br><br>" .
                              "<strong>📋 Thông tin đã cập nhật:</strong><br>" .
                              "• Mã SV: {$sv->MaSV}<br>" .
                              "• Họ tên: {$request->TenSV}<br>" .
                              "• Lớp: {$tenLop}<br>" .
                              "• Email: {$request->Email}";

            return redirect()->route('admin.sinhvien.index')
                ->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating student: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Lỗi: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'])->withInput();
        }
    }

    /** 🗑️ Xóa */
    public function destroy($id)
    {
        $sv = SinhVien::findOrFail($id);
        
        // Lưu thông tin trước khi xóa
        $tenSV = $sv->TenSV;
        $maSV = $sv->MaSV;
        $maTK = $sv->MaTK;
        
        // Xóa sinh viên TRƯỚC (để tránh lỗi foreign key)
        $sv->delete();
        
        // Sau đó xóa tài khoản liên quan nếu có
        if ($maTK) {
            TaiKhoan::where('MaTK', $maTK)->delete();
        }
        
        $successMessage = "✅ Xóa sinh viên thành công!<br><br>" .
                          "<strong>📋 Thông tin đã xóa:</strong><br>" .
                          "• Mã SV: {$maSV}<br>" .
                          "• Họ tên: {$tenSV}<br>" .
                          "• Tài khoản liên quan đã được xóa";
        
        return redirect()->route('admin.sinhvien.index')
            ->with('success', $successMessage);
    }

    /** Chi tiết đề tài - Trả về JSON cho AJAX */
    public function detai($MaSV)
    {
        $sv = SinhVien::findOrFail($MaSV);
        $deTai = DeTai::whereHas('sinhviens', fn($q) => $q->where('SinhVien.MaSV', $MaSV))
                      ->with(['giangVien', 'canBo', 'namHoc'])
                      ->first();

        $baoCaos = $deTai ? BaoCao::where('MaDeTai', $deTai->MaDeTai)->with('fileBaoCao')->get() : collect();
        $diems = $deTai ? ChamDiem::where('MaDeTai', $deTai->MaDeTai)->with('giangvien')->get() : collect();
        $tiendos = $deTai ? \App\Models\TienDo::where('MaDeTai', $deTai->MaDeTai)->with('fileCode')->orderBy('Deadline')->get() : collect();

        // Trả về JSON nếu là AJAX request
        if (request()->ajax()) {
            return response()->json([
                'sv' => $sv,
                'deTai' => $deTai,
                'baoCaos' => $baoCaos,
                'diems' => $diems,
                'tiendos' => $tiendos
            ]);
        }

        // Trả về view nếu không phải AJAX
        return view('admin.sinhvien.detai', compact('sv','deTai','baoCaos','diems'));
    }

    /** ⬇️ Export */
    public function export()
    {
        $filePath = storage_path('app/public/sinhvien_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['MaSV','TenSV','GioiTinh','NgaySinh','Email','SDT','Lớp','Ngành','Khoa','Năm học','Trạng thái']);

        $sinhviens = SinhVien::with(['lop','nganh','khoa','namhoc'])->get();

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
                $sv->khoa->TenKhoa ?? '',
                $sv->namhoc->TenNamHoc ?? '',
                $sv->TrangThai ?? ''
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /** ⬇️ Export theo Lớp */
    public function exportByClass($maLop)
    {
        $lop = Lop::with(['khoa', 'nganh'])->findOrFail($maLop);
        $filePath = storage_path('app/public/sinhvien_lop_' . $lop->TenLop . '.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['Mã SV','Tên SV','Giới tính','Ngày sinh','Email','SĐT','Lớp','Ngành','Khoa','Năm học','Trạng thái']);

        $sinhviens = SinhVien::where('MaLop', $maLop)
            ->with(['lop','nganh','khoa','namhoc'])
            ->get();

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
                $sv->khoa->TenKhoa ?? '',
                $sv->namhoc->TenNamHoc ?? '',
                $sv->TrangThai ?? ''
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    /** ⬆️ Import */
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
        $lopStats = []; // Track students by class

        // Lấy số thứ tự lớn nhất hiện tại để tự sinh mã nếu cần
        $lastSV = SinhVien::orderBy('MaSV', 'desc')->first();
        $currentMaxId = $lastSV ? intval(substr($lastSV->MaSV, 0)) : 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $tenSV = trim($row['Tên sinh viên'] ?? $row['Họ tên'] ?? '');

                // Nếu không có tên thì bỏ qua
                if (!$tenSV) {
                    $lineNumber = (int)$index + 2;
                    $errors[] = "Dòng {$lineNumber}: Thiếu Tên sinh viên.";
                    continue;
                }

                // Tự sinh Mã SV nếu không có trong file
                $maSV = trim($row['Mã SV'] ?? '');
                if (!$maSV) {
                    $currentMaxId++;
                    $maSV = str_pad($currentMaxId, 10, '0', STR_PAD_LEFT);
                }

                // Xử lý Dynamic Data
                $maKhoa = $this->findOrCreate(Khoa::class, 'MaKhoa', 'TenKhoa', $row['Khoa'] ?? '');
                $maNganh = $this->findOrCreate(Nganh::class, 'MaNganh', 'TenNganh', $row['Ngành'] ?? '');
                
                $tenLop = $row['Lớp'] ?? '';
                $maLop = $this->findOrCreate(Lop::class, 'MaLop', 'TenLop', $tenLop, [
                    'MaKhoa' => $maKhoa,
                    'MaNganh' => $maNganh
                ]);
                
                // Track statistics by class
                if (!isset($lopStats[$tenLop])) {
                    $lopStats[$tenLop] = 0;
                }
                $lopStats[$tenLop]++;
                
                $namhoc = NamHoc::where('TenNamHoc', $row['Năm học'] ?? '')->first();

                // Xử lý ngày sinh với nhiều định dạng
                $ngaySinh = null;
                if (isset($row['Ngày sinh']) && !empty($row['Ngày sinh'])) {
                    $ngaySinhRaw = $row['Ngày sinh'];
                    
                    // Nếu là DateTime object từ Excel
                    if ($ngaySinhRaw instanceof \DateTimeInterface) {
                        $ngaySinh = $ngaySinhRaw->format('Y-m-d');
                    } 
                    // Nếu là string, thử parse nhiều định dạng
                    else {
                        $ngaySinhStr = trim($ngaySinhRaw);
                        
                        // Thử định dạng d/m/Y (14/05/2004)
                        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $ngaySinhStr, $matches)) {
                            $ngaySinh = sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
                        }
                        // Thử định dạng Y-m-d (2004-05-14)
                        elseif (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $ngaySinhStr)) {
                            $ngaySinh = date('Y-m-d', strtotime($ngaySinhStr));
                        }
                        // Thử định dạng d-m-Y (14-05-2004)
                        elseif (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $ngaySinhStr, $matches)) {
                            $ngaySinh = sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
                        }
                        // Thử strtotime như fallback
                        else {
                            $timestamp = strtotime($ngaySinhStr);
                            if ($timestamp !== false) {
                                $ngaySinh = date('Y-m-d', $timestamp);
                            }
                        }
                    }
                }

                /** @var array $data */
                $data = (array) [
                    'MaSV' => $maSV,
                    'TenSV' => $tenSV,
                    'GioiTinh' => $row['Giới tính'] ?? null,
                    'NgaySinh' => $ngaySinh,
                    'Email' => $row['Email'] ?? null,
                    'SDT' => $row['SDT'] ?? null,
                    'TrangThai' => $row['Trạng thái'] ?? 'Đang học',
                    'MaLop' => $maLop,
                    'MaNganh' => $maNganh,
                    'MaKhoa' => $maKhoa,
                    'MaNamHoc' => $namhoc->MaNamHoc ?? null,
                ];

                $sinhvien = SinhVien::updateOrCreate(['MaSV' => $maSV], $data);

                $tk = TaiKhoan::updateOrCreate(
                    ['MaSo' => $maSV],
                    [
                        'MatKhau' => bcrypt($maSV),
                        'VaiTro' => 'SinhVien'
                    ]
                );

                $sinhvien->update(['MaTK' => $tk->MaTK]);

                $count++;
            }

            DB::commit();

            // Build detailed success message
            $successMessage = "✅ Import thành công {$count} sinh viên!<br><br>" .
                              "<strong>📊 Thống kê theo lớp:</strong><br>";
            
            foreach ($lopStats as $tenLop => $soLuong) {
                $successMessage .= "• {$tenLop}: {$soLuong} sinh viên<br>";
            }

            return redirect()->route('admin.sinhvien.index')
                ->with('success', $successMessage)
                ->with('warning', count($errors) ? "⚠️ Có " . count($errors) . " dòng lỗi." : null);

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