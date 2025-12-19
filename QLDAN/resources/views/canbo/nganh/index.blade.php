@extends('layouts.canbo')

@section('title', 'Quản lý Ngành')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="fas fa-book me-2"></i>Quản lý Ngành
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-database me-1"></i>
                        Tổng số: <span class="fw-semibold text-dark">{{ $nganhs->count() }}</span> ngành
                    </p>
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('canbo.nganh.export') }}" class="btn btn-success shadow-sm">
                        <i class="fas fa-file-excel me-2"></i>Xuất Excel
                    </a>
                    <button class="btn btn-info shadow-sm" id="btnImportNganh">
                        <i class="fas fa-file-upload me-2"></i>Nhập Excel
                    </button>
                    <button class="btn btn-primary shadow-sm" id="btnAddNganh">
                        <i class="fas fa-plus-circle me-2"></i>Thêm Ngành
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
                       id="nganhSearch" 
                       class="form-control border-start-0 ps-0" 
                       placeholder="Tìm kiếm theo mã ngành, tên ngành...">
            </div>
        </div>
        <div class="col-md-6 text-end mt-3 mt-md-0">
            <span class="text-muted">
                <i class="fas fa-filter me-1"></i>
                Hiển thị <span id="resultCount">{{ $nganhs->count() }}</span> kết quả
            </span>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="row g-4" id="nganhGrid">
        @foreach($nganhs as $nganh)
        <div class="col-md-6 col-lg-4 col-xl-3 nganh-card" data-id="{{ $nganh->MaNganh }}">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-gradient-purple">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item btn-view-detail" href="#" data-id="{{ $nganh->MaNganh }}" data-name="{{ $nganh->TenNganh }}">
                                    <i class="fas fa-eye text-info me-2"></i>Xem chi tiết
                                </a></li>
                                <li><a class="dropdown-item btn-edit" href="#" data-id="{{ $nganh->MaNganh }}">
                                    <i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa
                                </a></li>
                                <li><a class="dropdown-item btn-delete" href="#" data-id="{{ $nganh->MaNganh }}">
                                    <i class="fas fa-trash text-danger me-2"></i>Xóa
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <h5 class="card-title fw-bold mb-2 nganh-name">{{ $nganh->TenNganh }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-id-badge me-1"></i>
                        Mã: <span class="fw-semibold">{{ $nganh->MaNganh }}</span>
                    </p>
                    
                    <div class="stats-row">
                        <div class="stat-item mb-2">
                            <i class="fas fa-users text-primary"></i>
                            <span class="ms-2">{{ $nganh->lops_count ?? 0 }} lớp</span>
                        </div>
                        @if(isset($nganh->lops))
                        <div class="stat-item">
                            <i class="fas fa-user-graduate text-info"></i>
                            <span class="ms-2">{{ $nganh->lops->sum('sinhviens_count') ?? 0 }} sinh viên</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($nganhs->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Chưa có ngành nào. Hãy thêm ngành mới!</p>
        </div>
    @endif
</div>

{{-- Modal Thêm/Sửa Ngành --}}
<div class="modal fade" id="nganhModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="nganhModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Thêm Ngành
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="nganhForm">
                    @csrf
                    <input type="hidden" name="_method" id="nganhMethod" value="POST">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-book text-primary me-2"></i>
                            Tên Ngành <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg shadow-sm" 
                               name="TenNganh" 
                               id="tenNganh" 
                               placeholder="Nhập tên ngành..."
                               required>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Ví dụ: Công nghệ Thông tin
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
                                    File Excel cần có cột tiêu đề: <code class="bg-white px-2 py-1 rounded">Tên Ngành</code>
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

{{-- Delete & Notification Modals --}}
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
                <p class="text-muted mb-2" id="deleteMessage">Xóa ngành này?</p>
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

{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-gradient-primary text-white border-0">
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

.bg-gradient-purple {
    background: linear-gradient(135deg, #ab47bc 0%, #8e24aa 100%);
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
    const modal = new bootstrap.Modal('#nganhModal');
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
    
    $('#btnAddNganh').click(() => {
        $('#nganhModalLabel').html('<i class="fas fa-plus-circle me-2"></i>Thêm Ngành');
        $('#nganhForm')[0].reset();
        $('#nganhMethod').val('POST');
        $('#nganhForm').attr('action', '{{ route("canbo.nganh.store") }}');
        modal.show();
    });

    $('#btnImportNganh').click(() => {
        $('#importForm')[0].reset();
        importModal.show();
    });

    $('#importForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("canbo.nganh.import") }}',
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
        
        $('#nganhModalLabel').html('<i class="fas fa-edit me-2"></i>Cập nhật Ngành');
        $('#nganhMethod').val('PUT');
        $('#nganhForm').attr('action', '{{ url("canbo/nganh") }}/' + id);
        
        $.get('{{ url("canbo/nganh") }}/' + id + '/edit', function(res) {
            $('#tenNganh').val(res.nganh.TenNganh);
            modal.show();
        });
    });

    let deleteId = null;
    let deleteCard = null;
    
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        deleteCard = $(this).closest('.nganh-card');
        deleteId = $(this).data('id');
        const tenNganh = deleteCard.find('.nganh-name').text();
        
        document.getElementById('deleteMessage').textContent = `Xóa ngành "${tenNganh}"?`;
        deleteModal.show();
    });
    
    $('#confirmDeleteBtn').click(function() {
        if (!deleteId || !deleteCard) return;
        
        $.ajax({
            url: '{{ url("canbo/nganh") }}/' + deleteId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                deleteModal.hide();
                deleteCard.fadeOut(300, function() {
                    $(this).remove();
                    updateResultCount();
                });
                showNotification('success', 'Thành công!', 'Xóa Ngành thành công!');
                deleteId = null;
                deleteCard = null;
            },
            error: function(xhr) {
                deleteModal.hide();
                showNotification('error', 'Lỗi!', xhr.responseJSON?.message || 'Không thể xóa');
            }
        });
    });

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

    $('#nganhSearch').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('.nganh-card').each(function() {
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
        $.get(`{{ url('canbo/nganh') }}/${nganhId}/detail`, function(res) {
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