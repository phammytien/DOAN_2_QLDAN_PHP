<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1e88e5;
            --light-blue: #e3f2fd;
            --sidebar-bg: #f8f9fa;
            --sidebar-active: #1e88e5;
            --header-height: 70px;
            --text-dark: #2c3e50;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            overflow-x: hidden;
        }

        /* HEADER */
        .main-header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-area img {
            height: 45px;
            width: auto;
            object-fit: contain;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .logo-text-top {
            font-size: 11px;
            font-weight: 600;
            color: #1e88e5;
            text-transform: uppercase;
        }

        .logo-text-bottom {
            font-size: 13px;
            font-weight: 700;
            color: #d32f2f;
            text-transform: uppercase;
        }

        .search-bar {
            position: relative;
        }

        .search-bar input {
            border-radius: 20px;
            padding: 5px 15px 5px 35px;
            border: 1px solid #ddd;
            width: 300px;
            background: #f8f9fa;
        }

        .search-bar i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
            color: #555;
        }

        .header-link {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            position: fixed;
            top: var(--header-height);
            bottom: 0;
            left: 0;
            z-index: 900;
            overflow-y: auto;
            padding-top: 10px;
            border-right: 1px solid #e0e0e0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            margin-bottom: 2px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: var(--text-dark);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            justify-content: space-between;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .menu-link:hover {
            background: var(--light-blue);
            color: var(--primary-blue);
            border-left-color: var(--primary-blue);
        }

        .menu-link.active {
            background: var(--light-blue);
            color: var(--primary-blue);
            border-left-color: var(--primary-blue);
            font-weight: 600;
        }

        .menu-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: var(--primary-blue);
        }
        
        .submenu-icon {
            font-size: 10px;
            transition: transform 0.3s;
            color: #666;
        }
        
        .menu-link[aria-expanded="true"] .submenu-icon {
            transform: rotate(180deg);
        }

        /* SUBMENU */
        .submenu {
            list-style: none;
            padding: 0;
            background: #ffffff;
            display: none;
            border-left: 3px solid var(--light-blue);
        }
        
        .submenu.show {
            display: block;
        }

        .submenu .menu-link {
            padding-left: 52px;
            font-size: 13px;
            border-left: none;
            font-weight: 400;
        }

        .submenu .menu-link:hover {
            background: var(--light-blue);
            border-left: none;
        }

        .submenu .menu-link.active {
            background: var(--light-blue);
            border-left: none;
        }

        .submenu .menu-icon {
            font-size: 14px;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            margin-top: var(--header-height);
            padding: 20px;
            min-height: calc(100vh - var(--header-height));
        }

        /* MODAL RESET SUCCESS (Keep existing styles) */
        #modalResetSuccess {
            position: fixed !important;
            z-index: 999999 !important;
        }
        #modalResetSuccess .modal-dialog,
        #modalResetSuccess .modal-content {
            z-index: 1000000 !important;
            position: relative !important;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .search-bar input {
                width: 150px;
            }
            .logo-text {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="main-header">
        <div class="header-left">
            <div class="logo-area">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2">
                    <img src="https://euni.vn/wp-content/uploads/2025/02/DHV-1200-800.png" alt="Logo">
                    <div class="d-flex flex-column">
                        <div class="fw-bold text-primary" style="font-size: 12px; line-height: 1.2;">BỘ GIÁO DỤC VÀ ĐÀO TẠO</div>
                        <div class="fw-bold text-danger text-uppercase" style="font-size: 14px; line-height: 1.2;">TRƯỜNG ĐẠI HỌC ĐỒNG THÁP</div>
                    </div>

                </a>
            </div>
            <div class="search-bar d-none d-md-block">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Tìm kiếm...">
            </div>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.dashboard') }}" class="header-link">
                <i class="bi bi-house-door-fill"></i> Trang chủ
            </a>
            
            {{-- Notification Bell for Password Reset --}}
            <a href="{{ route('admin.taikhoan.index', ['trangthai' => 'request_reset']) }}" class="header-link position-relative me-3">
                <i class="bi bi-bell-fill text-warning fs-5"></i>
                @if(isset($requestResetCount) && $requestResetCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        {{ $requestResetCount }}
                        <span class="visually-hidden">yêu cầu reset</span>
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.thongbao.index') }}" class="header-link">
                <i class="bi bi-megaphone-fill text-danger"></i> Tin tức
            </a>
            <div class="user-profile dropdown">
                <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="d-none d-md-block">{{ $adminInfo->name ?? Auth::user()->name }}</span>
                    <i class="bi bi-caret-down-fill" style="font-size: 10px;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li>
                        <div class="px-3 py-2">
                            <div class="fw-bold">{{ $adminInfo->name ?? Auth::user()->name }}</div>
                            <div class="small text-muted">{{ Auth::user()->email }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person-gear me-2"></i>Hồ sơ cá nhân</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.change_password') }}"><i class="bi bi-key me-2"></i>Đổi mật khẩu</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-house-door-fill menu-icon"></i> TRANG CHỦ
                    </div>
                </a>
            </li>
            
            {{-- Quản lý người dùng (Dropdown) --}}
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle-btn {{ request()->routeIs('admin.giangvien.*') || request()->routeIs('admin.sinhvien.*') || request()->routeIs('admin.canbo.*') ? 'active' : '' }}" 
                   data-target="#submenu-users" aria-expanded="false">
                    <div>
                        <i class="bi bi-people-fill menu-icon"></i> QUẢN LÝ NGƯỜI DÙNG
                    </div>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul id="submenu-users" class="submenu {{ request()->routeIs('admin.giangvien.*') || request()->routeIs('admin.sinhvien.*') || request()->routeIs('admin.canbo.*') ? 'show' : '' }}">
                    <li>
                        <a href="{{ route('admin.giangvien.index') }}" class="menu-link {{ request()->routeIs('admin.giangvien.*') ? 'active' : '' }}">
                            <div><i class="bi bi-person-video menu-icon"></i> Giảng viên</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sinhvien.index') }}" class="menu-link {{ request()->routeIs('admin.sinhvien.*') ? 'active' : '' }}">
                            <div><i class="bi bi-mortarboard-fill menu-icon"></i> Sinh viên</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.canbo.index') }}" class="menu-link {{ request()->routeIs('admin.canbo.*') ? 'active' : '' }}">
                            <div><i class="bi bi-person-badge-fill menu-icon"></i> Cán bộ</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Phân quyền --}}
            <li class="menu-item">
                <a href="{{ route('admin.taikhoan.index') }}" class="menu-link {{ request()->routeIs('admin.taikhoan.*') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-shield-lock-fill menu-icon"></i> PHÂN QUYỀN
                    </div>
                </a>
            </li>

            {{-- Quản lý đào tạo (Dropdown) --}}
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle-btn {{ request()->routeIs('admin.khoa.*') || request()->routeIs('admin.lop.*') || request()->routeIs('admin.nganh.*') ? 'active' : '' }}" 
                   data-target="#submenu-training" aria-expanded="false">
                    <div>
                        <i class="bi bi-building-fill menu-icon"></i> QUẢN LÝ ĐÀO TẠO
                    </div>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul id="submenu-training" class="submenu {{ request()->routeIs('admin.khoa.*') || request()->routeIs('admin.lop.*') || request()->routeIs('admin.nganh.*') ? 'show' : '' }}">
                    <li>
                        <a href="{{ route('admin.khoa.index') }}" class="menu-link {{ request()->routeIs('admin.khoa.*') ? 'active' : '' }}">
                            <div><i class="bi bi-bank2 menu-icon"></i> Khoa</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.lop.index') }}" class="menu-link {{ request()->routeIs('admin.lop.*') ? 'active' : '' }}">
                            <div><i class="bi bi-people menu-icon"></i> Lớp</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.nganh.index') }}" class="menu-link {{ request()->routeIs('admin.nganh.*') ? 'active' : '' }}">
                            <div><i class="bi bi-book-half menu-icon"></i> Ngành</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Quản lý đề tài (Dropdown) --}}
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle-btn {{ request()->routeIs('admin.detai.*') ? 'active' : '' }}" 
                   data-target="#submenu-detai" aria-expanded="false">
                    <div>
                        <i class="bi bi-journal-text menu-icon"></i> QUẢN LÝ ĐỀ TÀI
                    </div>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul id="submenu-detai" class="submenu {{ request()->routeIs('admin.detai.*') ? 'show' : '' }}">
                    <li>
                        <a href="{{ route('admin.detai.index') }}" class="menu-link {{ request()->routeIs('admin.detai.index') ? 'active' : '' }}">
                            <div><i class="bi bi-list-ul menu-icon"></i> Danh sách đề tài</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.detai.dangky') }}" class="menu-link {{ request()->routeIs('admin.detai.dangky') ? 'active' : '' }}">
                            <div><i class="bi bi-people-fill menu-icon"></i> Sinh viên đăng ký</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Phân công --}}
            <li class="menu-item">
                <a href="{{ route('admin.phancong.index') }}" class="menu-link {{ request()->routeIs('admin.phancong.*') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-clipboard-check-fill menu-icon"></i> PHÂN CÔNG
                    </div>
                </a>
            </li>

            {{-- Quản lý báo cáo (Dropdown) --}}
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle-btn {{ request()->routeIs('admin.baocao.*') || request()->routeIs('admin.tiendo.*') ? 'active' : '' }}" 
                   data-target="#submenu-reports" aria-expanded="false">
                    <div>
                        <i class="bi bi-file-earmark-bar-graph-fill menu-icon"></i> QUẢN LÝ BÁO CÁO
                    </div>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul id="submenu-reports" class="submenu {{ request()->routeIs('admin.baocao.*') || request()->routeIs('admin.tiendo.*') || request()->routeIs('admin.baocao.thongke') ? 'show' : '' }}">
                    <li>
                        <a href="{{ route('admin.baocao.index') }}" class="menu-link {{ request()->routeIs('admin.baocao.index') ? 'active' : '' }}">
                            <div><i class="bi bi-file-text-fill menu-icon"></i> Báo cáo</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tiendo.index') }}" class="menu-link {{ request()->routeIs('admin.tiendo.*') ? 'active' : '' }}">
                            <div><i class="bi bi-list-task menu-icon"></i> Tiến độ</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.baocao.thongke') }}" class="menu-link {{ request()->routeIs('admin.baocao.thongke') ? 'active' : '' }}">
                            <div><i class="bi bi-bar-chart-fill menu-icon"></i> Thống kê</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Điểm --}}
            <li class="menu-item">
                <a href="{{ route('admin.chamdiem.index') }}" class="menu-link {{ request()->routeIs('admin.chamdiem.*') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-star-fill menu-icon"></i> QUẢN LÝ ĐIỂM
                    </div>
                </a>
            </li>

            {{-- Thông báo --}}
            <li class="menu-item">
                <a href="{{ route('admin.thongbao.index') }}" class="menu-link {{ request()->routeIs('admin.thongbao.*') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-megaphone-fill menu-icon"></i> THÔNG BÁO
                    </div>
                </a>
            </li>

            {{-- Cấu hình --}}
            <li class="menu-item">
                <a href="{{ route('admin.cauhinh.index') }}" class="menu-link {{ request()->routeIs('admin.cauhinh.*') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-gear-fill menu-icon"></i> CẤU HÌNH HỆ THỐNG
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown functionality
            const dropdownBtns = document.querySelectorAll('.dropdown-toggle-btn');
            
            dropdownBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const targetSubmenu = document.querySelector(targetId);
                    
                    // Toggle current submenu
                    if (targetSubmenu) {
                        targetSubmenu.classList.toggle('show');
                        const isExpanded = this.getAttribute('aria-expanded') === 'true';
                        this.setAttribute('aria-expanded', !isExpanded);
                    }
                });
            });
        });
    </script>
</body>
</html>
