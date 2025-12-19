@extends('layouts.canbo')

@section('title', 'Quản lý Giảng viên')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Quản lý Giảng viên
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-database me-1"></i>
                        Tổng số: <span class="fw-semibold text-dark">{{ $giangviens->total() }}</span> giảng viên
                    </p>
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('canbo.giangvien.export') }}" class="btn btn-success shadow-sm">
                        <i class="fas fa-file-excel me-2"></i>Xuất Excel
                    </a>
                    <button class="btn btn-info shadow-sm text-white" id="btnImportGV">
                        <i class="fas fa-file-upload me-2"></i>Nhập Excel
                    </button>
                    <button class="btn btn-primary shadow-sm" id="btnAddGV">
                        <i class="fas fa-plus-circle me-2"></i>Thêm Giảng viên
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" id="gvSearch" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên, mã GV, email...">
            </div>
        </div>
        <div class="col-md-6 text-end mt-3 mt-md-0 align-self-center">
            <span class="text-muted">
                <span id="resultCount" class="fw-bold text-primary">{{ $giangviens->count() }}</span> kết quả
            </span>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="row g-4" id="gvGrid">
        @foreach($giangviens as $gv)
        <div class="col-md-6 col-lg-4 col-xl-3 gv-card" data-id="{{ $gv->MaGV }}">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-light-orange text-orange">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li><a class="dropdown-item py-2" href="{{ route('canbo.giangvien.detail', $gv->MaGV) }}">
                                    <i class="fas fa-eye text-info me-2"></i>Xem chi tiết
                                </a></li>
                                <li><a class="dropdown-item py-2 btn-edit" href="#" data-id="{{ $gv->MaGV }}">
                                    <i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 btn-delete text-danger" href="#" data-id="{{ $gv->MaGV }}">
                                    <i class="fas fa-trash me-2"></i>Xóa
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <h6 class="card-title fw-bold mb-1 gv-name text-dark">{{ $gv->TenGV }}</h6>
                    <p class="text-muted small mb-3">
                        Mã GV: <span class="fw-semibold">{{ $gv->MaGV }}</span>
                    </p>
                    
                    <div class="info-list">
                        @if($gv->Email)
                        <div class="d-flex align-items-center mb-2 text-muted small">
                            <i class="fas fa-envelope me-2 text-primary opacity-50" style="width: 16px;"></i>
                            <span class="text-truncate">{{ Str::limit($gv->Email, 22) }}</span>
                        </div>
                        @endif
                        
                        @if($gv->SDT)
                        <div class="d-flex align-items-center mb-2 text-muted small">
                            <i class="fas fa-phone me-2 text-primary opacity-50" style="width: 16px;"></i>
                            <span>{{ $gv->SDT }}</span>
                        </div>
                        @endif

                        @if($gv->HocVi)
                        <div class="d-flex align-items-center mb-0 text-muted small">
                            <i class="fas fa-graduation-cap me-2 text-primary opacity-50" style="width: 16px;"></i>
                            <span>{{ $gv->HocVi }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $giangviens->links() }}
    </div>

    @if($giangviens->count() == 0)
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-inbox fa-3x text-muted opacity-50"></i>
            </div>
            <p class="text-muted">Chưa có giảng viên nào. Hãy thêm giảng viên mới!</p>
        </div>
    @endif
</div>

{{-- Modal Thêm/Sửa --}}
<div class="modal fade" id="gvModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="gvModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Thêm Giảng viên
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="gvForm">
                    @csrf
                    <input type="hidden" name="_method" id="gvMethod" value="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control shadow-sm" name="TenGV" id="tenGV" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Giới tính <span class="text-danger">*</span></label>
                            <select class="form-select shadow-sm" name="GioiTinh" id="gioiTinh" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">CCCD <span class="text-danger">*</span></label>
                            <input type="text" class="form-control shadow-sm" name="MaCCCD" id="maCCCD" maxlength="12" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control shadow-sm" name="NgaySinh" id="ngaySinh" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control shadow-sm" name="Email" id="email" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control shadow-sm" name="SDT" id="sdt" maxlength="10" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Học vị</label>
                            <input type="text" class="form-control shadow-sm" name="HocVi" id="hocVi" placeholder="VD: Thạc sĩ, Tiến sĩ">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Học hàm</label>
                            <input type="text" class="form-control shadow-sm" name="HocHam" id="hocHam" placeholder="VD: Phó Giáo sư">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Ngành</label>
                        <select class="form-select shadow-sm" name="MaNganh" id="maNganh">
                            <option value="">-- Chọn Ngành --</option>
                            @foreach($nganhs as $nganh)
                                <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">
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
            <div class="modal-header bg-info text-white border-0">
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
                        <small class="text-muted">Định dạng: .xlsx, .xls, .csv</small>
                    </div>
                    <div class="alert alert-light border shadow-sm">
                        <strong><i class="fas fa-info-circle me-2 text-info"></i>Lưu ý:</strong> File Excel cần có các cột: 
                        <code>TenGV</code>, <code>Email</code>, <code>SDT</code>, <code>CCCD</code>, <code>Ngành</code>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info btn-lg text-white shadow-sm fw-bold">
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
                <p class="text-muted mb-2" id="deleteMessage">Xóa giảng viên này?</p>
                <div class="alert alert-warning border-0 mt-3 mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <small><strong>Lưu ý:</strong> Thao tác này không thể hoàn tác!</small>
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-light px-4 py-2 fw-semibold rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-danger px-4 py-2 fw-semibold rounded-pill" id="confirmDeleteBtn">
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
/* Custom Colors */
.bg-light-orange { background-color: #fff3e0; }
.text-orange { color: #ef6c00 !important; }

/* Card Styling */
.hover-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.05) !important;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    border-color: rgba(0,0,0,0.0) !important;
}

