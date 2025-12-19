@extends('layouts.admin')

@section('title', 'Quản lý Ngành')

@section('content')
<div class="container-fluid py-4" style="background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 50%, #fef3c7 100%); min-height: 100vh;">
    
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-left: 6px solid #3b82f6;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="fw-bold text-dark mb-1">
                                <i class="fas fa-book-open text-primary me-2"></i>Quản lý Ngành
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="fas fa-database me-1"></i>
                                Tổng số: <span class="fw-semibold text-dark">{{ $nganhs->count() }}</span> ngành
                            </p>
                        </div>
                        
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.nganh.export') }}" class="btn btn-success shadow-sm" style="border-radius: 10px;">
                                <i class="fas fa-file-excel me-2"></i>Xuất Excel
                            </a>
                            <button class="btn btn-info shadow-sm" id="btnImportNganh" style="border-radius: 10px;">
                                <i class="fas fa-file-upload me-2"></i>Nhập Excel
                            </button>
                            <button class="btn btn-primary shadow-sm" id="btnAddNganh" style="border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
                                <i class="fas fa-plus-circle me-2"></i>Thêm Ngành
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
                <input type="text" id="nganhSearch" class="form-control border-0 ps-2" placeholder="Tìm kiếm theo mã ngành, tên ngành..." style="font-size: 15px;">
            </div>
        </div>
        <div class="col-md-6 text-end mt-3 mt-md-0">
            <span class="text-muted">
                <i class="fas fa-filter me-1"></i>
                Hiển thị <span id="resultCount">{{ $nganhs->count() }}</span> kết quả
            </span>
        </div>
    </div>

    {{-- Grid List Layout --}}
    <div class="row g-3" id="nganhGrid">
        @foreach($nganhs as $nganh)
        <div class="col-12 nganh-item" data-id="{{ $nganh->MaNganh }}">
            <div class="card border-0 shadow-sm hover-list-card" style="border-radius: 16px; transition: all 0.3s ease; border-left: 4px solid #3b82f6;">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        {{-- Icon & Title --}}
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <div class="list-icon-box me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-graduation-cap text-white" style="font-size: 28px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold nganh-name" style="color: #1e293b; font-size: 1.1rem;">{{ $nganh->TenNganh }}</h6>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-id-badge me-1"></i>
                                        Mã: <span class="fw-semibold">{{ $nganh->MaNganh }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Stats --}}
                        <div class="col-md-4">
                            <div class="d-flex gap-3 justify-content-center">
                                <div class="text-center">
                                    <div class="stat-value text-primary fw-bold" style="font-size: 1.5rem;">{{ $nganh->lops_count ?? 0 }}</div>
                                    <div class="stat-label text-muted small">
                                        <i class="fas fa-users me-1"></i>Lớp
                                    </div>
                                </div>
                                @if(isset($nganh->lops))
                                <div class="text-center">
                                    <div class="stat-value text-info fw-bold" style="font-size: 1.5rem;">{{ $nganh->lops->sum('sinhviens_count') ?? 0 }}</div>
                                    <div class="stat-label text-muted small">
                                        <i class="fas fa-user-graduate me-1"></i>Sinh viên
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="col-md-3 text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-info btn-view-detail" data-id="{{ $nganh->MaNganh }}" data-name="{{ $nganh->TenNganh }}" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning btn-edit" data-id="{{ $nganh->MaNganh }}" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $nganh->MaNganh }}" title="Xóa">
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
        {{ $nganhs->links() }}
    </div>

    @if($nganhs->count() == 0)
        <div class="text-center py-5">
            <div class="empty-state-icon mb-4" style="width: 120px; height: 120px; margin: 0 auto; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-inbox" style="font-size: 60px; color: #9ca3af;"></i>
            </div>
            <h4 class="text-muted mb-2">Chưa có ngành nào</h4>
            <p class="text-muted">Hãy thêm ngành mới để bắt đầu!</p>
            <button class="btn btn-primary mt-3" id="btnAddNganhEmpty" style="border-radius: 10px;">
                <i class="fas fa-plus-circle me-2"></i>Thêm Ngành Đầu Tiên
            </button>
        </div>
    @endif
