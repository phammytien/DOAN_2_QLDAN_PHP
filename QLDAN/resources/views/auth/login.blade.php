<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-blue: #4267B2;
            --header-blue: #3b5998;
            --light-bg: #f5f6f8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header-top {
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

.logo-img {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

        .header-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header-text p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            padding: 40px 20px;
        }

        .container-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* FOOTER */
        .footer-bottom {
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
            color: white;
            padding: 25px 0;
            margin-top: auto;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .footer-content p {
            margin: 5px 0;
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .footer-content a {
            color: white;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .footer-content a:hover {
            border-bottom-color: white;
            opacity: 1;
        }

        /* FORM ĐĂNG NHẬP */
        .login-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            height: fit-content;
        }

        .login-title {
            color: var(--primary-blue);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-title i {
            font-size: 1.5rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 1.1rem;
            z-index: 10;
        }

        .input-group-custom input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            height: 48px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .input-group-custom input:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(66, 103, 178, 0.15);
        }

        .btn-login {
            background-color: var(--primary-blue);
            color: white;
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #365899;
            transform: translateY(-1px);
        }

        /* THÔNG BÁO LỖI */
        .alert-login-error {
            background: #fff5f5;
            border: 1px solid #dc3545;
            border-left: 5px solid #dc3545;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* THÔNG BÁO */
        .notifications-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-height: 600px;
            overflow-y: auto;
        }

        .notifications-header {
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 20px;
        }

        .notifications-title {
            color: #e67e22;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }

        .notification-item {
            padding: 15px 20px;
            margin-bottom: 12px;
            border-radius: 8px;
            background: #f8f9fa;
            border-left: 4px solid #e67e22;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background: #e8f4f8;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .notification-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
        }

        .notification-text {
            flex: 1;
            font-size: 0.95rem;
            color: #333;
            line-height: 1.5;
        }

        .notification-date {
            font-size: 0.85rem;
            color: #666;
            white-space: nowrap;
            font-weight: 500;
        }

        .no-notifications {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .no-notifications i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Modal */
        .modal-content {
            border-radius: 15px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #365899 100%);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
        }

        .modal-header .modal-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 30px;
            background: #fafafa;
        }

        .modal-detail-row {
            background: white;
            padding: 18px 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 4px solid var(--primary-blue);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .modal-detail-row:hover {
            transform: translateX(3px);
        }

        .modal-detail-row:last-child {
            margin-bottom: 0;
        }

        .modal-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-label i {
            color: var(--primary-blue);
            font-size: 1.1rem;
        }

        .modal-value {
            color: #333;
            line-height: 1.6;
            font-size: 0.95rem;
            padding-left: 28px;
        }

        .modal-footer {
            padding: 15px 30px;
            background: #fafafa;
            border-top: none;
        }

        .modal-footer .btn {
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .file-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 8px 15px;
            background: #e8f4f8;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .file-link:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateX(3px);
        }

        .file-link i {
            font-size: 1.1rem;
        }

        /* Custom scrollbar */
        .notifications-container::-webkit-scrollbar {
            width: 8px;
        }

        .notifications-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .notifications-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .notifications-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            .login-container,
            .notifications-container {
                padding: 20px;
            }

            .notification-content {
                flex-direction: column;
            }

            .notification-date {
                align-self: flex-start;
            }
        }
    </style>
</head>

<body>

<!-- HEADER -->
<header class="header-top">
    <div class="header-content">
        <div class="logo-container">
            <div class="logo-img">
            <img src="https://euni.vn/wp-content/uploads/2025/02/DHV-1200-800.png" alt="Logo">
            </div>
            <div class="header-text">
                <h1>TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</h1>
                <p>Hệ thống quản lý đồ án</p>
            </div>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="main-content">
<div class="container-wrapper">
    <div class="row g-4">
        
        <!-- 🔐 FORM ĐĂNG NHẬP -->
        <div class="col-lg-4 col-md-5">
            <div class="login-container">
                <h2 class="login-title">
                    <i class="bi bi-person-circle"></i>
                    Đăng nhập hệ thống
                </h2>

                <!-- THÔNG BÁO LỖI ĐĂNG NHẬP -->
                @if($errors->any())
                    <div class="alert alert-danger alert-login-error">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Đăng nhập thất bại!</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-login-error">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-x-circle-fill" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>{{ session('error') }}</strong>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf

                    <div class="input-group-custom">
                        <i class="bi bi-person-circle"></i>
                        <input type="text" 
                               name="ma_so" 
                               placeholder="Nhập mã đăng nhập" 
                               value="{{ old('ma_so') }}"
                               required>
                    </div>

                    <div class="input-group-custom">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" 
                               name="mat_khau" 
                               placeholder="Nhập mật khẩu" 
                               required>
                    </div>

                    <button type="submit" class="btn-login">
                        Đăng nhập
                    </button>

                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            Quên mật khẩu? 
                            <a href="#" class="text-primary text-decoration-none fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRequestReset">
                                Gửi yêu cầu cấp lại
                            </a>
                        </small>
                    </div>
                </form>
            </div>
        </div>

        <!-- 📢 THÔNG BÁO -->
        <div class="col-lg-8 col-md-7">
            <div class="notifications-container">
                <div class="notifications-header">
                    <h3 class="notifications-title">THÔNG BÁO MỚI NHẤT</h3>
                </div>

                @if(count($thongbao) > 0)
                    <div id="notificationsList">
                        @foreach($thongbao as $tb)
                            <div class="notification-item" onclick="showNotificationDetail('{{ $tb->MaTB }}')">
                                <div class="notification-content">
                                    <div class="notification-text">{{ $tb->NoiDung }}</div>
                                    <div class="notification-date">{{ \Carbon\Carbon::parse($tb->TGDang)->format('d-m-Y') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-notifications">
                        <i class="bi bi-inbox"></i>
                        <p class="mb-0">Không có thông báo nào</p>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
</main>

<!-- FOOTER -->
<footer class="footer-bottom">
    <div class="footer-content">
        <p><strong>783 Phạm Hữu Lầu, P.6, Tp.Cao Lãnh, Đồng Tháp</strong></p>
        <p>Điện thoại: (0277) 3851518 - Fax: (0277) 388 1713</p>
        <p>Email: <a href="mailto:dhdt@dthu.edu.vn">dhdt@dthu.edu.vn</a></p>
    </div>
</footer>

<!-- Modal Chi tiết Thông báo -->
<div class="modal fade" id="notificationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-megaphone-fill me-2"></i>
                    Chi tiết thông báo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Nội dung sẽ được load vào đây -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Đóng
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL YÊU CẦU CẤP LẠI MẬT KHẨU --}}
<div class="modal fade" id="modalRequestReset" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-primary text-white border-0" style="border-radius: 16px 16px 0 0;">
                <h5 class="modal-title fw-bold"><i class="bi bi-key-fill me-2"></i>Yêu cầu cấp lại mật khẩu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-lock fa-2x"></i>
                    </div>
                    <p class="text-muted mb-0">Nhập mã số của bạn, chúng tôi sẽ gửi yêu cầu đến Admin.</p>
                </div>

                <form id="formRequestReset">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Mã số người dùng</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-badge"></i></span>
                            <input type="text" name="ma_so" class="form-control border-start-0" placeholder="VD: SV001, GV001..." required>
                        </div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="errorMsg"></div>
                    <div class="alert alert-success d-none" id="successMsg"></div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2" id="btnSendRequest">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                            Gửi yêu cầu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('formRequestReset').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnSendRequest');
        const spinner = btn.querySelector('.spinner-border');
        const errorMsg = document.getElementById('errorMsg');
        const successMsg = document.getElementById('successMsg');
        const formData = new FormData(this);

        // Reset UI
        btn.disabled = true;
        spinner.classList.remove('d-none');
        errorMsg.classList.add('d-none');
        successMsg.classList.add('d-none');

        fetch("{{ route('password.request') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                successMsg.textContent = data.message;
                successMsg.classList.remove('d-none');
                this.reset();
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('modalRequestReset')).hide();
                }, 3000);
            } else {
                errorMsg.textContent = data.message;
                errorMsg.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMsg.textContent = 'Đã xảy ra lỗi. Vui lòng thử lại sau.';
            errorMsg.classList.remove('d-none');
        })
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add('d-none');
        });
    });

    // Dữ liệu thông báo từ PHP - Chuyển đổi sang JSON
    const notificationsData = {!! json_encode($thongbao ?? []) !!};

    console.log('Notifications data:', notificationsData); // Debug

    // Hiển thị chi tiết thông báo
    function showNotificationDetail(maTB) {
        console.log('Clicked notification ID:', maTB); // Debug
        
        const notif = notificationsData.find(n => n.MaTB == maTB);
        
        if (!notif) {
            console.error('Notification not found:', maTB);
            console.log('Available notifications:', notificationsData);
            alert('Không tìm thấy thông báo này!');
            return;
        }

        console.log('Found notification:', notif); // Debug

        const modalBody = document.getElementById('modalBody');
        
        // Format ngày tháng
        let dateStr = 'N/A';
        if (notif.TGDang) {
            try {
                const date = new Date(notif.TGDang);
                dateStr = date.toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch(e) {
                console.error('Date parse error:', e);
            }
        }
        
        // Tạo link file nếu có
        let fileLink = '';
        if (notif.TenFile) {
            const fileUrl = '{{ asset("storage/uploads/thongbao") }}/' + notif.TenFile;
            fileLink = `
                <div class="modal-detail-row">
                    <div class="modal-label">
                        <i class="bi bi-paperclip"></i>
                        File đính kèm
                    </div>
                    <div class="modal-value">
                        <a href="${fileUrl}" 
                           target="_blank" 
                           class="file-link"
                           onclick="event.stopPropagation();">
                            <i class="bi bi-download"></i>
                            Tải file: ${notif.TenFile}
                        </a>
                    </div>
                </div>
            `;
        }

        // Hiển thị nội dung trong modal
        modalBody.innerHTML = `
            <div class="modal-detail-row">
                <div class="modal-label">
                    <i class="bi bi-calendar-event"></i>
                    Thời gian đăng
                </div>
                <div class="modal-value">${dateStr}</div>
            </div>
            <div class="modal-detail-row">
                <div class="modal-label">
                    <i class="bi bi-megaphone"></i>
                    Nội dung thông báo
                </div>
                <div class="modal-value">${notif.NoiDung || 'Không có nội dung'}</div>
            </div>
            ${fileLink}
        `;

        // Mở modal
        const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
        modal.show();
    }

    // Tự động focus vào input đầu tiên
    document.addEventListener('DOMContentLoaded', function() {
        const firstInput = document.querySelector('input[name="ma_so"]');
        if (firstInput) {
            firstInput.focus();
        }
        
        // Kiểm tra xem có thông báo không
        console.log('Total notifications:', notificationsData.length);
        if (notificationsData.length > 0) {
            console.log('First notification:', notificationsData[0]);
        }
    });
</script>

</body>
</html>