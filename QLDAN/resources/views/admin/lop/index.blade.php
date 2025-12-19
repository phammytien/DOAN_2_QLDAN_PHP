@extends('layouts.admin')

@section('title', 'Quản lý Lớp')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark">Quản lý Lớp</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.lop.export') }}" class="btn btn-success text-white">
                <i class="fas fa-file-excel me-2"></i>Xuất Excel
            </a>
            <button class="btn btn-warning text-white" id="btnExportByClass">
                <i class="fas fa-file-download me-2"></i>Xuất theo Lớp
            </button>
            <button class="btn btn-info text-white" id="btnImportLop">
                <i class="fas fa-file-upload me-2"></i>Nhập Excel
            </button>
            <button class="btn btn-primary" id="btnAddLop">
                <i class="fas fa-plus me-2"></i>Thêm Lớp
            </button>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="d-flex gap-3">
                <div class="flex-grow-1" style="max-width: 300px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="lopSearch" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm Lớp...">
                    </div>
                </div>
                
                <select id="filterKhoa" class="form-select" style="width: 250px;">
                    <option value="">Tất cả Khoa</option>
                    @foreach($khoas as $khoa)
                        <option value="{{ $khoa->MaKhoa }}" {{ request('khoa') == $khoa->MaKhoa ? 'selected' : '' }}>
                            {{ $khoa->TenKhoa }}
                        </option>
                    @endforeach
                </select>
                
                <select id="filterNganh" class="form-select" style="width: 250px;">
                    <option value="">Tất cả Ngành</option>
                    @foreach($nganhs as $nganh)
                        <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Accordion for Lop with SinhVien --}}
    <div class="accordion" id="lopAccordion">
        @foreach($lops as $lop)
        <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden" 
             data-id="{{ $lop->MaLop }}" 
             data-khoa-id="{{ $lop->MaKhoa }}" 
             data-nganh-id="{{ $lop->MaNganh }}">
            
            <h2 class="accordion-header" id="heading{{ $lop->MaLop }}">
                <div class="d-flex align-items-center p-3 bg-white border-start border-4 border-primary position-relative">
                    {{-- Accordion Trigger (Invisible but clickable area) --}}
                    <button class="accordion-button collapsed position-absolute w-100 h-100 top-0 start-0 opacity-0" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapse{{ $lop->MaLop }}" aria-expanded="false" 
                            aria-controls="collapse{{ $lop->MaLop }}" style="z-index: 1;">
                    </button>

                    {{-- Visible Content --}}
                    <div class="d-flex align-items-center flex-grow-1" style="z-index: 0; pointer-events: none;">
                        <i class="fas fa-users text-primary me-3 fa-lg"></i>
                        <span class="fw-bold text-dark fs-6">{{ $lop->TenLop }}</span>
                        
                        <span class="badge bg-light text-secondary border ms-3 fw-normal">
                            {{ $lop->sinhviens_count }} sinh viên
                        </span>
                        
                        @if($lop->khoa)
                            <span class="badge ms-2 fw-normal" style="background-color: #fff3cd; color: #856404;">
                                {{ $lop->khoa->TenKhoa }}
                            </span>
                        @endif
                        
                        @if($lop->nganh)
                            <span class="badge ms-2 fw-normal" style="background-color: #d1ecf1; color: #0c5460;">
                                {{ $lop->nganh->TenNganh }}
                            </span>
                        @endif
                    </div>

                    {{-- Actions (Above the trigger) --}}
                    <div class="ms-auto d-flex gap-2 position-relative" style="z-index: 2;">
                        <button class="btn btn-warning text-white btn-sm btn-edit rounded" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" data-id="{{ $lop->MaLop }}" title="Sửa">
                            <i class="fas fa-pen fa-xs"></i>
                        </button>
                        <button class="btn btn-danger text-white btn-sm btn-delete rounded" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" data-id="{{ $lop->MaLop }}" title="Xóa">
                            <i class="fas fa-trash fa-xs"></i>
                        </button>
                    </div>
                </div>
            </h2>

            <div id="collapse{{ $lop->MaLop }}" class="accordion-collapse collapse" 
                 aria-labelledby="heading{{ $lop->MaLop }}" data-bs-parent="#lopAccordion">
                <div class="accordion-body bg-light border-top">
                    @if($lop->sinhviens->count() > 0)
                        <div class="table-responsive bg-white rounded shadow-sm p-3">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small text-uppercase fw-bold">Mã SV</th>
                                        <th class="text-secondary small text-uppercase fw-bold">Họ tên</th>
                                        <th class="text-secondary small text-uppercase fw-bold">Giới tính</th>
                                        <th class="text-secondary small text-uppercase fw-bold">Ngày sinh</th>
                                        <th class="text-secondary small text-uppercase fw-bold">Email</th>
                                        <th class="text-secondary small text-uppercase fw-bold">SĐT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lop->sinhviens as $sv)
                                    <tr>
                                        <td><span class="text-primary fw-bold">{{ $sv->MaSV }}</span></td>
                                        <td><span class="fw-medium">{{ $sv->TenSV }}</span></td>
                                        <td>
                                            @if($sv->GioiTinh == 'Nam')
                                                <span class="badge bg-info bg-opacity-10 text-info">Nam</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger">Nữ</span>
                                            @endif
                                        </td>
                                        <td>{{ $sv->NgaySinh ? date('d/m/Y', strtotime($sv->NgaySinh)) : '-' }}</td>
                                        <td>{{ $sv->Email ?? '-' }}</td>
                                        <td>{{ $sv->SDT ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-user-slash fa-2x mb-2"></i>
                            <p class="mb-0">Chưa có sinh viên nào trong lớp này.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $lops->links() }}
    </div>

    @if($lops->count() == 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Chưa có lớp nào. Hãy thêm lớp mới!
        </div>
    @endif
</div>

{{-- Modal for Add/Edit Lop --}}
<div class="modal fade" id="lopModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="lopModalLabel">
                    <i class="fas fa-users me-2"></i>Thêm Lớp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold mb-0">
                                <i class="fas fa-building text-primary me-2"></i>Khoa
                            </label>
                            <a href="#" class="small text-decoration-none toggle-input" data-target="khoa">
                                <i class="fas fa-plus-circle me-1"></i>Nhập mới
                            </a>
                        </div>
                        
                        {{-- Select Mode --}}
                        <div class="input-group" id="group-khoa-select">
                            <select class="form-select shadow-sm" name="MaKhoa" id="maKhoa">
                                <option value="">-- Chọn Khoa --</option>
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Mode (Hidden by default) --}}
                        <div class="input-group d-none" id="group-khoa-input">
                            <input type="text" class="form-control shadow-sm" name="TenKhoaNew" id="tenKhoaNew" placeholder="Nhập tên Khoa mới...">
                            <button type="button" class="btn btn-outline-secondary toggle-back" data-target="khoa">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold mb-0">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>Ngành
                            </label>
                            <a href="#" class="small text-decoration-none toggle-input" data-target="nganh">
                                <i class="fas fa-plus-circle me-1"></i>Nhập mới
                            </a>
                        </div>
                        
                        {{-- Select Mode --}}
                        <div class="input-group" id="group-nganh-select">
                            <select class="form-select shadow-sm" name="MaNganh" id="maNganh">
                                <option value="">-- Chọn Ngành --</option>
                                @foreach($nganhs as $nganh)
                                    <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Mode (Hidden by default) --}}
                        <div class="input-group d-none" id="group-nganh-input">
                            <input type="text" class="form-control shadow-sm" name="TenNganhNew" id="tenNganhNew" placeholder="Nhập tên Ngành mới...">
                            <button type="button" class="btn btn-outline-secondary toggle-back" data-target="nganh">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <button type="submit" form="lopForm" class="btn btn-primary px-4 shadow-sm">
                    <i class="fas fa-save me-2"></i>Lưu
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-info text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-upload me-2"></i>Nhập dữ liệu từ Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <strong><i class="fas fa-info-circle me-2"></i>Lưu ý:</strong> File Excel cần có các cột tiêu đề: 
                        <code>Tên Lớp</code>, <code>Khoa</code>, <code>Ngành</code>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <button type="submit" form="importForm" class="btn btn-info text-white px-4 shadow-sm">
                    <i class="fas fa-upload me-2"></i>Tải lên
                </button>
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
                <p class="text-muted mb-2" id="deleteMessage" style="font-size: 1.1rem;">Xóa lớp này?</p>
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

