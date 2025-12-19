@extends('layouts.admin')

@section('title', 'Quản lý Giảng viên')

@section('content')
<style>
    :root {
        --primary-blue: #4f46e5;
        --light-blue: #e0e7ff;
        --dark-blue: #3730a3;
        --hover-blue: #6366f1;
        --success-green: #10b981;
        --warning-orange: #f59e0b;
        --danger-red: #ef4444;
        --info-cyan: #06b6d4;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    body {
        background: #f0f4f8 !important;
    }

    .page-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-title-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    .page-title-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-blue);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-action-header {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-export {
        background: var(--info-cyan);
        color: white;
    }

    .btn-export:hover {
        background: #0891b2;
        transform: translateY(-1px);
    }

    .btn-import {
        background: var(--primary-blue);
        color: white;
    }

    .btn-import:hover {
        background: var(--dark-blue);
        transform: translateY(-1px);
    }

    .btn-add {
        background: var(--success-green);
        color: white;
    }

    .btn-add:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .alert-success-custom {
        background: #d1fae5;
        border-left: 4px solid var(--success-green);
        color: #065f46;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .search-section {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .search-input {
        flex: 1;
        padding: 0.625rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-blue);
    }

    .btn-search {
        padding: 0.625rem 1.5rem;
        background: var(--primary-blue);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-reset {
        padding: 0.625rem 1.5rem;
        background: var(--gray-200);
        color: var(--gray-600);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        border-left: 4px solid;
    }

    .stat-card.blue {
        border-left-color: var(--primary-blue);
    }

    .stat-card.green {
        border-left-color: var(--success-green);
    }

    .stat-card.orange {
        border-left-color: var(--warning-orange);
    }

    .stat-card.cyan {
        border-left-color: var(--info-cyan);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .stat-icon {
        font-size: 1.25rem;
        margin-right: 0.5rem;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: #f8fafc;
        border-bottom: 2px solid var(--gray-200);
    }

    .data-table th {
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.875rem;
        color: #374151;
    }

    .data-table tbody tr:hover {
        background: var(--gray-50);
    }

    .badge-tk {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-table {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-view {
        background: var(--primary-blue);
        color: white;
    }

    .btn-edit {
        background: var(--warning-orange);
        color: white;
    }

    .btn-delete {
        background: var(--danger-red);
        color: white;
    }

    .btn-table:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .modal-modern .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-modern .modal-header {
        background: var(--primary-blue);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
    }

    .modal-modern .modal-body {
        padding: 1.5rem;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control-modern {
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        padding: 0.625rem;
    }

    .form-control-modern:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    /* Scrollable Tabs */
    .faculty-tabs-container {
        margin-bottom: 1.5rem;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 5px;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .faculty-tabs-container::-webkit-scrollbar {
        display: none;
    }
    .faculty-tab {
        display: inline-block;
        padding: 0.5rem 1rem;
        margin-right: 0.5rem;
        border-radius: 20px;
        background: white;
        color: var(--gray-600);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        border: 1px solid var(--gray-200);
        transition: all 0.2s;
    }
    .faculty-tab:hover {
        background: var(--gray-50);
        color: var(--primary-blue);
        border-color: var(--primary-blue);
    }
    .faculty-tab.active {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }
</style>

<div class="page-container">
    {{-- Page Title --}}
    <div class="page-title-section">
        <div class="page-title">
            <div class="page-title-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <span>Quản lý Giảng viên</span>
        </div>
        <div class="header-actions">
            <div class="dropdown d-inline-block">
                <button class="btn-action-header btn-export dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <ul class="dropdown-menu shadow-lg border-0" aria-labelledby="exportDropdown" style="border-radius: 12px; padding: 0.5rem;">
                    <li>
                        <a class="dropdown-item rounded py-2" href="{{ route('admin.giangvien.export') }}">
                            <i class="fas fa-globe me-2 text-primary"></i>Xuất tất cả
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header text-uppercase small fw-bold text-muted">Xuất theo khoa</h6></li>
                    @foreach($khoas as $k)
                        <li>
                            <a class="dropdown-item rounded py-2" href="{{ route('admin.giangvien.export', ['khoa' => $k->MaKhoa]) }}">
                                <i class="fas fa-building me-2 text-secondary"></i>{{ $k->TenKhoa }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <button class="btn-action-header btn-import" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-upload"></i> Import Excel
            </button>
            <button class="btn-action-header btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i> Thêm giảng viên
            </button>
        </div>
    </div>

    {{-- Success/Error Modals triggered by JavaScript --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('success', 'Thành công!', '{!! session('success') !!}');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('error', 'Lỗi!', '{{ session('error') }}');
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('error', 'Lỗi xác thực!', '{!! implode("<br>", $errors->all()) !!}');
            });
        </script>
    @endif

    {{-- Faculty Tabs --}}
    <div class="faculty-tabs-container">
        <a href="{{ route('admin.giangvien.index') }}" class="faculty-tab {{ !request('khoa') ? 'active' : '' }}">
            Tất cả
        </a>
        @foreach($khoas as $k)
            <a href="{{ route('admin.giangvien.index', ['khoa' => $k->MaKhoa]) }}" class="faculty-tab {{ request('khoa') == $k->MaKhoa ? 'active' : '' }}">
                {{ $k->TenKhoa }}
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="search-section">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" class="search-input" 
                   placeholder="🔍 Tìm kiếm Mã GV, Tên, Email...">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
            <a href="{{ route('admin.giangvien.index') }}" class="btn-reset">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>
    </div>

    {{-- Statistics Cards --}}
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-value">
                <i class="fas fa-users stat-icon"></i>{{ $gvs->total() }}
            </div>
            <div class="stat-label">Tổng giảng viên</div>
        </div>
        <div class="stat-card green">
            <div class="stat-value">
                <i class="fas fa-check-circle stat-icon"></i>{{ $gvs->where('MaTK', '!=', null)->count() }}
            </div>
            <div class="stat-label">Đang hoạt động</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-value">
                <i class="fas fa-user-plus stat-icon"></i>{{ $gvs->whereBetween('created_at', [now()->startOfMonth(), now()])->count() }}
            </div>
            <div class="stat-label">Mới thêm tháng này</div>
        </div>
        <div class="stat-card cyan">
            <div class="stat-value">
                <i class="fas fa-building stat-icon"></i>{{ $gvs->pluck('MaKhoa')->unique()->count() }}
            </div>
            <div class="stat-label">Khoa</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>MÃ GV</th>
                    <th>HỌ & TÊN</th>
                    <th>NGÀY SINH</th>
                    <th>EMAIL</th>
                    <th>CCCD</th>
                    <th>SĐT</th>
                    <th>KHOA</th>
                    <th>NGÀNH</th>
                    <th>TK LIÊN KẾT</th>
                    <th>HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gvs as $gv)
                    <tr>
                        <td><strong>{{ $gv->MaGV }}</strong></td>
                        <td>{{ $gv->TenGV }}</td>
                        <td>{{ $gv->NgaySinh ? $gv->NgaySinh->format('d/m/Y') : '-' }}</td>
                        <td>{{ $gv->Email ?? '-' }}</td>
                        <td>{{ $gv->MaCCCD ?? '-' }}</td>
                        <td>{{ $gv->SDT ?? '-' }}</td>
                        <td>{{ $gv->khoa->TenKhoa ?? '-' }}</td>
                        <td>{{ $gv->nganh->TenNganh ?? '-' }}</td>
                        <td>
                            @if($gv->taiKhoan)
                                <span class="badge-tk">{{ $gv->taiKhoan->MaSo }}</span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-table btn-view"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal"
                                    data-id="{{ $gv->MaGV }}">
                                    Xem
                                </button>
                                <button class="btn-table btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="{{ $gv->MaGV }}">
                                    Sửa
                                </button>
                                <form id="delete-form-{{ $gv->MaGV }}" action="{{ route('admin.giangvien.destroy', $gv->MaGV) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-table btn-delete" onclick="confirmDelete('{{ $gv->MaGV }}', '{{ $gv->TenGV }}')">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 3rem; color: #9ca3af;">
                            <i class="fas fa-inbox" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
                            Không có giảng viên nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $gvs->links() }}
    </div>
</div>

{{-- MODAL THÊM --}}
<div class="modal fade modal-modern" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Thêm Giảng viên</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.giangvien.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-user text-primary me-2"></i>Họ & Tên <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="TenGV" class="form-control form-control-modern" required minlength="3" maxlength="200" placeholder="Nhập họ tên...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-modern">
                                <i class="fas fa-calendar text-warning me-2"></i>Ngày sinh <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="NgaySinh" class="form-control form-control-modern" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-modern">
                                <i class="fas fa-id-card text-info me-2"></i>CCCD <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="MaCCCD" class="form-control form-control-modern" required pattern="[0-9]{12}" maxlength="12" title="CCCD phải có đúng 12 chữ số" placeholder="Nhập 12 số...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-modern">Ngày cấp</label>
                            <input type="date" name="NgayCap" class="form-control form-control-modern">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-modern">Nơi cấp</label>
                            <input type="text" name="NoiCap" class="form-control form-control-modern" placeholder="Nơi cấp...">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-modern">
                                <i class="fas fa-phone text-success me-2"></i>SĐT <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="SDT" class="form-control form-control-modern" required pattern="[0-9]{10}" maxlength="10" title="Số điện thoại phải có đúng 10 chữ số" placeholder="Nhập 10 số...">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-modern">
                                <i class="fas fa-envelope text-danger me-2"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="Email" class="form-control form-control-modern" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Email chỉ được chứa chữ, số và ký tự @" placeholder="email@example.com">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-modern">
                                <i class="fas fa-venus-mars text-secondary me-2"></i>Giới tính <span class="text-danger">*</span>
                            </label>
                            <select name="GioiTinh" class="form-select form-control-modern" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-modern">Khoa <span class="text-danger">*</span></label>
                            <input list="listKhoa" name="MaKhoa" class="form-control form-control-modern" required placeholder="Chọn hoặc nhập khoa mới">
                            <datalist id="listKhoa">
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->TenKhoa }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-modern">Ngành <span class="text-danger">*</span></label>
                            <input list="listNganh" name="MaNganh" class="form-control form-control-modern" required placeholder="Chọn hoặc nhập ngành mới">
                            <datalist id="listNganh">
                                @foreach($nganhs as $nganh)
                                    <option value="{{ $nganh->TenNganh }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-modern">Năm học</label>
                            <select name="MaNamHoc" class="form-select form-control-modern">
                                <option value="">-- Chọn năm học --</option>
                                @foreach($namhocs as $nh)
                                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-modern">Chuyên ngành</label>
                            <input type="text" name="ChuyenNganh" class="form-control form-control-modern" placeholder="Chuyên ngành...">
                        </div>


                        {{-- Hidden: Tài khoản liên kết (auto-managed by system)
                        <div class="col-md-12">
                            <label class="form-label-modern">Mã tài khoản liên kết</label>
                            <select name="MaTK" class="form-select form-control-modern">
                                <option value="">-- Không liên kết --</option>
                                @foreach($taikhoans as $tk)
                                    <option value="{{ $tk->MaTK }}">{{ $tk->MaTK }} / {{ $tk->MaSo }}</option>
                                @endforeach
                            </select>
                        </div>
                        --}}
                    </div>
                </div>

                <div class="modal-footer" style="background: var(--gray-50);">
                    <button type="button" class="btn btn-modern" style="background: var(--gray-200); color: var(--gray-600);" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL XEM --}}
<div class="modal fade modal-modern" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> Thông tin giảng viên</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewContent">
                <!-- AJAX load -->
            </div>
        </div>
    </div>
</div>

{{-- MODAL IMPORT --}}
<div class="modal fade modal-modern" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-file-upload"></i> Import Giảng viên từ Excel</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.giangvien.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-modern">Chọn file Excel (.xlsx, .csv)</label>
                        <input type="file" name="excel_file" class="form-control form-control-modern" required accept=".xlsx,.csv">
                    </div>
                    <div class="alert alert-modern" style="background: var(--light-blue); color: var(--dark-blue); border-left: 4px solid var(--primary-blue);">
                        <small>
                            File Excel cần có các cột: <strong>MaGV, TenGV</strong> (bắt buộc).<br>
                            Các cột khác: Email, SDT, CCCD, NgaySinh (yyyy-mm-dd), GioiTinh, Khoa, Nganh, HocVi, HocHam.
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="background: var(--gray-50);">
                    <button type="button" class="btn btn-modern" style="background: var(--gray-200); color: var(--gray-600);" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                    <button type="submit" class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-upload me-2"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SỬA --}}
<div class="modal fade modal-modern" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Sửa giảng viên</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editContent">
                    <!-- AJAX load -->
                </div>
            </form>
        </div>
    </div>
</div>

{{-- AJAX SCRIPTS --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Load modal Xem
    document.querySelectorAll('[data-bs-target="#viewModal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            fetch(`/admin/giangvien/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.text())
                .then(html => document.querySelector("#viewContent").innerHTML = html);
        });
    });

    // Load modal Sửa
    document.querySelectorAll('[data-bs-target="#editModal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            fetch(`/admin/giangvien/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#editContent").innerHTML = html;
                    document.querySelector("#editForm").action = `/admin/giangvien/${id}`;
                });
        });
    });
});
</script>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #856404;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Bạn có chắc chắn muốn xóa giảng viên này?</h5>
                <p class="text-muted mb-4" id="deleteMessage">Xóa giảng viên này?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn" style="border-radius: 20px;">OK</button>
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 20px; background-color: #f8d7da; color: #721c24; border: none;">Hủy</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Notification Modal (Success/Error) --}}
<div class="modal fade" id="notificationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-body p-0">
                <!-- Header with Icon -->
                <div class="text-center pt-5 pb-3" id="notificationHeader" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div id="notificationIcon" class="mb-3"></div>
                    <h4 class="fw-bold text-white mb-0" id="notificationTitle">Thành công!</h4>
                </div>
                
                <!-- Content -->
                <div class="p-4" id="notificationContent">
                    <div id="notificationMessage" style="line-height: 1.8; color: #4a5568;"></div>
                </div>
                
                <!-- Footer Buttons -->
                <div class="p-4 pt-0" id="notificationButtons"></div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    width: 80px; height: 80px; margin: 0 auto;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    animation: scaleIn 0.5s ease-out;
    backdrop-filter: blur(10px);
}
.success-icon i { font-size: 40px; color: #fff; }

.error-icon {
    width: 80px; height: 80px; margin: 0 auto;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    animation: shake 0.5s ease-out;
    backdrop-filter: blur(10px);
}
.error-icon i { font-size: 40px; color: #fff; }

.info-card {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 0.75rem;
}

.info-card-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.info-card-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-weight: 600;
    color: #4a5568;
    min-width: 120px;
}

.info-value {
    color: #2d3748;
    flex: 1;
}

@keyframes scaleIn {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); opacity: 1; }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#notificationContent {
    animation: fadeInUp 0.5s ease-out 0.2s both;
}
</style>