.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

/* Modal Icons */
.warning-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: #fee2e2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

.warning-icon i {
    font-size: 36px;
    color: #dc2626;
}

.success-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    background: #d1e7dd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.success-icon i {
    font-size: 32px;
    color: #198754;
}

.error-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    background: #f8d7da;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.error-icon i {
    font-size: 32px;
    color: #dc3545;
}

@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
}
</style>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){
    const modal = new bootstrap.Modal('#gvModal');
    const importModal = new bootstrap.Modal('#importModal');
    const deleteModal = new bootstrap.Modal('#deleteModal');
    const notificationModal = new bootstrap.Modal('#notificationModal');
    
    function showNotification(type, title, message) {
        document.getElementById('notificationTitle').textContent = title;
        document.getElementById('notificationMessage').innerHTML = message;
        
        if (type === 'success') {
            document.getElementById('notificationIcon').innerHTML = '<div class="success-icon"><i class="fas fa-check"></i></div>';
            document.getElementById('notificationButtons').innerHTML = `
                <button type="button" class="btn btn-success btn-lg w-100 rounded-pill" onclick="window.location.reload()">
                    Quay lại danh sách
                </button>
            `;
            setTimeout(() => { notificationModal.hide(); window.location.reload(); }, 2000);
        } else {
            document.getElementById('notificationIcon').innerHTML = '<div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>';
            document.getElementById('notificationButtons').innerHTML = `
                <button type="button" class="btn btn-danger btn-lg w-100 rounded-pill" data-bs-dismiss="modal">Đóng</button>
            `;
        }
        notificationModal.show();
    }
    
    $('#btnAddGV').click(() => {
        $('#gvModalLabel').html('<i class="fas fa-plus-circle me-2"></i>Thêm Giảng viên');
        $('#gvForm')[0].reset();
        $('#gvMethod').val('POST');
        $('#gvForm').attr('action', '{{ route("canbo.giangvien.store") }}');
        modal.show();
    });

    $('#btnImportGV').click(() => {
        $('#importForm')[0].reset();
        importModal.show();
    });

    $('#importForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("canbo.giangvien.import") }}',
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
        
        $('#gvModalLabel').html('<i class="fas fa-edit me-2"></i>Cập nhật Giảng viên');
        $('#gvMethod').val('PUT');
        $('#gvForm').attr('action', '{{ url("canbo/giangvien") }}/' + id);
        
        $.get('{{ url("canbo/giangvien") }}/' + id + '/edit', function(res) {
            $('#tenGV').val(res.giangvien.TenGV);
            $('#gioiTinh').val(res.giangvien.GioiTinh);
            $('#maCCCD').val(res.giangvien.MaCCCD);
            $('#ngaySinh').val(res.giangvien.NgaySinh);
            $('#email').val(res.giangvien.Email);
            $('#sdt').val(res.giangvien.SDT);
            $('#hocVi').val(res.giangvien.HocVi);
            $('#hocHam').val(res.giangvien.HocHam);
            $('#maNganh').val(res.giangvien.MaNganh);
            modal.show();
        });
    });

    let deleteId = null;
    let deleteCard = null;
    
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        deleteCard = $(this).closest('.gv-card');
        deleteId = $(this).data('id');
        const tenGV = deleteCard.find('.gv-name').text();
        
        document.getElementById('deleteMessage').textContent = `Xóa giảng viên "${tenGV}"?`;
        deleteModal.show();
    });
    
    $('#confirmDeleteBtn').click(function() {
        if (!deleteId || !deleteCard) return;
        
        $.ajax({
            url: '{{ url("canbo/giangvien") }}/' + deleteId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                deleteModal.hide();
                deleteCard.fadeOut(300, function() {
                    $(this).remove();
                    updateResultCount();
                });
                showNotification('success', 'Thành công!', 'Xóa giảng viên thành công!');
                deleteId = null;
                deleteCard = null;
            },
            error: function(xhr) {
                deleteModal.hide();
                showNotification('error', 'Lỗi!', xhr.responseJSON?.message || 'Không thể xóa');
            }
        });
    });

    $('#gvForm').submit(function(e) {
        e.preventDefault();
        const method = $('#gvMethod').val();
        const url = $(this).attr('action');
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(res) {
                modal.hide();
                const successMessage = method === 'POST' ? res.message || 'Thêm giảng viên thành công!' : 'Cập nhật giảng viên thành công!';
                showNotification('success', 'Thành công!', successMessage);
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    const errorMessages = Object.values(errors).map(err => err[0]).join('<br>');
                    showNotification('error', 'Lỗi xác thực!', errorMessages);
                } else {
                    showNotification('error', 'Lỗi!', xhr.responseJSON?.message || 'Có lỗi xảy ra!');
                }
            }
        });
    });

    $('#gvSearch').on('keyup', function() {
        const term = $(this).val().toLowerCase();
        $('.gv-card').each(function() {
            const gvName = $(this).find('.gv-name').text().toLowerCase();
            const gvId = $(this).data('id').toString().toLowerCase();
            $(this).toggle(gvName.indexOf(term) !== -1 || gvId.indexOf(term) !== -1);
        });
        updateResultCount();
    });
    
    function updateResultCount() {
        const visibleCards = $('.gv-card:visible').length;
        $('#resultCount').text(visibleCards);
    }
});
</script>
@endpush