</div>

{{-- Modal for Add/Edit Nganh --}}
<div class="modal fade" id="nganhModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nganhModalLabel">Thêm Ngành</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="nganhForm">
                    @csrf
                    <input type="hidden" name="_method" id="nganhMethod" value="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên Ngành <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="TenNganh" id="tenNganh" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nhập dữ liệu từ Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Chọn file Excel <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="excel_file" id="importFile" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Định dạng: .xlsx, .xls, .csv (Tối đa 2MB)</small>
                    </div>
                    <div class="alert alert-info">
                        <strong>Lưu ý:</strong> File Excel cần có cột tiêu đề: <code>Tên Ngành</code>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Tải lên
                    </button>
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
                <p class="text-muted mb-2" id="deleteMessage" style="font-size: 1.1rem;">Xóa ngành này?</p>
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

{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold" id="detailModalTitle">
                    <i class="fas fa-info-circle me-2"></i>Chi tiết Ngành
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold" id="detailModalTitle">
                    <i class="fas fa-info-circle me-2"></i>Chi tiết Ngành
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-list-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-list-card:hover {
    transform: translateX(8px);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.2), 0 10px 10px -5px rgba(59, 130, 246, 0.1) !important;
    border-left-width: 6px !important;
}

.stat-value {
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: scale(1.1);
    z-index: 1;
}

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
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    animation: scaleIn 0.5s ease-out;
}
.success-icon i { font-size: 40px; color: #059669; }

.error-icon {
    width: 80px; height: 80px; margin: 0 auto;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    animation: shake 0.5s ease-out;
}
.error-icon i { font-size: 40px; color: #dc2626; }

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
    const modal = new bootstrap.Modal('#nganhModal');
    const importModal = new bootstrap.Modal('#importModal');
    const deleteModal = new bootstrap.Modal('#deleteModal');
    const notificationModal = new bootstrap.Modal('#notificationModal');
    
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
    
    // Add new Nganh
    $('#btnAddNganh, #btnAddNganhEmpty').click(() => {
        $('#nganhModalLabel').text('Thêm Ngành');
        $('#nganhForm')[0].reset();
        $('#nganhMethod').val('POST');
        $('#nganhForm').attr('action', '{{ route("admin.nganh.store") }}');
        modal.show();
    });

    // Open Import Modal
    $('#btnImportNganh').click(() => {
        $('#importForm')[0].reset();
        importModal.show();
    });

    // Handle Import Form
    $('#importForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.nganh.import") }}',
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

    // Edit Nganh
    $(document).on('click', '.btn-edit', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        
        $('#nganhModalLabel').text('Cập nhật Ngành');
        $('#nganhMethod').val('PUT');
        $('#nganhForm').attr('action', '{{ url("admin/nganh") }}/' + id);
        
        $.get('{{ url("admin/nganh") }}/' + id + '/edit', function(res) {
            $('#tenNganh').val(res.nganh.TenNganh);
            modal.show();
        });
    });

    // Delete Nganh - Show confirmation modal
    let deleteId = null;
    let deleteItem = null;
    
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        deleteItem = $(this).closest('.nganh-item');
        deleteId = $(this).data('id');
        const tenNganh = deleteItem.find('.nganh-name').text();
        
        document.getElementById('deleteMessage').textContent = `Xóa ngành "${tenNganh}"?`;
        deleteModal.show();
    });
    
    // Confirm delete
    $('#confirmDeleteBtn').click(function() {
        if (!deleteId || !deleteItem) return;
        
        $.ajax({
            url: '{{ url("admin/nganh") }}/' + deleteId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                deleteModal.hide();
                deleteItem.fadeOut(300, function() {
                    $(this).remove();
                    updateResultCount();
                });
                showNotification('success', 'Thành công!', 'Xóa Ngành thành công!');
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
    $('#nganhForm').submit(function(e) {
        e.preventDefault();
        const method = $('#nganhMethod').val();
        const url = $(this).attr('action');
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(res) {
                modal.hide();
                const successMessage = method === 'POST' ? 'Thêm Ngành thành công!' : 'Cập nhật Ngành thành công!';
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

    // Client-side search
    $('#nganhSearch').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('.nganh-item').each(function() {
            const nganhName = $(this).find('.nganh-name').text().toLowerCase();
            const nganhId = $(this).data('id').toString().toLowerCase();
            $(this).toggle(nganhName.indexOf(term) !== -1 || nganhId.indexOf(term) !== -1);
        });
        updateResultCount();
    });
    
    // View Detail Handler
    const detailModal = new bootstrap.Modal('#detailModal');
    
    $(document).on('click', '.btn-view-detail', function(e) {
        e.preventDefault();
        const nganhId = $(this).data('id');
        const nganhName = $(this).data('name');
        
        $('#detailModalTitle').html(`<i class="fas fa-info-circle me-2"></i>Chi tiết Ngành: ${nganhName}`);
        $('#detailModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Đang tải dữ liệu...</p>
            </div>
        `);
        
        detailModal.show();
        
        // Load detail data
        $.get(`{{ url('admin/nganh') }}/${nganhId}/detail`, function(res) {
            if (res.lops && res.lops.length > 0) {
                let html = '<div class="accordion" id="lopAccordion">';
                
                res.lops.forEach((lop, index) => {
                    const collapseId = `collapse${lop.MaLop}`;
                    html += `
                        <div class="accordion-item mb-3 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                            <h2 class="accordion-header">
                                <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                                    <div class="d-flex align-items-center w-100">
                                        <i class="fas fa-users text-primary me-3"></i>
                                        <div class="flex-grow-1">
                                            <strong>${lop.TenLop}</strong>
                                            <small class="text-muted ms-2">(${lop.MaLop})</small>
                                        </div>
                                        <span class="badge bg-info me-3">${lop.khoa ? lop.khoa.TenKhoa : 'N/A'}</span>
                                        <span class="badge bg-primary me-3">${lop.sinhviens ? lop.sinhviens.length : 0} SV</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="${collapseId}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" 
                                 data-bs-parent="#lopAccordion">
                                <div class="accordion-body p-0">`;
                    
                    if (lop.sinhviens && lop.sinhviens.length > 0) {
                        html += `
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="15%">Mã SV</th>
                                            <th width="30%">Họ và tên</th>
                                            <th width="10%">Giới tính</th>
                                            <th width="20%">Email</th>
                                            <th width="15%">SĐT</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        
                        lop.sinhviens.forEach((sv, svIndex) => {
                            html += `
                                <tr>
                                    <td class="text-center">${svIndex + 1}</td>
                                    <td><span class="badge bg-info">${sv.MaSV}</span></td>
                                    <td><strong>${sv.TenSV}</strong></td>
                                    <td>${sv.GioiTinh || '-'}</td>
                                    <td><small>${sv.Email || '-'}</small></td>
                                    <td>${sv.SDT || '-'}</td>
                                </tr>`;
                        });
                        
                        html += `
                                    </tbody>
                                </table>
                            </div>`;
                    } else {
                        html += `
                            <div class="alert alert-info m-3">
                                <i class="fas fa-info-circle me-2"></i>Chưa có sinh viên nào trong lớp này.
                            </div>`;
                    }
                    
                    html += `
                                </div>
                            </div>
                        </div>`;
                });
                
                html += '</div>';
                $('#detailModalBody').html(html);
            } else {
                $('#detailModalBody').html(`
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Ngành này chưa có lớp nào.
                    </div>
                `);
            }
        }).fail(function() {
            $('#detailModalBody').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle me-2"></i>Không thể tải dữ liệu. Vui lòng thử lại!
                </div>
            `);
        });
    });
    
    function updateResultCount() {
        const visibleCards = $('.nganh-card:visible').length;
        $('#resultCount').text(visibleCards);
    }
});
</script>
@endpush