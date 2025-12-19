
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








