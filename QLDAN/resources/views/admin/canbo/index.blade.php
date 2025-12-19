@extends('layouts.admin')

@section('title', 'Quản lý Cán bộ')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="fas fa-user-tie me-2"></i>Quản lý Cán bộ
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Tổng số: <span class="fw-semibold text-dark">{{ $canbos->total() }}</span> cán bộ
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.canbo.export') }}" class="btn btn-info text-white shadow-sm">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </a>
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-upload me-2"></i>Import Excel
                    </button>
                    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus-circle me-2"></i>Thêm cán bộ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Tìm kiếm theo tên, email, SĐT...">
            </div>
        </div>
    </div>

    {{-- Success/Error Modals triggered by JavaScript --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('success', 'Thành công!', '{{ session('success') }}');
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

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('error', 'Cảnh báo!', '{{ session('warning') }}');
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

    <!-- Table Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-list-alt me-2"></i>Danh sách cán bộ
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="canboTable">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">
                                <i class="fas fa-id-card text-primary me-2"></i>Mã CB
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-user text-primary me-2"></i>Họ & Tên
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-envelope text-primary me-2"></i>Email
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-phone text-primary me-2"></i>SĐT
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>Học vị
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-book text-primary me-2"></i>Chuyên ngành
                            </th>
                            <!-- <th class="px-4 py-3">
                                <i class="fas fa-link text-primary me-2"></i>TK liên kết
                            </th> -->
                            <th class="px-4 py-3 text-center">
                                <i class="fas fa-cog text-primary me-2"></i>Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($canbos as $cb)
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary-subtle text-primary">{{ $cb->MaCB }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $cb->TenCB }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <small class="text-muted">
                                        <i class="far fa-envelope me-1"></i>{{ $cb->Email ?? '-' }}
                                    </small>
                                </td>
                                <td class="px-4 py-3">
                                    <small class="text-muted">
                                        <i class="fas fa-mobile-alt me-1"></i>{{ $cb->SDT ?? '-' }}
                                    </small>
                                </td>
                                <td class="px-4 py-3">
                                    @if($cb->HocVi)
                                        <span class="badge bg-info-subtle text-info">{{ $cb->HocVi }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <small>{{ $cb->ChuyenNganh ?? '-' }}</small>
                                </td>
                                <!-- <td class="px-4 py-3">
                                    @if($cb->taikhoan)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-check-circle me-1"></i>{{ $cb->taikhoan->MaSo }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <i class="fas fa-times-circle me-1"></i>Chưa liên kết
                                        </span>
                                    @endif
                                </td> -->
                                <td class="px-4 py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $cb->MaCB }}" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form id="delete-form-{{ $cb->MaCB }}" action="{{ route('admin.canbo.destroy', $cb->MaCB) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ $cb->MaCB }}', '{{ $cb->TenCB }}')" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Chưa có cán bộ nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $canbos->links() }}
    </div>
</div>

{{-- MODAL THÊM --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-plus me-2"></i>Thêm Cán bộ
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.canbo.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user text-primary me-2"></i>Họ & Tên <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="TenCB" class="form-control shadow-sm" required minlength="3" maxlength="200">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-venus-mars text-primary me-2"></i>Giới tính <span class="text-danger">*</span>
                            </label>
                            <select name="GioiTinh" class="form-select shadow-sm" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar text-primary me-2"></i>Ngày sinh <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="NgaySinh" class="form-control shadow-sm" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-id-card text-primary me-2"></i>CCCD <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="MaCCCD" class="form-control shadow-sm" required pattern="[0-9]{12}" maxlength="12" title="CCCD phải có đúng 12 chữ số" placeholder="Nhập 12 số">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-phone text-primary me-2"></i>SĐT <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="SDT" class="form-control shadow-sm" required pattern="[0-9]{10}" maxlength="10" title="Số điện thoại phải có đúng 10 chữ số" placeholder="Nhập 10 số">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-envelope text-primary me-2"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="Email" class="form-control shadow-sm" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-pray text-primary me-2"></i>Tôn giáo
                            </label>
                            <input type="text" name="TonGiao" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-users text-primary me-2"></i>Dân tộc
                            </label>
                            <input type="text" name="DanToc" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>Học vị
                            </label>
                            <input type="text" name="HocVi" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-award text-primary me-2"></i>Học hàm
                            </label>
                            <input type="text" name="HocHam" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-book text-primary me-2"></i>Chuyên ngành
                            </label>
                            <input type="text" name="ChuyenNganh" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>Nơi sinh
                            </label>
                            <input type="text" name="NoiSinh" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-home text-primary me-2"></i>Hộ khẩu thường trú
                            </label>
                            <input type="text" name="HKTT" class="form-control shadow-sm">
                        </div>

                        <!-- {{-- Hidden: Tài khoản liên kết (auto-managed by system)
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-link text-primary me-2"></i>Tài khoản liên kết
                            </label>
                            <select name="MaTK" class="form-select shadow-sm">
                                <option value="">-- Không liên kết --</option>
                                @foreach($taikhoans as $tk)
                                    <option value="{{ $tk->MaTK }}">{{ $tk->MaSo }} ({{ $tk->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        --}} -->
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light">
                    <button class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL IMPORT --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-upload me-2"></i>Import Cán bộ từ Excel
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.canbo.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-excel text-primary me-2"></i>Chọn file Excel <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="excel_file" class="form-control shadow-sm" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Định dạng: .xlsx, .xls, .csv (Tối đa 2MB)</small>
                    </div>
                    <div class="alert alert-info border-0 shadow-sm">
                        <strong><i class="fas fa-info-circle me-2"></i>Lưu ý:</strong> File Excel cần có các cột: 
                        <code>TenCB</code>, <code>Email</code>, <code>SDT</code>, <code>CCCD</code>, 
                        <code>NgaySinh</code>, <code>GioiTinh</code>, <code>HocVi</code>, <code>HocHam</code>, <code>ChuyenNganh</code>
                        <br><strong>MaCB tự động tạo!</strong>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('admin.canbo.template') }}" class="btn btn-sm btn-outline-primary shadow-sm">
                            <i class="fas fa-download me-2"></i>Tải file mẫu
                        </a>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light">
                    <button class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-upload me-2"></i>Tải lên
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SỬA --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-warning text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2"></i>Sửa Cán bộ
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4" id="editContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2" id="deleteMessage">Bạn có chắc muốn xóa cán bộ này?</h5>
                <p class="text-muted mb-0">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="fas fa-trash-alt me-2"></i>Xóa
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Notification Modal (Success/Error) --}}
<div class="modal fade" id="notificationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4" id="notificationIcon"></div>
                <h4 class="fw-bold mb-3" id="notificationTitle">Thành công!</h4>
                <p class="text-muted mb-4" id="notificationMessage"></p>
                <div class="d-grid gap-2" id="notificationButtons"></div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #3e88c9ff 0%, #155ae4ff 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
}

.avatar-sm {
    width: 35px;
    height: 35px;
}

.card {
    transition: transform 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f8f9ff;
}

.btn-outline-warning:hover,
.btn-outline-danger:hover {
    transform: scale(1.05);
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.form-control:focus,
.form-select:focus {
    border-color: #1799bde1;
    box-shadow: 0 0 0 0.2rem rgba(34, 127, 208, 0.25);
}

.badge {
    font-weight: 500;
    font-size: 0.85rem;
}

.success-icon {
    width: 80px; height: 80px; margin: 0 auto;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    animation: scaleIn 0.5s ease-out;
}
.success-icon i { font-size: 40px; color: #28a745; }

.error-icon {
    width: 80px; height: 80px; margin: 0 auto;
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    animation: shake 0.5s ease-out;
}
.error-icon i { font-size: 40px; color: #dc3545; }

@keyframes scaleIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('canboTable');
    const rows = table.querySelectorAll('tbody tr');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Load modal Sửa
    document.querySelectorAll('[data-bs-target="#editModal"]').forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            fetch(`/admin/canbo/${id}/edit`)
                .then(res => res.text())
                .then(html => {
                    document.querySelector("#editContent").innerHTML = html;
                    document.querySelector("#editForm").action = `/admin/canbo/${id}`;
                });
        });
    });
});

function showNotificationModal(type, title, message) {
    const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
    document.getElementById('notificationTitle').textContent = title;
    document.getElementById('notificationMessage').innerHTML = message;
    
    if (type === 'success') {
        document.getElementById('notificationIcon').innerHTML = '<div class="success-icon"><i class="fas fa-check"></i></div>';
        document.getElementById('notificationButtons').innerHTML = `
            <button type="button" class="btn btn-success btn-lg" onclick="window.location.reload()" style="border-radius: 10px;">
                Quay lại danh sách
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">
                + Thêm mới
            </button>
        `;
        setTimeout(() => { modal.hide(); window.location.reload(); }, 2000);
    } else {
        document.getElementById('notificationIcon').innerHTML = '<div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>';
        document.getElementById('notificationButtons').innerHTML = `
            <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal" style="border-radius: 10px;">OK</button>
        `;
    }
    modal.show();
}

function confirmDelete(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteMessage').textContent = `Bạn có chắc muốn xóa cán bộ ${name}?`;
    
    document.getElementById('confirmDeleteBtn').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
    };
    
    modal.show();
}
</script>

@endsection
