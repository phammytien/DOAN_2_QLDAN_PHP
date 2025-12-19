@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="fas fa-bell me-2"></i>Quản lý Thông báo
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Tổng số: <span class="fw-semibold text-dark">{{ $tbs->count() }}</span> thông báo
                    </p>
                </div>
                <button class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i>Thêm thông báo mới
                </button>
            </div>
        </div>
    </div>

    <!-- FILTER TABS -->
    <div class="filter-tabs mb-4">
        <div class="nav nav-pills" role="tablist">
            <a class="nav-link {{ !request('doituong') ? 'active' : '' }}" href="{{ route('admin.thongbao.index') }}">
                <i class="fas fa-list me-2"></i>Tất cả
            </a>
            <a class="nav-link {{ request('doituong') == 'SV' ? 'active' : '' }}" href="{{ route('admin.thongbao.index', ['doituong' => 'SV']) }}">
                <i class="fas fa-user-graduate me-2"></i>Sinh viên
            </a>
            <a class="nav-link {{ request('doituong') == 'GV' ? 'active' : '' }}" href="{{ route('admin.thongbao.index', ['doituong' => 'GV']) }}">
                <i class="fas fa-chalkboard-teacher me-2"></i>Giảng viên
            </a>
            <a class="nav-link {{ request('doituong') == 'CB' ? 'active' : '' }}" href="{{ route('admin.thongbao.index', ['doituong' => 'CB']) }}">
                <i class="fas fa-user-tie me-2"></i>Cán bộ
            </a>
        </div>
    </div>

    <!-- Success Modal Notification (Centered) -->
    @if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-body text-center p-5" style="background: linear-gradient(135deg, #2d33e9ff 0%, #1e51caff 100%);">
                    <div class="success-checkmark mb-3">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="icon-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Thành công!</h4>
                    <p class="text-white mb-0" style="font-size: 0.95rem;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Table Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-list-alt me-2"></i>Danh sách thông báo
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">
                                <i class="fas fa-comment-dots text-primary me-2"></i>Nội dung
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-user-tie text-primary me-2"></i>Người đăng
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-users text-primary me-2"></i>Đối tượng
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-paperclip text-primary me-2"></i>File
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-clock text-primary me-2"></i>Thời gian
                            </th>
                            <th class="px-4 py-3 text-center">
                                <i class="fas fa-cog text-primary me-2"></i>Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tbs as $tb)
                        <tr class="border-bottom">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        @if($tb->MucDo == 'Khan')
                                            <span class="badge bg-danger rounded-pill">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                        @elseif($tb->MucDo == 'QuanTrong')
                                            <span class="badge bg-warning rounded-pill">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                        @else
                                            <span class="badge bg-info rounded-pill">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-truncate" style="max-width: 300px;">
                                        {{ $tb->NoiDung }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <span>{{ $tb->canBo->TenCB ?? '---' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($tb->DoiTuongNhan == 'SV')
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="fas fa-user-graduate me-1"></i>Sinh viên
                                    </span>
                                @elseif($tb->DoiTuongNhan == 'GV')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>Giảng viên
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        <i class="fas fa-globe me-1"></i>Tất cả
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if($tb->TenFile)
                                    <a href="{{ asset('storage/uploads/thongbao/' . $tb->TenFile) }}" 
                                       target="_blank"
                                       class="text-decoration-none text-primary">
                                        <i class="fas fa-file-download me-1"></i>
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;">
                                            {{ $tb->TenFile }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-minus-circle me-1"></i>Không có
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $tb->TGDang }}
                                </small>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning btn-edit" 
                                            data-id="{{ $tb->MaTB }}"
                                            data-bs-toggle="tooltip" 
                                            title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form id="deleteForm{{ $tb->MaTB }}" action="{{ route('admin.thongbao.destroy', $tb->MaTB) }}"
                                          method="POST"
                                          style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-delete"
                                                data-id="{{ $tb->MaTB }}"
                                                data-bs-toggle="tooltip"
                                                title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL THÊM THÔNG BÁO --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-primary text-white border-0" style="background: linear-gradient(135deg, #2d33e9ff 0%, #1e51caff 100%);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bullhorn me-2"></i>Tạo thông báo mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.thongbao.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-align-left text-primary me-2"></i>
                            Nội dung thông báo <span class="text-danger">*</span>
                        </label>
                        <textarea name="NoiDung" class="form-control shadow-sm" rows="4" placeholder="Nhập nội dung thông báo..." required>{{ old('NoiDung') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-shield text-primary me-2"></i>
                                Người đăng
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-tie"></i></span>
                                <select name="MaCB" class="form-select shadow-sm">
                                    <option value="">-- Chọn cán bộ --</option>
                                    @foreach($cbs as $cb)
                                        <option value="{{ $cb->MaCB }}">{{ $cb->TenCB }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-friends text-primary me-2"></i>
                                Đối tượng nhận <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                <select name="DoiTuongNhan" class="form-select shadow-sm" required>
                                    <option value="TatCa">🌐 Tất cả</option>
                                    <option value="SV">🎓 Sinh viên</option>
                                    <option value="GV">👨‍🏫 Giảng viên</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-flag text-primary me-2"></i>
                                Mức độ thông báo <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-exclamation-circle"></i></span>
                                <select name="MucDo" class="form-select shadow-sm" required>
                                    <option value="Khan">🚨 Khẩn cấp</option>
                                    <option value="QuanTrong">⚠️ Quan trọng</option>
                                    <option value="BinhThuong">ℹ️ Bình thường</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-cloud-upload-alt text-primary me-2"></i>
                                File đính kèm
                            </label>
                            <input type="file" name="TenFile" class="form-control shadow-sm">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>Tối đa 5MB
                            </small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i>Đăng thông báo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Thông Báo -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-warning text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa thông báo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div id="editContent">
                    <!-- AJAX Content will be loaded here -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
                <h5 class="fw-bold mb-2">Bạn có chắc muốn xóa thông báo này?</h5>
                <p class="text-muted mb-0">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDelete">
                    <i class="fas fa-trash-alt me-2"></i>Xóa
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #2d33e9ff 0%, #1e51caff 100%);
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
    cursor: pointer;
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
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.badge {
    font-weight: 500;
    font-size: 0.85rem;
}

.filter-tabs .nav-pills .nav-link {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 20px;
    margin-right: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.filter-tabs .nav-pills .nav-link:hover {
    background: #f3f4f6;
    border-color: #2d33e9ff;
    color: #2d33e9ff;
    transform: translateY(-2px);
}

.filter-tabs .nav-pills .nav-link.active {
    background: linear-gradient(135deg, #2d33e9ff 0%, #1e51caff 100%);
    border-color: #2d33e9ff;
    color: white;
    box-shadow: 0 4px 12px rgba(45, 51, 233, 0.3);
}

/* Success Checkmark Animation */
.success-checkmark {
    width: 80px;
    height: 80px;
    margin: 0 auto;
}

.success-checkmark .check-icon {
    width: 80px;
    height: 80px;
    position: relative;
    border-radius: 50%;
    box-sizing: content-box;
    border: 4px solid rgba(255, 255, 255, 0.3);
}

.success-checkmark .check-icon::before {
    top: 3px;
    left: -2px;
    width: 30px;
    transform-origin: 100% 50%;
    border-radius: 100px 0 0 100px;
}

.success-checkmark .check-icon::after {
    top: 0;
    left: 30px;
    width: 60px;
    transform-origin: 0 50%;
    border-radius: 0 100px 100px 0;
    animation: rotate-circle 4.25s ease-in;
}

.success-checkmark .check-icon::before,
.success-checkmark .check-icon::after {
    content: '';
    height: 100px;
    position: absolute;
    background: #FFFFFF;
    transform: rotate(-45deg);
}

.success-checkmark .check-icon .icon-line {
    height: 5px;
    background-color: #FFFFFF;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}

.success-checkmark .check-icon .icon-line.line-tip {
    top: 46px;
    left: 14px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}

.success-checkmark .check-icon .icon-line.line-long {
    top: 38px;
    right: 8px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

.success-checkmark .check-icon .icon-circle {
    top: -4px;
    left: -4px;
    z-index: 10;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    position: absolute;
    box-sizing: content-box;
    border: 4px solid rgba(255, 255, 255, 0.5);
}

.success-checkmark .check-icon .icon-fix {
    top: 8px;
    width: 5px;
    left: 26px;
    z-index: 1;
    height: 85px;
    position: absolute;
    transform: rotate(-45deg);
    background-color: transparent;
}

@keyframes rotate-circle {
    0% {
        transform: rotate(-45deg);
    }
    5% {
        transform: rotate(-45deg);
    }
    12% {
        transform: rotate(-405deg);
    }
    100% {
        transform: rotate(-405deg);
    }
}

@keyframes icon-line-tip {
    0% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    54% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    70% {
        width: 50px;
        left: -8px;
        top: 37px;
    }
    84% {
        width: 17px;
        left: 21px;
        top: 48px;
    }
    100% {
        width: 25px;
        left: 14px;
        top: 46px;
    }
}

@keyframes icon-line-long {
    0% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    65% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    84% {
        width: 55px;
        right: 0px;
        top: 35px;
    }
    100% {
        width: 47px;
        right: 8px;
        top: 38px;
    }
}

/* Modal fade-in animation */
#successModal .modal-content {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-show success modal if exists
    const successModalEl = document.getElementById('successModal');
    if (successModalEl) {
        const successModal = new bootstrap.Modal(successModalEl);
        successModal.show();
        
        // Auto-hide after 2.5 seconds
        setTimeout(() => {
            successModal.hide();
        }, 2500);
    }

    // Xử lý nút Sửa
    const editButtons = document.querySelectorAll('.btn-edit');
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const editForm = document.getElementById('editForm');
    const editContent = document.getElementById('editContent');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            
            // Reset form action
            editForm.action = `/admin/thongbao/${id}`;
            
            // Show modal
            editModal.show();
            
            // Show loading
            editContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            // Fetch data
            fetch(`/admin/thongbao/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                editContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                editContent.innerHTML = '<div class="alert alert-danger m-3">Có lỗi xảy ra khi tải dữ liệu!</div>';
            });
        });
    });

    // Xử lý nút Xóa
    const deleteButtons = document.querySelectorAll('.btn-delete');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    let deleteFormId = null;

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            deleteFormId = 'deleteForm' + this.dataset.id;
            deleteModal.show();
        });
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteFormId) {
            document.getElementById(deleteFormId).submit();
        }
    });
});
</script>
@endsection