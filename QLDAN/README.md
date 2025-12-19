
## Yêu cầu hệ thống

-   PHP >= 8.1
-   Composer
-   MySQL/MariaDB
-   Node.js & npm (nếu muốn build frontend)

## Cài đặt

1. Clone dự án:
    ```bash
    git clone 
    cd QLDAN
    ```
2. Cấu hình Php
   Đảm bảo các extension sau đã được bật trong file `php.ini`:
   (nêu dùng xampp thì bỏ quả bươc này)
   Chuyển từ

```ini
;extension=mysqli
;extension=pdo_mysql
;extension=zip
```

```ini
extension=mysqli
extension=pdo_mysql
extension=zip
```

Nếu sử dụng 7-Zip cho các chức năng nén/giải nén, hãy đảm bảo bạn đã cài đặt 7-Zip trên hệ thống và thêm đường dẫn của nó vào biến môi trường `PATH` (Windows).  
Sau khi chỉnh sửa, hãy khởi động lại Apache hoặc PHP-FPM. 3. Cài đặt các package PHP:
`bash
    composer install
    ` 4. Tạo file `.env` từ file mẫu và cấu hình thông tin database:
`bash
    cp .env.example .env
    # Hoặc trên Windows: copy .env.example .env
    ` 5. Tạo key ứng dụng:
`bash
    php artisan key:generate
    ` 6. Chạy migration và seed dữ liệu mẫu:
`bash
    php artisan migrate
    ` 7. Khởi động server:
`bash
    php artisan serve
    `

## Cấu trúc thư mục

-   `app/Models/` - Các model Eloquent 
-   `app/Http/Controllers/` - Controller xử lý logic request
-   `database/migrations/` - Các file migration tạo bảng
-   `database/seeders/` - Seeder dữ liệu mẫu
-   `resources/views/` - Giao diện Blade
-   `public/` - Thư mục public, entrypoint index.php
-   `routes/web.php` - Định nghĩa route web

## Sử dụng

-   Truy cập trang chủ tại: `http://localhost:8000`
-   Đăng nhập/đăng ký tài khoản để sử dụng các chức năng quản lý
-   Quản lý sản phẩm, đơn hàng, người dùng, đánh giá, upload file, v.v.

## Đóng góp

Mọi đóng góp, báo lỗi hoặc đề xuất tính năng mới đều được hoan nghênh! Vui lòng tạo issue hoặc pull request.



## License