<script>
function showNotificationModal(type, title, message) {
    const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
    const headerEl = document.getElementById('notificationHeader');
    
    document.getElementById('notificationTitle').textContent = title;
    
    if (type === 'success') {
        // Success gradient
        headerEl.style.background = 'linear-gradient(135deg, #3390e0ff 0%, #1f5bf2ff 100%)';
        document.getElementById('notificationIcon').innerHTML = '<div class="success-icon"><i class="fas fa-check-circle"></i></div>';
        
        // Parse and format message
        const formattedMessage = formatSuccessMessage(message);
        document.getElementById('notificationMessage').innerHTML = formattedMessage;
        
        document.getElementById('notificationButtons').innerHTML = `
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-check me-2"></i>Đóng
                </button>
            </div>
        `;
        
        // Auto close after 5 seconds
        setTimeout(() => { 
            modal.hide(); 
            setTimeout(() => window.location.reload(), 300);
        }, 5000);
    } else {
        // Error gradient
        headerEl.style.background = 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
        document.getElementById('notificationIcon').innerHTML = '<div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>';
        document.getElementById('notificationMessage').innerHTML = message;
        
        document.getElementById('notificationButtons').innerHTML = `
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-times me-2"></i>Đóng
                </button>
            </div>
        `;
    }
    modal.show();
}