{{-- Export by Class Modal --}}
<div class="modal fade" id="exportByClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-warning text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-download me-2"></i>Xuất Excel theo Lớp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-users text-warning me-2"></i>Chọn Lớp <span class="text-danger">*</span>
                    </label>
                    <select class="form-select shadow-sm" id="exportClassSelect">
                        <option value="">-- Chọn lớp cần xuất --</option>
                        @foreach($lops as $lop)
                            <option value="{{ $lop->MaLop }}">{{ $lop->TenLop }} ({{ $lop->sinhviens_count }} SV)</option>
                        @endforeach
                    </select>
                </div>
                <div class="alert alert-info border-0 shadow-sm">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Lưu ý:</strong> File Excel sẽ chứa danh sách sinh viên của lớp được chọn.
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <button type="button" class="btn btn-warning text-white px-4 shadow-sm" id="confirmExportByClass">
                    <i class="fas fa-download me-2"></i>Xuất Excel
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #63b7eaff 0%, #45bdd2ff 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #0f8cf9ff;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .accordion-button:not(.collapsed) {
        background: transparent !important;
        color: white !important;
        box-shadow: none !important;
    }
    
    .accordion-button::after {
        filter: brightness(0) invert(1);
    }
    
    .accordion-button:focus {
        box-shadow: none !important;
    }
    
    .accordion-item {
        transition: all 0.3s ease;
    }
    
    .accordion-item:hover {
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.2);
        transform: translateY(-2px);
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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){
    const modal = new bootstrap.Modal('#lopModal');
    const importModal = new bootstrap.Modal('#importModal');
    const deleteModal = new bootstrap.Modal('#deleteModal');
    const notificationModal = new bootstrap.Modal('#notificationModal');
    const exportByClassModal = new bootstrap.Modal('#exportByClassModal');
    
    const khoaMap = {!! json_encode($khoas->pluck('TenKhoa', 'MaKhoa')) !!};
    const nganhMap = {!! json_encode($nganhs->pluck('TenNganh', 'MaNganh')) !!};
    
    // Open Export by Class Modal
    $('#btnExportByClass').click(() => {
        $('#exportClassSelect').val('');
        exportByClassModal.show();
    });
    
    // Confirm Export by Class
    $('#confirmExportByClass').click(function() {
        const maLop = $('#exportClassSelect').val();
        if (!maLop) {
            alert('Vui lòng chọn lớp cần xuất!');
            return;
        }
        
        // Redirect to export route
        window.location.href = '{{ url("admin/sinhvien/export-by-class") }}/' + maLop;
        exportByClassModal.hide();
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
    
    // Toggle Input Logic
    $('.toggle-input').click(function(e) {
        e.preventDefault();
        const target = $(this).data('target');
        
        // Hide Select, Show Input
        $(`#group-${target}-select`).addClass('d-none');
        $(`#group-${target}-input`).removeClass('d-none');
        
        // Disable Select to avoid validation errors if required (optional)
        $(`#ma${target.charAt(0).toUpperCase() + target.slice(1)}`).val('');
        
        // Focus Input
        $(`#ten${target.charAt(0).toUpperCase() + target.slice(1)}New`).focus();
        
        // Hide Toggle Link
        $(this).addClass('d-none');
    });

    $('.toggle-back').click(function(e) {
        e.preventDefault();
        const target = $(this).data('target');
        
        // Show Select, Hide Input
        $(`#group-${target}-select`).removeClass('d-none');
        $(`#group-${target}-input`).addClass('d-none');
        
        // Clear Input
        $(`#ten${target.charAt(0).toUpperCase() + target.slice(1)}New`).val('');
        
        // Show Toggle Link
        $(`.toggle-input[data-target="${target}"]`).removeClass('d-none');
    });

    // Add new Lop
    $('#btnAddLop').click(() => {
        $('#lopModalLabel').text('Thêm Lớp');
        $('#lopForm')[0].reset();
        
        // Reset toggles
        $('.toggle-back').click();
        
        $('#lopMethod').val('POST');
        $('#lopForm').attr('action', '{{ route("admin.lop.store") }}');
        
        modal.show();
    });

    // Open Import Modal
    $('#btnImportLop').click(() => {
        $('#importForm')[0].reset();
        importModal.show();
    });

    // Handle Import Form
    $('#importForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.lop.import") }}',
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

    // Edit Lop
    $(document).on('click', '.btn-edit', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        
        $('#lopModalLabel').text('Cập nhật Lớp');
        $('#lopMethod').val('PUT');
        $('#lopForm').attr('action', '{{ url("admin/lop") }}/' + id);
        
        $.get('{{ url("admin/lop") }}/' + id + '/edit', function(res) {
            $('#tenLop').val(res.lop.TenLop);
            $('#maKhoa').val(res.lop.MaKhoa);
            $('#maNganh').val(res.lop.MaNganh);
            modal.show();
        });
    });

    // Delete Lop - Show confirmation modal
    let deleteId = null;
    let deleteAccordionItem = null;
    
    $(document).on('click', '.btn-delete', function(e) {
        e.stopPropagation();
        deleteAccordionItem = $(this).closest('.accordion-item');
        deleteId = $(this).data('id');
        const tenLop = deleteAccordionItem.find('.fw-bold').first().text();
        
        document.getElementById('deleteMessage').textContent = `Xóa lớp "${tenLop}"?`;
        deleteModal.show();
    });
    
    // Confirm delete
    $('#confirmDeleteBtn').click(function() {
        if (!deleteId || !deleteAccordionItem) return;
        
        $.ajax({
            url: '{{ url("admin/lop") }}/' + deleteId,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                deleteModal.hide();
                deleteAccordionItem.fadeOut(300, function() {
                    $(this).remove();
                });
                showNotification('success', 'Thành công!', 'Xóa Lớp thành công!');
                deleteId = null;
                deleteAccordionItem = null;
            },
            error: function(xhr) {
                deleteModal.hide();
                showNotification('error', 'Lỗi!', xhr.responseJSON?.message || 'Không thể xóa');
            }
        });
    });

    // Submit form
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

    // Combined filter function
    function filterLops() {
        const searchTerm = $('#lopSearch').val().toLowerCase();
        const selectedKhoa = $('#filterKhoa').val();
        const selectedNganh = $('#filterNganh').val();
        
        $('.lop-item').each(function() {
            const lopName = $(this).find('.lop-name').text().toLowerCase();
            const khoaId = $(this).data('khoa-id');
            const nganhId = $(this).data('nganh-id');
            
            const matchSearch = lopName.indexOf(searchTerm) !== -1;
            const matchKhoa = !selectedKhoa || khoaId == selectedKhoa;
            const matchNganh = !selectedNganh || nganhId == selectedNganh;
            
            $(this).toggle(matchSearch && matchKhoa && matchNganh);
        });
    }

    // Search and filter events
    $('#lopSearch').on('keyup', filterLops);
    $('#filterNganh').on('change', filterLops);
    
    // Server-side filter for Khoa
    $('#filterKhoa').on('change', function() {
        const khoaId = $(this).val();
        const url = new URL(window.location.href);
        if (khoaId) {
            url.searchParams.set('khoa', khoaId);
        } else {
            url.searchParams.delete('khoa');
        }
        window.location.href = url.toString();
    });
});
</script>
@endpush
