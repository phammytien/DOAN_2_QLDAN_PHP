@extends('layouts.admin')

@section('title', 'Quản lý Khoa')

@section('content')
<div class="container-fluid py-4" style="background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 50%, #e0f2fe 100%); min-height: 100vh;">
    
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 6px solid #1e40d8;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="fw-bold text-dark mb-1">
                                <i class="fas fa-building text-primary me-2"></i>Quản lý Khoa
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="fas fa-database me-1"></i>
                                Tổng số: <span class="fw-semibold text-dark">{{ $khoas->count() }}</span> khoa
                            </p>
                        </div>
                        
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.khoa.export') }}" class="btn btn-success shadow-sm" style="border-radius: 10px;">
                                <i class="fas fa-file-excel me-2"></i>Xuất Excel
                            </a>
                            <button class="btn btn-info shadow-sm" id="btnImportKhoa" style="border-radius: 10px;">
                                <i class="fas fa-file-upload me-2"></i>Nhập Excel
                            </button>
                            <button class="btn btn-primary shadow-sm" id="btnAddKhoa" style="border-radius: 10px; background: linear-gradient(135deg, #1e40d8 0%, #3030cd 100%);">
                                <i class="fas fa-plus-circle me-2"></i>Thêm Khoa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" id="khoaSearch" class="form-control border-0 ps-2" placeholder="Tìm kiếm theo mã khoa, tên khoa..." style="font-size: 15px;">
            </div>
        </div>
        <div class="col-md-6 text-end mt-3 mt-md-0">
            <span class="text-muted">
                <i class="fas fa-filter me-1"></i>
                Hiển thị <span id="resultCount">{{ $khoas->count() }}</span> kết quả
            </span>
        </div>
    </div>

    {{-- Grid List Layout --}}
    <div class="row g-3" id="khoaGrid">
        @foreach($khoas as $khoa)
        <div class="col-12 khoa-item" data-id="{{ $khoa->MaKhoa }}">
            <div class="card border-0 shadow-sm hover-list-card" style="border-radius: 16px; transition: all 0.3s ease; border-left: 4px solid #1e40d8;">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        {{-- Icon & Title --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="list-icon-box me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #1e40d8 0%, #3030cd 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-university text-white" style="font-size: 28px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold khoa-name" style="color: #1e293b; font-size: 1.1rem;">{{ $khoa->TenKhoa }}</h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-id-badge me-1"></i>
                                        Mã: <span class="fw-semibold">{{ $khoa->MaKhoa }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-warning btn-edit" data-id="{{ $khoa->MaKhoa }}" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $khoa->MaKhoa }}" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $khoas->links() }}
    </div>

    @if($khoas->count() == 0)
        <div class="text-center py-5">
            <div class="empty-state-icon mb-4" style="width: 120px; height: 120px; margin: 0 auto; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-inbox" style="font-size: 60px; color: #9ca3af;"></i>
            </div>
            <h4 class="text-muted mb-2">Chưa có khoa nào</h4>
            <p class="text-muted">Hãy thêm khoa mới để bắt đầu!</p>
            <button class="btn btn-primary mt-3" id="btnAddKhoaEmpty" style="border-radius: 10px;">
                <i class="fas fa-plus-circle me-2"></i>Thêm Khoa Đầu Tiên
            </button>
        </div>
    @endif
</div>

{{-- Modal Thêm/Sửa Khoa --}}
<div class="modal fade" id="khoaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="khoaModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Thêm Khoa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="khoaForm">
                    @csrf
                    <input type="hidden" name="_method" id="khoaMethod" value="POST">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-university text-primary me-2"></i>
                            Tên Khoa <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg shadow-sm" 
                               name="TenKhoa" 
                               id="tenKhoa" 
                               placeholder="Nhập tên khoa..."
                               required>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Ví dụ: Khoa Công nghệ Thông tin
                        </small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="fas fa-save me-2"></i>Lưu thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-info text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-import me-2"></i>Nhập dữ liệu từ Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-excel text-success me-2"></i>
                            Chọn file Excel <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                               class="form-control form-control-lg shadow-sm" 
                               name="excel_file" 
                               id="importFile" 
                               accept=".xlsx,.xls,.csv" 
                               required>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Định dạng: .xlsx, .xls, .csv (Tối đa 2MB)
                        </small>
                    </div>
                    
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 fw-bold">Lưu ý quan trọng:</h6>
                                <p class="mb-0">
                                    File Excel cần có cột tiêu đề: <code class="bg-white px-2 py-1 rounded">Tên Khoa</code>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info btn-lg text-white shadow-sm">
                            <i class="fas fa-upload me-2"></i>Tải lên và xử lý
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon" style="width: 100px; height: 100px; margin: 0 auto; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 50px; color: #dc2626;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" style="color: #1f2937;">Bạn có chắc chắn muốn xóa?</h4>
                <p class="text-muted mb-2" id="deleteMessage" style="font-size: 1.1rem;">Xóa khoa này?</p>
                <div class="alert alert-warning border-0 mt-3 mb-4" style="background: #fef3c7; color: #92400e; border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <small><strong>Lưu ý:</strong> Thao tác này không thể hoàn tác!</small>
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-light px-5 py-2 fw-semibold" data-bs-dismiss="modal" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger px-5 py-2 fw-semibold" id="confirmDeleteBtn" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);">
                        <i class="fas fa-trash-alt me-2"></i>Xác nhận xóa
                    </button>
                </div>
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
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.9;
    }
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

