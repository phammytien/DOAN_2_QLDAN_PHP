<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cán bộ - @yield('title')</title>
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

        /* MODAL RESET SUCCESS */
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
        }
    </style>
</head>
<body>

    @php
        $user = Auth::user();
        $canbo = $user->canBoQL;
        $tenCB = $canbo ? $canbo->TenCB : ($user->name ?? 'Cán bộ');
    @endphp

    <!-- HEADER -->
    <header class="main-header">
        <div class="header-left">
            <div class="logo-area">
                <a href="{{ route('canbo.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2">
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
            <a href="{{ route('canbo.dashboard') }}" class="header-link">
                <i class="bi bi-house-door-fill"></i> Trang chủ
            </a>
            <a href="{{ route('canbo.thongbao.index') }}" class="header-link">
                <i class="bi bi-bell-fill text-danger"></i> Thông báo
            </a>
            <div class="user-profile dropdown">
                <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="d-none d-md-block">{{ $tenCB }}</span>
                    <i class="bi bi-caret-down-fill" style="font-size: 10px;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li>
                        <div class="px-3 py-2">
                            <div class="fw-bold">{{ $tenCB }}</div>
                            <div class="small text-muted">{{ $user->email }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('canbo.profile') }}"><i class="bi bi-person-gear me-2"></i>Hồ sơ cá nhân</a></li>
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
                <a href="{{ route('canbo.dashboard') }}" class="menu-link {{ request()->routeIs('canbo.dashboard') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-house-door-fill menu-icon"></i> TRANG CHỦ
                    </div>
                </a>
            </li>
            
            {{-- Xét duyệt kết quả --}}
            <li class="menu-item">
                <a href="{{ route('canbo.diem') }}" class="menu-link {{ request()->routeIs('canbo.diem') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-star-fill menu-icon"></i> XÉT DUYỆT KẾT QUẢ
                    </div>
                </a>
            </li>
{{-- Quản lý đào tạo (Dropdown) --}}
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle-btn {{ request()->routeIs('canbo.lop.*') || request()->routeIs('canbo.nganh.*') ? 'active' : '' }}" 
                   data-target="#submenu-training" aria-expanded="false">
                    <div>
                        <i class="bi bi-building-fill menu-icon"></i> QUẢN LÝ ĐÀO TẠO
                    </div>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul id="submenu-training" class="submenu {{ request()->routeIs('canbo.lop.*') || request()->routeIs('canbo.nganh.*') ? 'show' : '' }}">
                    <li>
                        <a href="{{ route('canbo.lop.index') }}" class="menu-link {{ request()->routeIs('canbo.lop.*') ? 'active' : '' }}">
                            <div><i class="bi bi-people menu-icon"></i> Lớp</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('canbo.nganh.index') }}" class="menu-link {{ request()->routeIs('canbo.nganh.*') ? 'active' : '' }}">
                            <div><i class="bi bi-book-half menu-icon"></i> Ngành</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Quản lý người dùng (Dropdown) --}}
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle-btn {{ request()->routeIs('canbo.sinhvien.*') || request()->routeIs('canbo.giangvien.*') ? 'active' : '' }}" 
                   data-target="#submenu-users" aria-expanded="false">
                    <div>
                        <i class="bi bi-people-fill menu-icon"></i> QUẢN LÝ NGƯỜI DÙNG
                    </div>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul id="submenu-users" class="submenu {{ request()->routeIs('canbo.sinhvien.*') || request()->routeIs('canbo.giangvien.*') ? 'show' : '' }}">
                    <li>
                        <a href="{{ route('canbo.sinhvien.index') }}" class="menu-link {{ request()->routeIs('canbo.sinhvien.*') ? 'active' : '' }}">
                            <div><i class="bi bi-person-badge menu-icon"></i> Sinh viên</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('canbo.giangvien.index') }}" class="menu-link {{ request()->routeIs('canbo.giangvien.*') ? 'active' : '' }}">
                            <div><i class="bi bi-person-workspace menu-icon"></i> Giảng viên</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Quản lý đề tài (Dropdown) --}}
            <li class="menu-item">
                <a href="#" class="menu-link dropdown-toggle-btn {{ request()->routeIs('canbo.tiendo') || request()->routeIs('canbo.detai.*') || request()->routeIs('canbo.phanbien') || request()->routeIs('canbo.baocao.*') ? 'active' : '' }}" 
                   data-target="#submenu-detai" aria-expanded="false">
                    <div>
                        <i class="bi bi-folder-fill menu-icon"></i> QUẢN LÝ ĐỀ TÀI
                    </div>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </a>
                <ul id="submenu-detai" class="submenu {{ request()->routeIs('canbo.tiendo') || request()->routeIs('canbo.detai.*') || request()->routeIs('canbo.phanbien') || request()->routeIs('canbo.baocao.*') ? 'show' : '' }}">
                    <li>
                        <a href="{{ route('canbo.tiendo') }}" class="menu-link {{ request()->routeIs('canbo.tiendo') ? 'active' : '' }}">
                            <div><i class="bi bi-list-task menu-icon"></i> Quản lý tiến độ</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('canbo.detai.index') }}" class="menu-link {{ request()->routeIs('canbo.detai.*') ? 'active' : '' }}">
                            <div><i class="bi bi-journal-text menu-icon"></i> Quản lý đề tài</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('canbo.phanbien') }}" class="menu-link {{ request()->routeIs('canbo.phanbien') ? 'active' : '' }}">
                            <div><i class="bi bi-people-fill menu-icon"></i> Phân công phản biện</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('canbo.baocao.index') }}" class="menu-link {{ request()->routeIs('canbo.baocao.*') ? 'active' : '' }}">
                            <div><i class="bi bi-file-earmark-text-fill menu-icon"></i> Quản lý báo cáo</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Thông báo --}}
            <li class="menu-item">
                <a href="{{ route('canbo.thongbao.index') }}" class="menu-link {{ request()->routeIs('canbo.thongbao.*') ? 'active' : '' }}">
                    <div>
                        <i class="bi bi-bell-fill menu-icon"></i> THÔNG BÁO
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