Dự án sử dụng giấy phép [MIT](https://opensource.org/licenses/MIT).



Chức năng quản lý của Admin 
1. 👨‍💻 Quản lý tài khoản Danh sách tất cả tài khoản (sinh viên, giảng viên, admin) Thêm / sửa / xóa tài khoản Cấp quyền (đặt vai trò: sinh viên, giảng viên, admin) Khóa / mở khóa tài khoản 
2. 🧑‍🏫 Quản lý giảng viên Thêm / sửa / xóa thông tin giảng viên Gán giảng viên hướng dẫn / phản biện cho đề tài Xem danh sách giảng viên và số lượng đề tài họ đang hướng dẫn 
3. 🧑‍🎓 Quản lý sinh viên Danh sách sinh viên (lọc theo lớp, ngành) Cập nhật thông tin sinh viên Xem đề tài đã đăng ký, điểm số, trạng thái báo cáo 
4. 📚 Quản lý đề tài Danh sách đề tài (phân loại theo trạng thái: chờ duyệt / đang thực hiện / hoàn thành) Duyệt hoặc từ chối đề tài sinh viên đăng ký Thêm / sửa / xóa đề tài Gán giảng viên hướng dẫn 
5. 🧾 Quản lý báo cáo / tiến độ Xem báo cáo tiến độ của sinh viên Gửi phản hồi / nhận xét Duyệt hoặc yêu cầu chỉnh sửa báo cáo 
6. 🧮 Quản lý chấm điểm Xem điểm sinh viên từ giảng viên hướng dẫn và phản biện Duyệt điểm cuối cùng / tính điểm trung bình / lưu kết quả 
7. 📨 Quản lý thông báo Gửi thông báo đến từng vai trò (sinh viên, giảng viên, tất cả) Quản lý danh sách thông báo (thêm / sửa / xóa) Cho phép giảng viên hoặc sinh viên xem các thông báo liên quan 
8. 📂 Quản lý file / tài liệu Xem và quản lý các file sinh viên upload (báo cáo, đề tài, tài liệu) Xóa hoặc tải xuống khi cần 
9. ⚙️ Cấu hình hệ thống Quản lý năm học, học kỳ, thời gian đăng ký đề tài, nộp báo cáo,... Sao lưu / khôi phục dữ liệu Quản lý quyền truy cập và hoạt động hệ thống






BaoCaoThongKeController.php

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\NamHoc;
use App\Models\SinhVien;
use App\Models\DeTai;
use App\Models\BaoCao;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DiemTheoLopExport;

class BaoCaoThongKeController extends Controller
{
    public function index(Request $request)
    {
        $lops = Lop::orderBy('TenLop')->get();
        $namhocs = NamHoc::orderBy('TenNamHoc', 'desc')->get();
        
        $sinhviens = null;
        $lopName = '';
        
        if ($request->filled('lop')) {
            $query = SinhVien::where('MaLop', $request->lop)
                ->with(['deTai', 'baoCao.fileBaoCao']);
            
            // Lấy tên lớp
            $lop = Lop::find($request->lop);
            $lopName = $lop ? $lop->TenLop : '';
            
            // Lọc theo năm học nếu có
            if ($request->filled('namhoc')) {
                $query->whereHas('deTai', function($q) use ($request) {
                    $q->where('MaNamHoc', $request->namhoc);
                });
            }
            
            $sinhviens = $query->get();
            
            // Tính điểm trung bình cho mỗi sinh viên
            foreach ($sinhviens as $sv) {
                // Lấy đề tài đầu tiên (vì deTai() trả về collection)
                $deTaiFirst = $sv->deTai->first();
                
                if ($deTaiFirst) {
                    $sv->deTai = $deTaiFirst; // Gán lại để dùng như object
                    
                    // Lấy điểm từ bảng ChamDiem
                    // Lưu ý: Đây là lấy điểm của một lần chấm bất kỳ. 
                    // Nếu cần điểm trung bình hoặc điểm cụ thể (GVHD/GVPB), cần logic phức tạp hơn.
                    $diem = \DB::table('ChamDiem')
                        ->where('MaDeTai', $deTaiFirst->MaDeTai)
                        ->orderBy('Diem', 'desc') // Lấy điểm cao nhất nếu có nhiều điểm (tạm thời)
                        ->first();
                    
                    if ($diem && isset($diem->Diem)) {
                        $sv->diemTrungBinh = $diem->Diem;
                    } else {
                        $sv->diemTrungBinh = null;
                    }
                    
                    // Lấy báo cáo mới nhất
                    $sv->baoCao = BaoCao::where('MaSV', $sv->MaSV)
                        ->where('MaDeTai', $deTaiFirst->MaDeTai)
                        ->with('fileBaoCao')
                        ->orderBy('NgayNop', 'desc')
                        ->first();
                } else {
                    $sv->deTai = null;
                    $sv->diemTrungBinh = null;
                    $sv->baoCao = null;
                }
            }
        }

        
        return view('admin.baocao.thongke', compact('lops', 'namhocs', 'sinhviens', 'lopName'));
    }
    
    public function exportDiem(Request $request)
    {
        $lopId = $request->get('lop');
        $namhocId = $request->get('namhoc');
        
        if (!$lopId) {
            return back()->with('error', 'Vui lòng chọn lớp để xuất file');
        }
        
        $lop = Lop::find($lopId);
        $fileName = 'Diem_' . ($lop ? $lop->TenLop : 'Lop') . '_' . date('Y-m-d') . '.xls';
        
        // Logic lấy dữ liệu (giống index)
        $query = SinhVien::where('MaLop', $lopId)
            ->with(['deTai', 'baoCao.fileBaoCao'])
            ->orderBy('TenSV');
        
        if ($namhocId) {
            $query->whereHas('deTai', function($q) use ($namhocId) {
                $q->where('MaNamHoc', $namhocId);
            });
        }
        
        $sinhviens = $query->get();
        
        foreach ($sinhviens as $sv) {
            $deTaiFirst = $sv->deTai->first();
            
            if ($deTaiFirst) {
                $sv->deTai = $deTaiFirst;
                
                $diem = \DB::table('ChamDiem')
                    ->where('MaDeTai', $deTaiFirst->MaDeTai)
                    ->orderBy('Diem', 'desc')
                    ->first();
                
                $sv->diemTrungBinh = ($diem && isset($diem->Diem)) ? $diem->Diem : null;
            } else {
                $sv->deTai = null;
                $sv->diemTrungBinh = null;
            }
        }
        
        return response(view('admin.baocao.export_diem', compact('sinhviens')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}


NienKhoaController.php


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NienKhoa;
use Illuminate\Http\Request;

class NienKhoaController extends Controller
{
    public function index()
    {
        $nienkhoas = NienKhoa::all();
        return view('admin.nienkhoa.index', compact('nienkhoas'));
    }

    public function create()
    {
        return view('admin.nienkhoa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenNienKhoa' => 'nullable|string|max:100',
            'NamBatDau' => 'nullable|integer',
            'NamKetThuc' => 'nullable|integer'
        ]);

        NienKhoa::create($request->only('TenNienKhoa','NamBatDau','NamKetThuc'));
        return redirect()->route('admin.nienkhoa.index')->with('success','Thêm niên khóa thành công');
    }

    public function edit($id)
    {
        $nienkhoa = NienKhoa::findOrFail($id);
        return view('admin.nienkhoa.edit', compact('nienkhoa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenNienKhoa' => 'nullable|string|max:100',
            'NamBatDau' => 'nullable|integer',
            'NamKetThuc' => 'nullable|integer'
        ]);

        $nienkhoa = NienKhoa::findOrFail($id);
        $nienkhoa->update($request->only('TenNienKhoa','NamBatDau','NamKetThuc'));
        return redirect()->route('admin.nienkhoa.index')->with('success','Cập nhật thành công');
    }

    public function destroy($id)
    {
        NienKhoa::destroy($id);
        return redirect()->route('admin.nienkhoa.index')->with('success','Xóa thành công');
    }
}
