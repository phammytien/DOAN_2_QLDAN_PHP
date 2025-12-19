@extends('layouts.admin')

@section('title', 'Phân quyền người dùng')

@section('content')
<style>
    body {
        background: #f5f7fa !important;
    }

    .permission-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }

    .search-filter-bar {
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-input {
        flex: 1;
        max-width: 300px;
        padding: 0.625rem 1rem 0.625rem 2.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 0.75rem center;
        background-size: 1.25rem;
    }

    .search-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .filter-select {
        padding: 0.625rem 2.5rem 0.625rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        cursor: pointer;
        min-width: 180px;
    }

    .btn-search {
        padding: 0.625rem 1.5rem;
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-search:hover {
        background: #4f46e5;
        transform: translateY(-1px);
    }

    .tabs-container {
        background: white;
        border-radius: 12px 12px 0 0;
        padding: 0;
        margin-bottom: 0;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }

    .nav-tabs {
        border-bottom: 2px solid #e5e7eb;
        padding: 0 1.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6b7280;
        font-weight: 600;
        padding: 1rem 1.5rem;
        margin-bottom: -2px;
        transition: all 0.2s;
        border-bottom: 2px solid transparent;
    }

    .nav-tabs .nav-link:hover {
        color: #6366f1;
        border-bottom-color: #e5e7eb;
    }

    .nav-tabs .nav-link.active {
        color: #6366f1;
        background: transparent;
        border-bottom-color: #6366f1;
    }

    .table-container {
        background: white;
        border-radius: 0 0 12px 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge-role {
        padding: 0.375rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-sinhvien {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-giangvien {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-canbo {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
        padding: 0.375rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
        padding: 0.375rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        margin: 0 2px;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-lock {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-unlock {
        background: #d1fae5;
        color: #065f46;
    }

    .btn-reset {
        background: #dbeafe;
        color: #1e40af;
    }
</style>

<div class="permission-container">
    <h1 class="page-title">Phân quyền người dùng</h1>

    {{-- Search and Filter Bar --}}
    <form method="GET" action="{{ route('admin.taikhoan.index') }}" class="search-filter-bar">
        <input type="text" name="keyword" class="search-input" 
               placeholder="Tìm theo mã số hoặc tên..." 
               value="{{ request('keyword') }}">
        
        <select name="vaitro" class="filter-select" onchange="this.form.submit()">
            <option value="">Tất cả vai trò</option>
            @foreach($vaitros as $vt)
                <option value="{{ $vt }}" {{ request('vaitro') == $vt ? 'selected' : '' }}>
                    {{ $vt }}
                </option>
            @endforeach
        </select>
        
        <button type="submit" class="btn-search">
            Tìm kiếm
        </button>
    </form>

    {{-- Tabs --}}
    <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ !request('vaitro') || request('vaitro') == 'SinhVien' ? 'active' : '' }}" 
                   href="{{ route('admin.taikhoan.index', ['vaitro' => 'SinhVien']) }}">
                    Sinh viên
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('vaitro') == 'GiangVien' ? 'active' : '' }}" 
                   href="{{ route('admin.taikhoan.index', ['vaitro' => 'GiangVien']) }}">
                    Giảng viên
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('vaitro') == 'CanBo' ? 'active' : '' }}" 
                   href="{{ route('admin.taikhoan.index', ['vaitro' => 'CanBo']) }}">
                    Cán bộ
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request('trangthai') == 'request_reset' ? 'active' : '' }}" 
                   href="{{ route('admin.taikhoan.index', ['trangthai' => 'request_reset']) }}">
                    <i class="fas fa-key me-1 text-danger"></i> Yêu cầu Reset
                    @if(isset($requestResetCount) && $requestResetCount > 0)
                        <span class="badge bg-danger rounded-pill ms-1">{{ $requestResetCount }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">STT</th>
                    <th>MÃ SỐ</th>
                    <th>TÊN</th>
                    <th>VAI TRÒ</th>
                    <th>TRẠNG THÁI</th>
                    <th style="width: 150px;">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($taikhoans as $tk)
                <tr>
                    <td>{{ $taikhoans->firstItem() + $loop->index }}</td>
                    <td><strong>{{ $tk->MaSo }}</strong></td>
                    <td>{{ $tk->TenSV ?? $tk->TenGV ?? $tk->TenCB ?? '—' }}</td>
                    <td>
                        @if($tk->VaiTro == 'SinhVien')
                            <span class="badge-role badge-sinhvien">SinhVien</span>
                        @elseif($tk->VaiTro == 'GiangVien')
                            <span class="badge-role badge-giangvien">GiangVien</span>
                        @else
                            <span class="badge-role badge-canbo">{{ $tk->VaiTro }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($tk->TrangThai === 'active')
                            <span class="badge-active">Hoạt động</span>
                        @elseif ($tk->TrangThai === 'request_reset')
                            <span class="badge bg-warning text-dark border border-warning">
                                <i class="fas fa-clock me-1"></i> Chờ Reset
                            </span>
                        @else
                            <span class="badge-inactive">Bị khóa</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="action-btn btn-edit" 
                                    data-id="{{ $tk->MaTK }}"
                                    data-maso="{{ $tk->MaSo }}"
                                    data-vaitro="{{ $tk->VaiTro }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalSua"
                                    title="Sửa">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="action-btn {{ $tk->TrangThai === 'active' ? 'btn-lock' : 'btn-unlock' }} btn-toggle-status"
                                    data-id="{{ $tk->MaTK }}"
                                    data-maso="{{ $tk->MaSo }}"
                                    data-status="{{ $tk->TrangThai }}"
                                    title="{{ $tk->TrangThai === 'active' ? 'Khóa' : 'Mở khóa' }}">
                                <i class="fas fa-{{ $tk->TrangThai === 'active' ? 'lock' : 'lock-open' }}"></i>
                            </button>

                            <button class="action-btn btn-reset btn-confirm-reset"
                                    data-id="{{ $tk->MaTK }}"
                                    data-maso="{{ $tk->MaSo }}"
                                    title="Reset mật khẩu">
                                <i class="fas fa-key"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
                        Không tìm thấy tài khoản nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $taikhoans->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODAL SỬA TÀI KHOẢN --}}
