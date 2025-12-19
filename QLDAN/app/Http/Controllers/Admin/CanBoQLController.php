<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CanBoQL;
use App\Models\CCCD;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use App\Models\GiangVien;
use App\Models\SinhVien;
use App\Models\DeTai;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CanBoQLController extends Controller
{

     public function index()
    {
        $canbos = CanBoQL::with('taikhoan') ->paginate(15); 
        $taikhoans = TaiKhoan::all();
        return view('admin.canbo.index', compact('canbos', 'taikhoans')); 
    } 

    public function create() 
    {
        $taikhoans = TaiKhoan::all(); // Lấy tất cả tài khoản để chọn khi tạo cán bộ
        return view('admin.canbo.create', compact('taikhoans'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'TenCB' => 'required|string|max:200',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:200',
            'SDT' => 'required|digits:10',
            'MaCCCD' => 'required|digits:12'
        ], [
            'TenCB.required' => 'Tên cán bộ không được để trống',
            'GioiTinh.required' => 'Giới tính không được để trống',
            'NgaySinh.required' => 'Ngày sinh không được để trống',
            'Email.required' => 'Email không được để trống',
            'Email.email' => 'Email không hợp lệ',
            'SDT.required' => 'Số điện thoại không được để trống',
            'SDT.digits' => 'Số điện thoại phải có đúng 10 số',
            'MaCCCD.required' => 'CCCD không được để trống',
            'MaCCCD.digits' => 'CCCD phải có đúng 12 số'
        ]);

       
        $lastCB = CanBoQL::orderBy('MaCB', 'desc')->first();// Lấy cán bộ có MaCB lớn nhất hiện tại
        $nextId = $lastCB ? intval(substr($lastCB->MaCB, 2)) + 1 : 1; // Lấy số thứ tự tiếp theo
        $maCB = 'CB' . str_pad($nextId, 3, '0', STR_PAD_LEFT); // Tạo MaCB mới

        $data = $request->only([ // Lấy dữ liệu từ request
            'TenCB','GioiTinh','NgaySinh','MaCCCD','TonGiao','SDT',
            'Email','NoiSinh','HKTT','DanToc','HocVi','HocHam','ChuyenNganh'
        ]);
        $data['MaCB'] = $maCB;

        // Tự động tạo CCCD nếu chưa tồn tại
        if ($request->MaCCCD && !CCCD::where('MaCCCD', $request->MaCCCD)->exists()) {
            CCCD::create([
                'MaCCCD' => $request->MaCCCD,
                'NgayCap' => $request->NgayCap ?? null,
                'NoiCap' => $request->NoiCap ?? null
            ]);
        }

        // Create CanBoQL
        $cb = CanBoQL::create($data);

        // Create TaiKhoan
        $tk = TaiKhoan::create([
            'MaSo' => $maCB,
            'MatKhau' => bcrypt($maCB), // Password same as MaCB
            'VaiTro' => 'CanBo', // Assuming 'CanBo' is the correct role
            'active' => true
        ]);

        $cb->update(['MaTK' => $tk->MaTK]); // Cập nhật MaTK vào CanBoQL

        return redirect()->route('admin.canbo.index')->with('success', "Thêm cán bộ thành công! Mã: $maCB, Mật khẩu: $maCB");
    }

    public function edit(CanBoQL $canbo) //hiển thị form chỉnh sửa cán bộ
    {
        $taikhoans = TaiKhoan::all();
        return view('admin.canbo.edit', compact('canbo','taikhoans'));
    }

    public function update(Request $request, CanBoQL $canbo) // xử lý cập nhật cán bộ
    {
        $request->validate([
            'TenCB' => 'required|string|max:200',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'NgaySinh' => 'required|date',
            'Email' => 'required|email|max:200',
            'SDT' => 'required|digits:10',
            'MaCCCD' => 'required|digits:12'
        ], [
            'TenCB.required' => 'Tên cán bộ không được để trống',
            'GioiTinh.required' => 'Giới tính không được để trống',
            'NgaySinh.required' => 'Ngày sinh không được để trống',
            'Email.required' => 'Email không được để trống',
            'Email.email' => 'Email không hợp lệ',
            'SDT.required' => 'Số điện thoại không được để trống',
            'SDT.digits' => 'Số điện thoại phải có đúng 10 số',
            'MaCCCD.required' => 'CCCD không được để trống',
            'MaCCCD.digits' => 'CCCD phải có đúng 12 số'
        ]);

        // Tự động tạo CCCD nếu chưa tồn tại
        if ($request->MaCCCD && !CCCD::where('MaCCCD', $request->MaCCCD)->exists()) {
            CCCD::create([
                'MaCCCD' => $request->MaCCCD,
                'NgayCap' => $request->NgayCap ?? null,
                'NoiCap' => $request->NoiCap ?? null
            ]);
        }

        $canbo->update($request->only([ 
            'TenCB','GioiTinh','NgaySinh','MaCCCD','TonGiao','SDT',
            'Email','NoiSinh','HKTT','DanToc','HocVi','HocHam','ChuyenNganh','MaTK'
        ]));

        return redirect()->route('admin.canbo.index')->with('success','Cập nhật cán bộ thành công');
    }


    public function destroy(CanBoQL $canbo)
    {
        try {
            DB::beginTransaction(); // Bắt đầu giao dịch
            
            $maTK = $canbo->MaTK; // Lưu MaTK trước khi xóa
            $maCB = $canbo->MaCB; // Lưu MaCB để xóa các bản ghi liên quan
            
            // 1. Xóa các bản ghi phân công (PhanCong)
            DB::table('phancong')->where('MaCB', $maCB)->delete();
            
            // 2. Xóa các thông báo do cán bộ này đăng
            DB::table('thongbao')->where('MaCB', $maCB)->delete();
            
            // 3. Cập nhật các lớp đang có cán bộ này quản lý
            DB::table('lop')->where('MaCB', $maCB)->update(['MaCB' => null]);
            
            // 4. Cập nhật các đề tài đang có cán bộ này quản lý
            DB::table('detai')->where('MaCB', $maCB)->update(['MaCB' => null]);
            
            // 5. XÓA CÁN BỘ TRƯỚC (vì CanBoQL.MaTK tham chiếu đến TaiKhoan.MaTK)
            $canbo->delete();
            
            // 6. Sau đó mới xóa tài khoản
            if ($maTK) {
                TaiKhoan::where('MaTK', $maTK)->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.canbo.index')
                ->with('success','Xóa cán bộ và các dữ liệu liên quan thành công');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.canbo.index')
                ->with('error', 'Không thể xóa cán bộ: ' . $e->getMessage());
        }
    }

    // ================== EXPORT ==================
    public function export()
    {
        $filePath = storage_path('app/public/canbo_export.xlsx');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader(['MaCB', 'TenCB', 'Email', 'SDT', 'CCCD', 'NgaySinh', 'GioiTinh', 'HocVi', 'HocHam', 'ChuyenNganh']);

        $canbos = CanBoQL::all(); 

        foreach ($canbos as $cb) {
            $writer->addRow([
                $cb->MaCB,
                $cb->TenCB,
                $cb->Email,
                $cb->SDT,
                $cb->MaCCCD,
                $cb->NgaySinh,
                $cb->GioiTinh,
                $cb->HocVi,
                $cb->HocHam,
                $cb->ChuyenNganh
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // ================== IMPORT ==================
    public function import(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,csv']); 

        $fileModel = FileHelper::uploadFile($request->file('excel_file'), 'excel'); //
        $filePath = public_path('img/uploads/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fileModel->path));

        if (!file_exists($filePath)) {
            return back()->with('error', 'Không tìm thấy file');
        }

        try {
            $rows = SimpleExcelReader::create($filePath)->getRows();
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi đọc Excel: ' . $e->getMessage());
        }

        $count = 0;
        $errors = [];

        // Lấy số thứ tự lớn nhất hiện tại để tự sinh mã
        $lastCB = CanBoQL::orderBy('MaCB', 'desc')->first();
        $currentMaxId = $lastCB ? intval(substr($lastCB->MaCB, 2)) : 0; 

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $tenCB = trim($row['TenCB'] ?? $row['Họ tên'] ?? '');

                if (!$tenCB) {
                    $errors[] = "Dòng " . ($index + 2) . ": Thiếu Tên Cán bộ";
                    continue;
                }

                // Tự sinh MaCB
                $currentMaxId++;
                $maCB = 'CB' . str_pad($currentMaxId, 3, '0', STR_PAD_LEFT);

                // Xử lý CCCD
                $cccdVal = isset($row['CCCD']) ? trim($row['CCCD']) : null;
                if (!empty($cccdVal)) {
                    CCCD::firstOrCreate(
                        ['MaCCCD' => $cccdVal],
                        [
                            'NgayCap' => now(),
                            'NoiCap'  => 'Chưa cập nhật'
                        ]
                    );
                }

                $cbData = [ 
                    'MaCB' => $maCB,
                    'TenCB' => $tenCB,
                    'Email' => $row['Email'] ?? null,
                    'SDT' => $row['SDT'] ?? null,
                    'MaCCCD' => $cccdVal,
                    'NgaySinh' => isset($row['NgaySinh']) ? (
                        $row['NgaySinh'] instanceof \DateTimeInterface 
                            ? $row['NgaySinh']->format('Y-m-d') 
                            : (\Carbon\Carbon::hasFormat($row['NgaySinh'], 'd/m/Y') ? \Carbon\Carbon::createFromFormat('d/m/Y', $row['NgaySinh'])->format('Y-m-d') : null)
                    ) : null,
                    'GioiTinh' => $row['GioiTinh'] ?? null,
                    'HocVi' => $row['HocVi'] ?? null,
                    'HocHam' => $row['HocHam'] ?? null,
                    'ChuyenNganh' => $row['ChuyenNganh'] ?? null,
                ];

                $cb = CanBoQL::create($cbData);

                // Tạo tài khoản
                $tk = TaiKhoan::create([
                    'MaSo' => $maCB,
                    'MatKhau' => Hash::make($maCB),
                    'VaiTro' => 'CanBo',
                    'active' => true
                ]);

                $cb->update(['MaTK' => $tk->MaTK]);

                $count++;
            }

            DB::commit(); 

            return redirect()->route('admin.canbo.index')
                ->with('success', "Import thành công {$count} cán bộ!")
                ->with('warning', count($errors) ? "Có " . count($errors) . " dòng lỗi." : null);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }

    // ================== TEMPLATE ==================
    public function downloadTemplate() 
    {
        $headers = ['TenCB', 'Email', 'SDT', 'MaCCCD', 'NgaySinh', 'GioiTinh', 'TonGiao', 'DanToc', 'HocVi', 'HocHam', 'ChuyenNganh', 'NoiSinh', 'HKTT'];
        $exampleData = ['Nguyễn Văn A', 'nguyenvana@example.com', '0123456789', '001234567890', '1980-01-15', 'Nam', 'Không', 'Kinh', 'Tiến sĩ', 'Phó giáo sư', 'Công nghệ thông tin', 'Hà Nội', 'Số 1, Đường ABC, Hà Nội'];

        $writer = SimpleExcelWriter::streamDownload('CanBo_Template.xlsx')
            ->addHeader($headers)
            ->addRow($exampleData);

        return $writer->toBrowser();
    }
}