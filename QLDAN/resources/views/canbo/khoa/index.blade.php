@extends('layouts.canbo')

@section('title', 'Quản lý Khoa')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="fas fa-building me-2"></i>Quản lý Khoa
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-database me-1"></i>
                        Tổng số: <span class="fw-semibold text-dark">{{ $khoas->count() }}</span> khoa
                    </p>
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('canbo.khoa.export') }}" class="btn btn-success shadow-sm">
                        <i class="fas fa-file-excel me-2"></i>Xuất Excel
                    </a>
                    <button class="btn btn-info shadow-sm" id="btnImportKhoa">
                        <i class="fas fa-file-upload me-2"></i>Nhập Excel
                    </button>
                    <button class="btn btn-primary shadow-sm" id="btnAddKhoa">
                        <i class="fas fa-plus-circle me-2"></i>Thêm Khoa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" 
                       id="khoaSearch" 
                       class="form-control border-start-0 ps-0" 
                       placeholder="Tìm kiếm theo mã khoa, tên khoa...">
            </div>
        </div>
        <div class="col-md-6 text-end mt-3 mt-md-0">
            <span class="text-muted">
                <i class="fas fa-filter me-1"></i>
                Hiển thị <span id="resultCount">{{ $khoas->count() }}</span> kết quả
            </span>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="row g-4" id="khoaGrid">
        @foreach($khoas as $khoa)
        <div class="col-md-6 col-lg-4 col-xl-3 khoa-card" data-id="{{ $khoa->MaKhoa }}">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item btn-edit" href="#" data-id="{{ $khoa->MaKhoa }}">
                                    <i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa
                                </a></li>
                                <li><a class="dropdown-item btn-delete" href="#" data-id="{{ $khoa->MaKhoa }}">
                                    <i class="fas fa-trash text-danger me-2"></i>Xóa
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <h5 class="card-title fw-bold mb-2 khoa-name">{{ $khoa->TenKhoa }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-id-badge me-1"></i>
                        Mã: <span class="fw-semibold">{{ $khoa->MaKhoa }}</span>
                    </p>
                    
                    <div class="stats-row">
                        <div class="stat-item">
                            <i class="fas fa-users text-primary"></i>
                            <span class="ms-2">{{ $khoa->lops_count ?? 0 }} lớp</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($khoas->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Chưa có khoa nào. Hãy thêm khoa mới!</p>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3">Bạn có chắc chắn muốn xóa?</h4>
                <p class="text-muted mb-2" id="deleteMessage">Xóa khoa này?</p>
                <div class="alert alert-warning border-0 mt-3 mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <small><strong>Lưu ý:</strong> Thao tác này không thể hoàn tác!</small>
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-light px-5 py-2 fw-semibold" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger px-5 py-2 fw-semibold" id="confirmDeleteBtn">
                        <i class="fas fa-trash-alt me-2"></i>Xác nhận xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Notification Modal --}}
<div class="modal fade" id="notificationModal" tabindex="-1" data-bs-backdrop="static">
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
.hover-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(66, 165, 245, 0.2) !important;
}

.icon-box {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stats-row {
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
}

.stat-item {
    display: flex;
    align-items: center;
    color: #666;
    font-size: 14px;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
}

.warning-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

.warning-icon i {
    font-size: 50px;
    color: #dc2626;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.9; }
}

.success-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.5s ease-out;
}

.success-icon i {
    font-size: 40px;
    color: #28a745;
}

.error-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: shake 0.5s ease-out;
}

.error-icon i {
    font-size: 40px;
    color: #dc3545;
}

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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){
    const modal = new bootstrap.Modal('#khoaModal');
    const importModal = new bootstrap.Modal('#importModal');
    const deleteModal = new bootstrap.Modal('#deleteModal');
    const notificationModal = new bootstrap.Modal('#notificationModal');
    
    function showNotification(type, title, message) {
        document.getElementById('notificationTitle').textContent = title;
        document.getElementById('notificationMessage').innerHTML = message;
        
        if (type === 'success') {
            document.getElementById('notificationIcon').innerHTML = '<div class="success-icon"><i class="fas fa-check"></i></div>';
            document.getElementById('notificationButtons').innerHTML = `
                <button type="button" class="btn btn-success btn-lg" onclick="window.location.reload()" style="border-radius: 10px;">
                    Quay lại danh sách
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
    
    $('#btnAddKhoa').click(() => {
        $('#khoaModalLabel').html('<i class="fas fa-plus-circle me-2"></i>Thêm Khoa');
        $('#khoaForm')[0].reset();
        $('#khoaMethod').val('POST');
        $('#khoaForm').attr('action', '{{ route("canbo.khoa.store") }}');
        modal.show();
    });

    $('#btnImportKhoa').click(() => {
        $('#importForm')[0].reset();
        importModal.show();
    });

    $('#importForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("canbo.khoa.import") }}',
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

    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        $('#khoaModalLabel').html('<i class="fas fa-edit me-2"></i>Cập nhật Khoa');
        $('#khoaMethod').val('PUT');
        $('#khoaForm').attr('action', '{{ url("canbo/khoa") }}/' + id);
        
        $.get('{{ url("canbo/khoa") }}/' + id + '/edit', function(res) {
            $('#tenKhoa').val(res.khoa.TenKhoa);
            modal.show();
        });
    });

    let deleteId = null;
    let deleteCard = null;
    
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        deleteCard = $(this).closest('.khoa-card');
        deleteId = $(this).data('id');
        const tenKhoa = deleteCard.find('.khoa-name').text();
        
        document.getElementById('deleteMessage').textContent = `Xóa khoa "${tenKhoa}"?`;
        deleteModal.show();
    });
    
    $('#confirmDeleteBtn').click(function() {
        if (!deleteId || !deleteCard) return;
        
        $.ajax({
            url: '{{ url("canbo/khoa") }}/' + deleteId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                deleteModal.hide();
                deleteCard.fadeOut(300, function() {
                    $(this).remove();
                    updateResultCount();
                });
                showNotification('success', 'Thành công!', 'Xóa Khoa thành công!');
                deleteId = null;
                deleteCard = null;
            },
            error: function(xhr) {
                deleteModal.hide();
                showNotification('error', 'Lỗi!', xhr.responseJSON?.message || 'Không thể xóa');
            }
        });
    });

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

    $('#khoaSearch').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('.khoa-card').each(function() {
            const khoaName = $(this).find('.khoa-name').text().toLowerCase();
            const khoaId = $(this).data('id').toString().toLowerCase();
            $(this).toggle(khoaName.indexOf(term) !== -1 || khoaId.indexOf(term) !== -1);
        });
        updateResultCount();
    });
    
    function updateResultCount() {
        const visibleCards = $('.khoa-card:visible').length;
        $('#resultCount').text(visibleCards);
    }
});
</script>
@endpush