.hover-list-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-list-card:hover {
    transform: translateX(8px);
    box-shadow: 0 10px 25px -5px rgba(30, 64, 216, 0.2), 0 10px 10px -5px rgba(30, 64, 216, 0.1) !important;
    border-left-width: 6px !important;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: scale(1.1);
    z-index: 1;
}
</style>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #1e40d8ff 0%, #3030cdff 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.avatar-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.table-hover tbody tr {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: scale(1.1);
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.form-control:focus,
.form-select:focus {
    border-color: #3e5feeff;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group .form-control:focus {
    box-shadow: none;
}

.badge {
    font-weight: 500;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.card {
    transition: all 0.3s ease;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

code {
    color: #667eea;
    font-weight: 600;
}

/* Smooth animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.4s ease;
}
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Toastr config
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};

$(function(){
    const modal = new bootstrap.Modal('#khoaModal');
    const importModal = new bootstrap.Modal('#importModal');
    const deleteModal = new bootstrap.Modal('#deleteModal');
    const notificationModal = new bootstrap.Modal('#notificationModal');
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Show notification modal
    function showNotification(type, title, message) {
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
            setTimeout(() => { notificationModal.hide(); window.location.reload(); }, 2000);
        } else {
            document.getElementById('notificationIcon').innerHTML = '<div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>';
            document.getElementById('notificationButtons').innerHTML = `
                <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal" style="border-radius: 10px;">OK</button>
            `;
        }
        notificationModal.show();
    }
    
    // Add new Khoa
    $('#btnAddKhoa, #btnAddKhoaEmpty').click(() => {
        $('#khoaModalLabel').html('<i class="fas fa-plus-circle me-2"></i>Thêm Khoa');
        $('#khoaForm')[0].reset();
        $('#khoaMethod').val('POST');
        $('#khoaForm').attr('action', '{{ route("admin.khoa.store") }}');
        modal.show();
    });

    // Open Import Modal
    $('#btnImportKhoa').click(() => {
        $('#importForm')[0].reset();
        importModal.show();
    });

    // Handle Import Form
    $('#importForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.khoa.import") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                importModal.hide();
                showNotification('success', 'Thành công!', res.message || 'Import thành công!');
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Có lỗi xảy ra khi import!';
                showNotification('error', 'Lỗi!', message);
            }
        });
    });

    // Edit Khoa
    $(document).on('click', '.btn-edit', function() {
        const item = $(this).closest('.khoa-item');
        const id = item.data('id');
        
        $('#khoaModalLabel').html('<i class="fas fa-edit me-2"></i>Cập nhật Khoa');
        $('#khoaMethod').val('PUT');
        $('#khoaForm').attr('action', '{{ url("admin/khoa") }}/' + id);
        
        $.get('{{ url("admin/khoa") }}/' + id + '/edit', function(res) {
            $('#tenKhoa').val(res.khoa.TenKhoa);
            modal.show();
        });
    });

    // Delete Khoa - Show confirmation modal
    let deleteId = null;
    let deleteItem = null;
    
    $(document).on('click', '.btn-delete', function() {
        deleteItem = $(this).closest('.khoa-item');
        deleteId = deleteItem.data('id');
        const tenKhoa = deleteItem.find('.khoa-name').text();
        
        document.getElementById('deleteMessage').textContent = `Xóa khoa "${tenKhoa}"?`;
        deleteModal.show();
    });
    
    // Confirm delete
    $('#confirmDeleteBtn').click(function() {
        if (!deleteId || !deleteItem) return;
        
        $.ajax({
            url: '{{ url("admin/khoa") }}/' + deleteId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                deleteModal.hide();
                deleteItem.fadeOut(300, function() {
                    $(this).remove();
                    updateResultCount();
                });
                showNotification('success', 'Thành công!', 'Xóa Khoa thành công!');
                deleteId = null;
                deleteItem = null;
            },
            error: function(xhr) {
                deleteModal.hide();
                showNotification('error', 'Lỗi!', xhr.responseJSON?.message || 'Không thể xóa');
            }
        });
    });

    // Submit form
    $('#khoaForm').submit(function(e) {
        e.preventDefault();
        const method = $('#khoaMethod').val();
        const url = $(this).attr('action');
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(res) {
                modal.hide();
                const successMessage = method === 'POST' ? 'Thêm Khoa thành công!' : 'Cập nhật Khoa thành công!';
                showNotification('success', 'Thành công!', successMessage);
                
                if (method === 'POST') {
                    // Add new row
                    const newRow = `
                        <tr data-id="${res.khoa.MaKhoa}" class="border-bottom">
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-primary border border-primary px-3 py-2">
                                    <i class="fas fa-id-badge me-1"></i>
                                    ${res.khoa.MaKhoa}
                                </span>
                            </td>
                            <td class="col-ten px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-icon bg-primary bg-opacity-10 rounded-circle me-3">
                                        <i class="fas fa-graduation-cap text-primary"></i>
                                    </div>
                                    <span class="fw-semibold">${res.khoa.TenKhoa}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-warning btn-edit" 
                                            title="Chỉnh sửa"
                                            data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-delete" 
                                            title="Xóa"
                                            data-bs-toggle="tooltip">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('#khoaTable tbody').append(newRow);
                    updateResultCount();
                } else {
                    // Update existing row
                    const tr = $('tr[data-id="' + res.khoa.MaKhoa + '"]');
                    tr.find('.col-ten .fw-semibold').text(res.khoa.TenKhoa);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    const errorMessages = Object.values(errors).map(err => err[0]).join('<br>');
                    showNotification('error', 'Lỗi xác thực!', errorMessages);
                } else {
                    showNotification('error', 'Lỗi!', 'Có lỗi xảy ra!');
                }
            }
        });
    });

    // Client-side search
    $('#khoaSearch').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('.khoa-item').each(function() {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.indexOf(term) !== -1);
        });
        updateResultCount();
    });
    
    // Update result count
    function updateResultCount() {
        const visibleItems = $('.khoa-item:visible').length;
        $('#resultCount').text(visibleItems);
    }
});
</script>
@endpush