<div class="modal fade" id="modalSua" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background: #6366f1; color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Sửa tài khoản</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-sua" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-id-card text-primary me-2"></i>Mã số
                        </label>
                        <input type="text" name="MaSo" id="edit-maso" class="form-control" required style="border: 2px solid #e5e7eb; border-radius: 8px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-lock text-warning me-2"></i>Mật khẩu mới (không bắt buộc)
                        </label>
                        <input type="password" name="MatKhau" class="form-control" 
                               placeholder="Đã bị khóa. Sử dụng chức năng Reset mật khẩu" style="border: 2px solid #e5e7eb; border-radius: 8px; background-color: #f3f4f6;" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user-tag text-info me-2"></i>Vai trò
                        </label>
                        <select name="VaiTro" id="edit-vaitro" class="form-select" required style="border: 2px solid #e5e7eb; border-radius: 8px;">
                            @foreach($vaitros as $vt)
                                <option value="{{ $vt }}">{{ $vt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f9fafb; border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-primary" style="background: #6366f1; border: none; border-radius: 8px;">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL XÁC NHẬN RESET MẬT KHẨU --}}
<div class="modal fade" id="modalConfirmReset" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon mx-auto bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-key fa-3x"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-3">Cấp lại mật khẩu?</h4>
                <p class="text-muted mb-4">Bạn có chắc chắn muốn reset mật khẩu cho tài khoản <strong id="reset-maso" class="text-primary"></strong> không?</p>
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <i class="fas fa-info-circle me-2"></i>Mật khẩu mới sẽ là: <strong>123456</strong>
                </div>
                
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">
                        Hủy bỏ
                    </button>
                    <form id="form-reset" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-white">
                            Xác nhận Reset
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL XÁC NHẬN KHÓA/MỞ KHÓA TÀI KHOẢN --}}
<div class="modal fade" id="modalConfirmToggle" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon mx-auto bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-lock fa-3x" id="toggle-icon"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-3" id="toggle-title">Khóa tài khoản?</h4>
                <p class="text-muted mb-4">Bạn có chắc chắn muốn <strong id="toggle-action">khóa</strong> tài khoản <strong id="toggle-maso" class="text-primary"></strong> không?</p>
                <div class="alert alert-warning border-0 rounded-3 mb-4" id="toggle-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Tài khoản sẽ không thể đăng nhập sau khi bị khóa!
                </div>
                
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">
                        Hủy bỏ
                    </button>
                    <form id="form-toggle" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn px-4 py-2 rounded-pill fw-bold text-white" id="toggle-confirm-btn">
                            Xác nhận
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL THÔNG BÁO RESET THÀNH CÔNG --}}
<div class="modal fade" id="modalResetSuccess" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="success-icon mx-auto bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; animation: scaleIn 0.5s ease-out;">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-3">Cấp lại mật khẩu thành công!</h4>
                <p class="text-muted mb-4">Mật khẩu mới cho tài khoản này là:123456</p>
                
                <!-- <div class="bg-light p-3 rounded-3 mb-4 border border-primary border-opacity-25 position-relative overflow-hidden shadow-sm">
                    <h2 class="text-dark fw-bold mb-0" style="letter-spacing: 4px; font-family: monospace;">123456</h2>
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-10" style="pointer-events: none;"></div>
                </div> -->
                
                <button type="button" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm" data-bs-dismiss="modal">
                    Hoàn tất
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL THÔNG BÁO THÀNH CÔNG (CHUNG) --}}
<div class="modal fade" id="modalActionSuccess" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="success-icon mx-auto bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; animation: scaleIn 0.5s ease-out;">
                        <i class="fas fa-check fa-3x"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-3">Thành công!</h4>
                <p class="text-muted mb-4" id="actionSuccessMessage">{{ session('success') }}</p>
                
                <button type="button" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow-sm" data-bs-dismiss="modal">
                    Tuyệt vời
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scaleIn {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1); }
}
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // XỬ LÝ NÚT SỬA
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let maso = this.getAttribute('data-maso');
            let vaitro = this.getAttribute('data-vaitro');

            document.getElementById('edit-maso').value = maso;
            document.getElementById('edit-vaitro').value = vaitro;
            document.getElementById('form-sua').action =
                "{{ route('admin.taikhoan.update', ['taikhoan' => '__ID__']) }}".replace('__ID__', id);
        });
    });

    // XỬ LÝ NÚT RESET - HIỆN MODAL XÁC NHẬN
    const confirmResetModal = new bootstrap.Modal(document.getElementById('modalConfirmReset'));
    document.querySelectorAll('.btn-confirm-reset').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let maso = this.getAttribute('data-maso');
            
            document.getElementById('reset-maso').textContent = maso;
            document.getElementById('form-reset').action = 
                "{{ route('admin.taikhoan.resetPassword', ['id' => '__ID__']) }}".replace('__ID__', id);
            
            confirmResetModal.show();
        });
    });

    // XỬ LÝ NÚT KHÓA/MỞ KHÓA - HIỆN MODAL XÁC NHẬN
    const confirmToggleModal = new bootstrap.Modal(document.getElementById('modalConfirmToggle'));
    document.querySelectorAll('.btn-toggle-status').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let maso = this.getAttribute('data-maso');
            let status = this.getAttribute('data-status');
            
            const isActive = status === 'active';
            const icon = document.getElementById('toggle-icon');
            const title = document.getElementById('toggle-title');
            const action = document.getElementById('toggle-action');
            const warning = document.getElementById('toggle-warning');
            const confirmBtn = document.getElementById('toggle-confirm-btn');
            
            if (isActive) {
                // Khóa tài khoản
                icon.className = 'fas fa-lock fa-3x';
                title.textContent = 'Khóa tài khoản?';
                action.textContent = 'khóa';
                warning.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Tài khoản sẽ không thể đăng nhập sau khi bị khóa!';
                warning.className = 'alert alert-warning border-0 rounded-3 mb-4';
                confirmBtn.className = 'btn btn-danger px-4 py-2 rounded-pill fw-bold text-white';
                confirmBtn.textContent = 'Xác nhận Khóa';
            } else {
                // Mở khóa tài khoản
                icon.className = 'fas fa-lock-open fa-3x';
                title.textContent = 'Mở khóa tài khoản?';
                action.textContent = 'mở khóa';
                warning.innerHTML = '<i class="fas fa-info-circle me-2"></i>Tài khoản sẽ có thể đăng nhập lại sau khi mở khóa!';
                warning.className = 'alert alert-info border-0 rounded-3 mb-4';
                confirmBtn.className = 'btn btn-success px-4 py-2 rounded-pill fw-bold text-white';
                confirmBtn.textContent = 'Xác nhận Mở khóa';
            }
            
            document.getElementById('toggle-maso').textContent = maso;
            document.getElementById('form-toggle').action = 
                "{{ route('admin.taikhoan.toggleStatus', ['id' => '__ID__']) }}".replace('__ID__', id);
            
            confirmToggleModal.show();
        });
    });

    // XỬ LÝ HIỂN THỊ THÔNG BÁO THÀNH CÔNG
    @if(session('success'))
        @if(str_contains(session('success'), 'Mật khẩu mới'))
            // Trường hợp Reset mật khẩu -> Hiện modal mật khẩu
            const resetSuccessModal = new bootstrap.Modal(document.getElementById('modalResetSuccess'));
            resetSuccessModal.show();
        @else
            // Trường hợp Khác (Khóa/Mở khóa, Cập nhật...) -> Hiện modal chung
            const actionSuccessModal = new bootstrap.Modal(document.getElementById('modalActionSuccess'));
            actionSuccessModal.show();
        @endif
    @endif
});
</script>
@endsection