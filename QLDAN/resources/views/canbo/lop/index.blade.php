@extends('layouts.canbo')

@section('title', 'Quản lý Lớp')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="fas fa-users me-2"></i>Quản lý Lớp
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-database me-1"></i>
                        Tổng số: <span class="fw-semibold text-dark">{{ $lops->total() }}</span> lớp
                    </p>
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('canbo.lop.export') }}" class="btn btn-success shadow-sm">
                        <i class="fas fa-file-excel me-2"></i>Xuất Excel
                    </a>
                    <button class="btn btn-info shadow-sm" id="btnImportLop">
                        <i class="fas fa-file-upload me-2"></i>Nhập Excel
                    </button>
                    <button class="btn btn-primary shadow-sm" id="btnAddLop">
                        <i class="fas fa-plus-circle me-2"></i>Thêm Lớp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" id="lopSearch" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm lớp...">
            </div>
        </div>
        <div class="col-md-3 mt-3 mt-md-0">
            <select id="filterKhoa" class="form-select">
                <option value="">Tất cả Khoa</option>
                @foreach($khoas as $khoa)
                    <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mt-3 mt-md-0">
            <select id="filterNganh" class="form-select">
                <option value="">Tất cả Ngành</option>
                @foreach($nganhs as $nganh)
                    <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 text-end mt-3 mt-md-0">
            <span class="text-muted">
                <span id="resultCount">{{ $lops->count() }}</span> kết quả
            </span>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="row g-4" id="lopGrid">
        @foreach($lops as $lop)
        <div class="col-md-6 col-lg-4 col-xl-3 lop-card" 
             data-id="{{ $lop->MaLop }}"
             data-khoa-id="{{ $lop->MaKhoa }}"
             data-nganh-id="{{ $lop->MaNganh }}">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-gradient-cyan">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('canbo.lop.detail', $lop->MaLop) }}">
                                    <i class="fas fa-eye text-info me-2"></i>Xem chi tiết
                                </a></li>
                                <li><a class="dropdown-item btn-edit" href="#" data-id="{{ $lop->MaLop }}">
                                    <i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa
                                </a></li>
                                <li><a class="dropdown-item btn-delete" href="#" data-id="{{ $lop->MaLop }}">
                                    <i class="fas fa-trash text-danger me-2"></i>Xóa
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <h5 class="card-title fw-bold mb-2 lop-name">{{ $lop->TenLop }}</h5>
                    <p class="text-muted small mb-2">
                        <i class="fas fa-id-badge me-1"></i>
                        Mã: <span class="fw-semibold">{{ $lop->MaLop }}</span>
                    </p>
                    
                    @if($lop->khoa)
                    <p class="text-muted small mb-2">
                        <i class="fas fa-building me-1"></i>
                        {{ $lop->khoa->TenKhoa }}
                    </p>
                    @endif
                    
                    @if($lop->nganh)
                    <p class="text-muted small mb-3">
                        <i class="fas fa-book me-1"></i>
                        {{ $lop->nganh->TenNganh }}
                    </p>
                    @endif
                    
                    <div class="stats-row">
                        <div class="stat-item">
                            <i class="fas fa-users text-primary"></i>
                            <span class="ms-2">{{ $lop->sinhviens_count ?? 0 }} sinh viên</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $lops->links() }}
    </div>

    @if($lops->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Chưa có lớp nào. Hãy thêm lớp mới!</p>
        </div>
    @endif
</div>

{{-- Modal Thêm/Sửa Lớp --}}
<div class="modal fade" id="lopModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="lopModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Thêm Lớp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="lopForm">
                    @csrf
                    <input type="hidden" name="_method" id="lopMethod" value="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Tên Lớp <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" name="TenLop" id="tenLop" required 
                               placeholder="Nhập tên lớp (VD: CNTT2021A)">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-building text-primary me-2"></i>Khoa
                        </label>
                        <select class="form-select shadow-sm" name="MaKhoa" id="maKhoa">
                            <option value="">-- Chọn Khoa --</option>
                            @foreach($khoas as $khoa)
                                <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-graduation-cap text-primary me-2"></i>Ngành
                        </label>
                        <select class="form-select shadow-sm" name="MaNganh" id="maNganh">
                            <option value="">-- Chọn Ngành --</option>
                            @foreach($nganhs as $nganh)
                                <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                            @endforeach
                        </select>
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
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-excel text-success me-2"></i>Chọn file Excel <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control shadow-sm" name="excel_file" id="importFile" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Định dạng: .xlsx, .xls, .csv (Tối đa 2MB)</small>
                    </div>
                    <div class="alert alert-info border-0 shadow-sm">
                        <strong><i class="fas fa-info-circle me-2"></i>Lưu ý:</strong> File Excel cần có các cột: 
                        <code>Tên Lớp</code>, <code>Khoa</code>, <code>Ngành</code>
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

{{-- Delete & Notification Modals (same as Khoa) --}}
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
                <p class="text-muted mb-2" id="deleteMessage">Xóa lớp này?</p>
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

.bg-gradient-cyan {
    background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
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
    const modal = new bootstrap.Modal('#lopModal');
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
    
    $('#btnAddLop').click(() => {
        $('#lopModalLabel').html('<i class="fas fa-plus-circle me-2"></i>Thêm Lớp');
        $('#lopForm')[0].reset();
        $('#lopMethod').val('POST');
        $('#lopForm').attr('action', '{{ route("canbo.lop.store") }}');
        modal.show();
    });

    $('#btnImportLop').click(() => {
        $('#importForm')[0].reset();
        importModal.show();
    });

    $('#importForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("canbo.lop.import") }}',
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
        
        $('#lopModalLabel').html('<i class="fas fa-edit me-2"></i>Cập nhật Lớp');
        $('#lopMethod').val('PUT');
        $('#lopForm').attr('action', '{{ url("canbo/lop") }}/' + id);
        
        $.get('{{ url("canbo/lop") }}/' + id + '/edit', function(res) {
            $('#tenLop').val(res.lop.TenLop);
            $('#maKhoa').val(res.lop.MaKhoa);
            $('#maNganh').val(res.lop.MaNganh);
            modal.show();
        });
    });

    let deleteId = null;
    let deleteCard = null;
    
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        deleteCard = $(this).closest('.lop-card');
        deleteId = $(this).data('id');
        const tenLop = deleteCard.find('.lop-name').text();
        
        document.getElementById('deleteMessage').textContent = `Xóa lớp "${tenLop}"?`;
        deleteModal.show();
    });
    
    $('#confirmDeleteBtn').click(function() {
        if (!deleteId || !deleteCard) return;
        
        $.ajax({
            url: '{{ url("canbo/lop") }}/' + deleteId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                deleteModal.hide();
                deleteCard.fadeOut(300, function() {
                    $(this).remove();
                    updateResultCount();
                });
                showNotification('success', 'Thành công!', 'Xóa Lớp thành công!');
                deleteId = null;
                deleteCard = null;
            },
            error: function(xhr) {
                deleteModal.hide();
                showNotification('error', 'Lỗi!', xhr.responseJSON?.message || 'Không thể xóa');
            }
        });
    });

    $('#lopForm').submit(function(e) {
        e.preventDefault();
        const method = $('#lopMethod').val();
        const url = $(this).attr('action');
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(res) {
                modal.hide();
                const successMessage = method === 'POST' ? 'Thêm Lớp thành công!' : 'Cập nhật Lớp thành công!';
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

    function filterLops() {
        const searchTerm = $('#lopSearch').val().toLowerCase();
        const selectedKhoa = $('#filterKhoa').val();
        const selectedNganh = $('#filterNganh').val();
        
        $('.lop-card').each(function() {
            const lopName = $(this).find('.lop-name').text().toLowerCase();
            const khoaId = $(this).data('khoa-id');
            const nganhId = $(this).data('nganh-id');
            
            const matchSearch = lopName.indexOf(searchTerm) !== -1;
            const matchKhoa = !selectedKhoa || khoaId == selectedKhoa;
            const matchNganh = !selectedNganh || nganhId == selectedNganh;
            
            $(this).toggle(matchSearch && matchKhoa && matchNganh);
        });
        updateResultCount();
    }

    $('#lopSearch').on('keyup', filterLops);
    $('#filterKhoa').on('change', filterLops);
    $('#filterNganh').on('change', filterLops);
    
    function updateResultCount() {
        const visibleCards = $('.lop-card:visible').length;
        $('#resultCount').text(visibleCards);
    }
});
</script>
@endpush