function formatSuccessMessage(message) {
    // Parse the message to extract structured data
    const lines = message.split('<br>');
    let html = '';
    let currentSection = '';
    
    lines.forEach(line => {
        line = line.trim();
        if (!line) return;
        
        if (line.includes('📋 Thông tin:')) {
            html += '<div class="info-card"><div style="font-weight: 700; color: #327de7ff; margin-bottom: 0.75rem; font-size: 1.1rem;"><i class="fas fa-info-circle me-2"></i>Thông tin giảng viên</div>';
            currentSection = 'info';
        } else if (line.includes('🔑 Tài khoản đăng nhập:')) {
            if (currentSection === 'info') html += '</div>';
            html += '<div class="info-card" style="border-left-color: #10b981;"><div style="font-weight: 700; color: #10b981; margin-bottom: 0.75rem; font-size: 1.1rem;"><i class="fas fa-key me-2"></i>Tài khoản đăng nhập</div>';
            currentSection = 'account';
        } else if (line.startsWith('•')) {
            const parts = line.substring(1).split(':');
            if (parts.length === 2) {
                const label = parts[0].trim();
                const value = parts[1].trim();
                html += `
                    <div class="info-card-item">
                        <span class="info-label">${label}:</span>
                        <span class="info-value"><strong>${value}</strong></span>
                    </div>
                `;
            }
        } else if (line.includes('✅')) {
            html += `<div style="text-align: center; margin-bottom: 1.5rem; font-size: 1.2rem; color: #10b981; font-weight: 600;">
                        <i class="fas fa-check-circle me-2"></i>${line.replace('✅', '')}
                     </div>`;
        }
    });
    
    if (currentSection) html += '</div>';
    
    return html;
}

function confirmDelete(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteMessage').textContent = `Xóa giảng viên ${name}?`;
    
    document.getElementById('confirmDeleteBtn').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
    };
    
    modal.show();
}
</script>

@endsection
