@extends('layouts.giangvien')

@section('content')
<style>
    /* Color Variables */
    :root {
        --primary-color: #0066cc;
        --primary-light: #e3f2fd;
        --primary-dark: #0052a3;
        --secondary-color: #00aaff;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --light-bg: #f8f9fa;
        --border-color: #e5e7eb;
        --text-dark: #1f2937;
        --text-light: #6b7280;
    }

    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header Section */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 102, 204, 0.15);
        margin-bottom: 2rem;
        color: white;
    }

    .page-header h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header i {
        font-size: 2rem;
    }

    /* Button Groups */
    .btn-group-header {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn {
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 102, 204, 0.3);
    }

    .btn-success {
        background-color: var(--success-color);
        color: white;
    }

    .btn-success:hover {
        background-color: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-warning {
        background-color: var(--warning-color);
        color: white;
    }

    .btn-warning:hover {
        background-color: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    .btn-info {
        background-color: var(--secondary-color);
        color: white;
    }

    .btn-info:hover {
        background-color: #0096d9;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 170, 255, 0.3);
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Alert */
    .alert {
        border: none;
        border-radius: 0.75rem;
        border-left: 4px solid;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-left-color: var(--success-color);
    }

    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
        border: none;
    }

    .table thead {
        background: linear-gradient(135deg, #f0f7ff 0%, #e3f2fd 100%);
        border-bottom: 2px solid var(--primary-color);
    }

    .table thead th {
        color: var(--primary-color);
        font-weight: 700;
        padding: 1.25rem;
        border: none;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: var(--primary-light);
        box-shadow: inset 0 0 10px rgba(0, 102, 204, 0.1);
    }

    .table tbody td {
        padding: 1.25rem;
        color: var(--text-dark);
        vertical-align: middle;
        border: none;
    }

    /* Badge Styles */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }

    .bg-success {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }

    .bg-warning {
        background-color: #fef08a !important;
        color: #78350f !important;
    }

    .bg-secondary {
        background-color: #e5e7eb !important;
        color: #374151 !important;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border: none;
        padding: 1.5rem;
    }

    .modal-header.bg-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #fbbf24 100%) !important;
    }

    .modal-header.bg-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%) !important;
    }

    .modal-header.bg-info {
        background: linear-gradient(135deg, var(--secondary-color) 0%, #38bdf8 100%) !important;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-body {
        padding: 2rem !important;
    }

    .modal-footer {
        background-color: var(--light-bg) !important;
        border: none;
        padding: 1.25rem;
    }

    .form-label {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 1.5px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        outline: none;
    }

    /* Action Buttons */
    .action-cell {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .deadline-form {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .deadline-form input {
        flex: 1;
    }

    /* Utility Classes */
    .text-danger {
        color: var(--danger-color);
    }

    .text-primary {
        color: var(--primary-color);
    }

    .fw-bold {
        font-weight: 700;
    }

    .fw-medium {
        font-weight: 500;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .p-3 {
        padding: 1rem;
    }

    .p-4 {
        padding: 1.5rem;
    }

    .bg-light {
        background-color: var(--light-bg);
    }

    .border {
        border: 1px solid var(--border-color);
    }

    .rounded {
        border-radius: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h3 {
            font-size: 1.5rem;
        }

        .btn-group-header {
            flex-direction: column;
        }

        .btn-group-header .btn {
            width: 100%;
            justify-content: center;
        }

        .table thead th {
            padding: 0.75rem 0.5rem;
            font-size: 0.8rem;
        }

        .table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.9rem;
        }

        .action-buttons {
            flex-wrap: wrap;
        }

        .deadline-form {
            flex-direction: column;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table tbody tr {
        animation: fadeInUp 0.5s ease-out;
    }
</style>

<div class="container-fluid p-4">
    <!-- Header Section -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3>
            <i class="bi bi-book-half"></i> Danh sách đề tài của tôi
        </h3>
        <div class="btn-group-header">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-excel"></i> Import Excel
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTopicModal">
                <i class="bi bi-plus-lg"></i> Thêm đề tài mới
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="min-width: 200px;"><i class="bi bi-file-text me-2"></i>Tên đề tài</th>
                        <th style="min-width: 150px;"><i class="bi bi-file-earmark-text me-2"></i>Mô tả</th>
                        <th style="min-width: 120px;"><i class="bi bi-building me-2"></i>Khoa</th>
                        <th style="min-width: 120px;"><i class="bi bi-mortarboard me-2"></i>Ngành</th>
                        <th style="min-width: 120px;"><i class="bi bi-folder me-2"></i>Loại đề tài</th>
                        <th style="min-width: 100px;"><i class="bi bi-calendar me-2"></i>Năm học</th>
                        <th style="min-width: 120px;"><i class="bi bi-check-circle me-2"></i>Trạng thái</th>
                        <th style="width: 320px; min-width: 320px;"><i class="bi bi-gear me-2"></i>Hành động</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($detais as $detai)
                    <tr>
                        <td>
                            <span class="fw-medium">{{ $detai->TenDeTai }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($detai->MoTa, 50) }}</small>
                        </td>
                        <td>{{ $detai->khoa->TenKhoa ?? 'Chưa có' }}</td>
                        <td>{{ $detai->nganh->TenNganh ?? 'Chưa có' }}</td>
                        <td>{{ $detai->LoaiDeTai }}</td>
                        <td>{{ $detai->NamHoc ?? 'Chưa có' }}</td>
                        <td>
                            <span class="badge 
                                @if($detai->TrangThai == 'Đã duyệt') bg-success 
                                @elseif($detai->TrangThai == 'Chưa duyệt') bg-warning 
                                @else bg-secondary @endif">
                                <i class="bi bi-check-lg me-1"></i>{{ $detai->TrangThai }}
                            </span>
                        </td>
                        <td>
                            <div class="action-cell">
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-info btn-sm btn-view" 
                                            data-bs-toggle="modal" data-bs-target="#viewTopicModal"
                                            data-ten="{{ $detai->TenDeTai }}"
                                            data-khoa="{{ $detai->khoa->TenKhoa ?? 'Chưa có' }}"
                                            data-nganh="{{ $detai->nganh->TenNganh ?? 'Chưa có' }}"
                                            data-loai="{{ $detai->LoaiDeTai }}"
                                            data-namhoc="{{ $detai->NamHoc }}"
                                            data-trangthai="{{ $detai->TrangThai }}"
                                            data-mota="{{ $detai->MoTa }}"
                                            title="Xem chi tiết">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm btn-edit" 
                                            data-bs-toggle="modal" data-bs-target="#editTopicModal"
                                            data-id="{{ $detai->MaDeTai }}"
                                            data-ten="{{ $detai->TenDeTai }}"
                                            data-makhoa="{{ $detai->MaKhoa }}"
                                            data-manganh="{{ $detai->MaNganh }}"
                                            data-loai="{{ $detai->LoaiDeTai }}"
                                            data-soluong="{{ $detai->SoLuongThanhVien ?? 1 }}"
                                            data-manamhoc="{{ $detai->MaNamHoc }}"
                                            data-mota="{{ $detai->MoTa }}"
                                            data-url="{{ route('giangvien.detai.update', $detai->MaDeTai) }}"
                                            title="Chỉnh sửa">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="{{ $detai->MaDeTai }}" 
                                            data-url="{{ route('giangvien.detai.destroy', $detai->MaDeTai) }}"
                                            title="Xóa">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>

                                <form action="{{ route('giangvien.detai.deadline', $detai->MaDeTai) }}" method="POST" class="deadline-form">
                                    @csrf
                                    <input type="datetime-local" name="DeadlineBaoCao" class="form-control form-control-sm" 
                                           value="{{ $detai->DeadlineBaoCao ? date('Y-m-d\TH:i', strtotime($detai->DeadlineBaoCao)) : '' }}" 
                                           placeholder="Deadline">
                                    <button class="btn btn-primary btn-sm" title="Lưu Deadline">
                                        <i class="bi bi-download"></i>
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

<!-- Modal Thêm Đề Tài -->
<div class="modal fade" id="addTopicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill"></i>Thêm Đề Tài Mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTopicForm" action="{{ route('giangvien.detai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên đề tài <span class="text-danger">*</span></label>
                        <input type="text" name="TenDeTai" class="form-control" placeholder="Nhập tên đề tài..." required>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Khoa <span class="text-danger">*</span></label>
                            <select name="MaKhoa" id="addMaKhoa" class="form-select" required>
                                <option value="">-- Chọn khoa --</option>
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngành <span class="text-danger">*</span></label>
                            <select name="MaNganh" id="addMaNganh" class="form-select" required>
                                <option value="">-- Chọn ngành --</option>
                                @foreach($nganhs as $nganh)
                                    <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại đề tài <span class="text-danger">*</span></label>
                            <select name="LoaiDeTai" class="form-select" required>
                                <option value="">-- Chọn loại đề tài --</option>
                                <option value="Cá nhân">Cá nhân</option>
                                <option value="Đồ án Môn học">Đồ án Môn học</option>
                                <option value="Nghiên cứu Khoa học">Nghiên cứu Khoa học</option>
                                <option value="Nghiên cứu Ứng dụng">Nghiên cứu Ứng dụng</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số lượng thành viên <span class="text-danger">*</span></label>
                            <select name="SoLuongThanhVien" class="form-select" required>
                                <option value="">-- Chọn số lượng --</option>
                                <option value="1">1 người (Cá nhân)</option>
                                <option value="2">2 người (Nhóm)</option>
                                <option value="3">3 người (Nhóm)</option>
                                <option value="4">4 người (Nhóm)</option>
                                <option value="5">5 người (Nhóm)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Năm học <span class="text-danger">*</span></label>
                            <select name="MaNamHoc" class="form-select" required>
                                <option value="">-- Chọn năm học --</option>
                                @foreach($namHocs as $nh)
                                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="MoTa" class="form-control" rows="3" placeholder="Mô tả chi tiết về đề tài..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu Đề Tài
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xem Chi Tiết -->
<div class="modal fade" id="viewTopicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #00aaff 0%, #38bdf8 100%);">
                <h5 class="modal-title">
                    <i class="bi bi-eye-fill"></i>Chi Tiết Đề Tài
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="fw-bold text-primary">Tên đề tài:</label>
                    <p id="viewTenDeTai" class="fs-5 fw-medium"></p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-primary">Khoa:</label>
                        <p id="viewKhoa"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-primary">Ngành:</label>
                        <p id="viewNganh"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-primary">Loại đề tài:</label>
                        <p id="viewLoaiDeTai"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-primary">Năm học:</label>
                        <p id="viewNamHoc"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-primary">Trạng thái:</label>
                    <p><span id="viewTrangThai" class="badge"></span></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-primary">Mô tả:</label>
                    <div id="viewMoTa" class="p-3 bg-light rounded border"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Đề Tài -->
<div class="modal fade" id="editTopicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i>Cập Nhật Đề Tài
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTopicForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên đề tài <span class="text-danger">*</span></label>
                        <input type="text" name="TenDeTai" id="editTenDeTai" class="form-control" required>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Khoa <span class="text-danger">*</span></label>
                            <select name="MaKhoa" id="editMaKhoa" class="form-select" required>
                                <option value="">-- Chọn khoa --</option>
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngành <span class="text-danger">*</span></label>
                            <select name="MaNganh" id="editMaNganh" class="form-select" required>
                                <option value="">-- Chọn ngành --</option>
                                @foreach($nganhs as $nganh)
                                    <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại đề tài <span class="text-danger">*</span></label>
                            <select name="LoaiDeTai" id="editLoaiDeTai" class="form-select" required>
                                <option value="">-- Chọn loại đề tài --</option>
                                <option value="Cá nhân">Cá nhân</option>
                                <option value="Đồ án Môn học">Đồ án Môn học</option>
                                <option value="Nghiên cứu Khoa học">Nghiên cứu Khoa học</option>
                                <option value="Nghiên cứu Ứng dụng">Nghiên cứu Ứng dụng</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số lượng thành viên <span class="text-danger">*</span></label>
                            <select name="SoLuongThanhVien" id="editSoLuongThanhVien" class="form-select" required>
                                <option value="">-- Chọn số lượng --</option>
                                <option value="1">1 người (Cá nhân)</option>
                                <option value="2">2 người (Nhóm)</option>
                                <option value="3">3 người (Nhóm)</option>
                                <option value="4">4 người (Nhóm)</option>
                                <option value="5">5 người (Nhóm)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Năm học <span class="text-danger">*</span></label>
                            <select name="MaNamHoc" id="editMaNamHoc" class="form-select" required>
                                <option value="">-- Chọn năm học --</option>
                                @foreach($namHocs as $nh)
                                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="MoTa" id="editMoTa" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-excel-fill"></i>Import Đề Tài từ Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importForm" action="{{ route('giangvien.detai.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info" style="background-color: #cffafe; color: #0c4a6e; border-left-color: var(--secondary-color);">
                        <i class="bi bi-info-circle me-2"></i>
                        File Excel cần có các cột: <strong>TenDeTai, Khoa, Ngành, LoaiDeTai, SoLuongThanhVien, NamHoc, MoTa</strong>.
                        <br><small class="mt-2 d-block">* SoLuongThanhVien: Nhập số từ 1-5 (1 = cá nhân, >1 = nhóm)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chọn file Excel</label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-2"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Populate View Modal
        $('.btn-view').on('click', function() {
            let btn = $(this);
            $('#viewTenDeTai').text(btn.data('ten'));
            $('#viewKhoa').text(btn.data('khoa'));
            $('#viewNganh').text(btn.data('nganh'));
            $('#viewLoaiDeTai').text(btn.data('loai'));
            $('#viewNamHoc').text(btn.data('namhoc'));
            $('#viewMoTa').text(btn.data('mota') || 'Không có mô tả');
            
            let trangThai = btn.data('trangthai');
            let badgeClass = 'bg-secondary';
            if (trangThai === 'Đã duyệt') badgeClass = 'bg-success';
            else if (trangThai === 'Chưa duyệt') badgeClass = 'bg-warning';
            
            $('#viewTrangThai').text(trangThai).removeClass().addClass('badge ' + badgeClass);
        });

        // Populate Edit Modal
        $('.btn-edit').on('click', function() {
            let btn = $(this);
            $('#editTopicForm').attr('action', btn.data('url'));
            $('#editTenDeTai').val(btn.data('ten'));
            $('#editMaKhoa').val(btn.data('makhoa'));
            $('#editMaNganh').val(btn.data('manganh'));
            $('#editLoaiDeTai').val(btn.data('loai'));
            $('#editSoLuongThanhVien').val(btn.data('soluong'));
            $('#editMaNamHoc').val(btn.data('manamhoc'));
            $('#editMoTa').val(btn.data('mota'));
        });

        // AJAX Add Topic
        $('#addTopicForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#addTopicModal').modal('hide');
                    form[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                        position: 'center'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    let errorMessage = 'Có lỗi xảy ra!';
                    if (errors) {
                        errorMessage = Object.values(errors).join('\n');
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: errorMessage,
                        position: 'center'
                    });
                }
            });
        });

        // AJAX Edit Topic
        $('#editTopicForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#editTopicModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Cập nhật thành công!',
                        text: 'Thông tin đề tài đã được cập nhật.',
                        showConfirmButton: false,
                        timer: 1500,
                        position: 'center'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    let errorMessage = 'Có lỗi xảy ra!';
                    if (errors) {
                        errorMessage = Object.values(errors).join('\n');
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi Cập Nhật!',
                        text: errorMessage,
                        position: 'center'
                    });
                }
            });
        });

        // AJAX Import
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#importModal').modal('hide');
                    form[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Import thành công!',
                        text: response.message,
                        position: 'center'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi Import!',
                        text: xhr.responseJSON?.message || 'Có lỗi xảy ra khi import file.',
                        position: 'center'
                    });
                }
            });
        });

        // AJAX Delete
        $('.btn-delete').on('click', function() {
            let url = $(this).data('url');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đã xóa!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500,
                                position: 'center'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: xhr.responseJSON?.message || 'Không thể xóa đề tài này.',
                                position: 'center'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection