@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-blue: #3b82f6;
        --light-blue: #eff6ff;
        --dark-blue: #1e40af;
        --hover-blue: #60a5fa;
        --success-green: #10b981;
        --warning-orange: #f59e0b;
        --danger-red: #ef4444;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    .modern-container {
        background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 50%, #fef3c7 100%);
        min-height: 100vh;
        padding: 2.5rem;
    }

    .page-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, var(--primary-blue) 0%, var(--hover-blue) 100%);
    }

    .page-header h3 {
        color: var(--gray-700);
        font-weight: 800;
        font-size: 2rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--gray-200);
    }

    .modern-select {
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 220px;
        font-weight: 500;
        background: var(--gray-50);
    }

    .modern-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        outline: none;
        background: white;
    }

    .btn-modern {
        border-radius: 10px;
        padding: 0.625rem 1.75rem;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%);
        color: white;
    }

    .btn-primary-modern:hover {
        background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-success-modern {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success-modern:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-info-modern {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
    }

    .btn-info-modern:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        color: white;
    }

    .modern-table thead th {
        border: none;
        padding: 1.25rem 1rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8125rem;
        letter-spacing: 0.8px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid var(--gray-100);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f0f9ff 0%, #ffffff 100%);
        transform: translateX(4px);
        box-shadow: -4px 0 0 0 var(--primary-blue);
    }

    .modern-table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        font-size: 0.9375rem;
    }

    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-modern::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .badge-success-modern {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .badge-info-modern {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .badge-danger-modern {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .badge-secondary-modern {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #4b5563;
        border: 1px solid #d1d5db;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-warning.btn-action {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-color: #f59e0b;
    }

    .btn-warning.btn-action:hover {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .btn-danger.btn-action {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        border-color: #ef4444;
    }

    .btn-danger.btn-action:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .btn-success.btn-action {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        border-color: #10b981;
    }

    .btn-success.btn-action:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .checkbox-modern {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--primary-blue);
    }

    .modal-modern .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-modern .modal-header {
        padding: 2rem;
        border-bottom: 2px solid var(--gray-100);
    }

    .modal-modern .modal-body {
        padding: 2.5rem;
    }

    .form-label-modern {
        font-weight: 700;
        color: var(--gray-700);
        margin-bottom: 0.625rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control-modern {
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: var(--gray-50);
    }

    .form-control-modern:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        outline: none;
        background: white;
    }

    .spinner-modern {
        border: 4px solid var(--light-blue);
        border-top-color: var(--primary-blue);
        border-radius: 50%;
        width: 48px;
        height: 48px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .pagination-modern {
        gap: 0.5rem;
    }

    .pagination-modern .page-link {
        border-radius: 10px;
        border: 2px solid var(--gray-200);
        color: var(--primary-blue);
        padding: 0.625rem 1rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
    }

    .pagination-modern .page-link:hover {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
        transform: translateY(-2px);
    }

    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%);
        border-color: var(--primary-blue);
        box-shadow: var(--shadow-md);
    }

    .empty-state {
        padding: 4rem;
        text-align: center;
        color: var(--gray-600);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--gray-300);
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
</style>

<div class="modern-container">
    <div class="page-header">
        <h3>📚 Danh sách đề tài</h3>
    </div>

    {{-- Bộ lọc trạng thái --}}
    <div class="filter-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <form method="GET" class="d-flex align-items-center gap-2">
                <select name="trangthai" class="modern-select form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="Chưa duyệt" {{ $trangThai == 'Chưa duyệt' ? 'selected' : '' }}>Chưa duyệt</option>
                    <option value="Đang thực hiện" {{ $trangThai == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                    <option value="Hoàn thành" {{ $trangThai == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="Hủy" {{ $trangThai == 'Hủy' ? 'selected' : '' }}>Hủy</option>
                </select>
                <button class="btn btn-modern btn-primary-modern">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </form>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-modern btn-info-modern" id="btnDuyetNhieu" onclick="openApprovalModal()" style="display:none;">
                    <i class="fas fa-check-double"></i> Duyệt đã chọn (<span id="countSelected">0</span>)
                </button>
                
                {{-- Button Xóa Nhiều --}}
                <button type="button" class="btn btn-modern btn-danger-modern" id="btnXoaNhieu" onclick="confirmDeleteMultiple()" style="display:none;">
                    <i class="fas fa-trash"></i> Xóa đã chọn
                </button>

                <!-- <button type="button" class="btn btn-modern btn-success-modern" data-bs-toggle="modal" data-bs-target="#modalThemDeTai">
                    <i class="fas fa-plus"></i> Thêm đề tài
                </button> -->
            </div>
        </div>
    </div>

    {{-- Form Xóa Nhiều (Ẩn) --}}
    <form id="formXoaNhieu" action="{{ route('admin.detai.destroy_multiple') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="detai_ids" id="deleteMultipleInput">
    </form>

    {{-- Thông báo --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('success', 'Thành công!', '{{ session('success') }}');
            });
        </script>
    @endif
    
    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('warning', 'Lưu ý!', '{{ session('warning') }}');
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

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('error', 'Lỗi xác thực!', '{!! implode("<br>", $errors->all()) !!}');
            });
        </script>
    @endif

    {{-- Bảng danh sách --}}
    <div class="table-card">
        <table class="table modern-table align-middle mb-0">
            <thead>
                <tr>
                    <th width="40px">
                        <input type="checkbox" id="checkAll" class="checkbox-modern" onclick="toggleCheckAll(this)">
                    </th>
                    <th>#</th>
                    <th>Tên đề tài</th>
                    <th>Giảng viên</th>
                    <th>Khoa</th>
                    <th>Năm học</th>
                    <th>Trạng thái</th>
                    <th>Duyệt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detais as $item)
                    <tr>
                        <td>
                            {{-- Cho phép check tất cả để xóa (kể cả đã duyệt), backend sẽ tự lọc an toàn --}}
                            <input type="checkbox" class="detai-checkbox checkbox-modern" value="{{ $item->MaDeTai }}" 
                                   data-namhoc="{{ $item->MaNamHoc }}" onchange="updateSelectedCount()">
                        </td>
                        <td><strong>{{ $loop->iteration }}</strong></td>
                        <td><strong>{{ $item->TenDeTai }}</strong></td>
                        <td>{{ $item->giangVien->TenGV ?? 'Chưa gán' }}</td>
                        <td>{{ $item->giangVien->khoa->TenKhoa ?? '-' }}</td>
                        <td>{{ $item->namHoc->TenNamHoc ?? '-' }}</td>
                        <td>
                            @php
                                $colors = [
                                    'Hoàn thành' => 'success',
                                    'Đang thực hiện' => 'info',
                                    'Hủy' => 'danger',
                                    'Chưa duyệt' => 'secondary'
                                ];
                            @endphp
                            <span class="badge badge-modern badge-{{ $colors[$item->TrangThai] ?? 'secondary' }}-modern">
                                {{ $item->TrangThai ?? 'Chưa duyệt' }}
                            </span>
                        </td>
                        <td>
                            @if($item->TrangThai == 'Chưa duyệt')
                                <button class="btn btn-success btn-action" onclick="openSingleApprovalModal({{ $item->MaDeTai }})">
                                    <i class="fas fa-check"></i> Duyệt
                                </button>
                            @elseif($item->TrangThai == 'Đang thực hiện')
                                <form id="complete-form-{{ $item->MaDeTai }}" action="{{ route('admin.detai.complete', $item->MaDeTai) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-primary btn-action" onclick="confirmComplete('{{ $item->MaDeTai }}', '{{ $item->TenDeTai }}')">
                                        <i class="fas fa-check-circle"></i> Hoàn thành
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-warning btn-action" onclick="loadEditForm({{ $item->MaDeTai }})">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <form id="delete-form-{{ $item->MaDeTai }}" action="{{ route('admin.detai.destroy', $item->MaDeTai) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-action" onclick="confirmDelete('{{ $item->MaDeTai }}', '{{ $item->TenDeTai }}')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p class="mb-0">Không có đề tài nào</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center mt-4">
        <div class="pagination-modern">
            {{ $detais->links() }}
        </div>
    </div>
</div>

{{-- MODAL THÊM ĐỀ TÀI --}}
<div class="modal fade modal-modern" id="modalThemDeTai" tabindex="-1" aria-labelledby="modalThemDeTaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%); color: white;">
                <h5 class="modal-title" id="modalThemDeTaiLabel">
                    <i class="fas fa-plus-circle"></i> Thêm đề tài mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.detai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label form-label-modern">
                            <i class="fas fa-book text-primary me-2"></i>Tên đề tài <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="TenDeTai" class="form-control form-control-modern" required
                               minlength="10" maxlength="500" placeholder="Nhập tên đề tài...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern">
                            <i class="fas fa-align-left text-info me-2"></i>Mô tả
                        </label>
                        <textarea name="MoTa" class="form-control form-control-modern" rows="4"
                                  placeholder="Nhập mô tả chi tiết về đề tài..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label form-label-modern">
                                <i class="fas fa-graduation-cap text-success me-2"></i>Lĩnh vực (Ngành) <span class="text-danger">*</span>
                            </label>
                            <select name="LinhVuc" class="form-select form-control-modern" required>
                                <option value="">-- Chọn lĩnh vực --</option>
                                @foreach($nganhs as $nganh)
                                    <option value="{{ $nganh->TenNganh }}">{{ $nganh->TenNganh }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label form-label-modern">
                                <i class="fas fa-calendar-alt text-warning me-2"></i>Năm học <span class="text-danger">*</span>
                            </label>
                            <select name="MaNamHoc" class="form-select form-control-modern" required>
                                <option value="">-- Chọn năm học --</option>
                                @foreach($namHocs as $nh)
                                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label form-label-modern">
                                <i class="fas fa-users text-danger me-2"></i>Loại đề tài
                            </label>
                            <select name="LoaiDeTai" class="form-select form-control-modern" id="addLoaiDeTai" onchange="toggleAddMemberCount()">
                                <option value="Cá nhân">Cá nhân</option>
                                <option value="Nhóm">Nhóm</option>
                            </select>
                            
                            <div id="addMemberCountGroup" class="mt-2" style="display: none;">
                                <label class="form-label form-label-modern" style="font-size: 0.75rem;">Số lượng thành viên</label>
                                <input type="number" name="SoLuongThanhVien" class="form-control form-control-modern" 
                                       value="1" min="1" max="10">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-modern">
                                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Giảng viên hướng dẫn
                            </label>
                            <select name="MaGV" class="form-select form-control-modern">
                                <option value="">-- Chọn giảng viên --</option>
                                @foreach($gvs as $gv)
                                    <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-modern">
                                <i class="fas fa-user-tie text-secondary me-2"></i>Cán bộ quản lý
                            </label>
                            <select name="MaCB" class="form-select form-control-modern">
                                <option value="">-- Chọn cán bộ --</option>
                                @foreach($cbs as $cb)
                                    <option value="{{ $cb->MaCB }}">{{ $cb->TenCB }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern" style="background: var(--gray-200); color: var(--gray-600);" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                    <button type="submit" class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SỬA ĐỀ TÀI --}}
<div class="modal fade modal-modern" id="modalSuaDeTai" tabindex="-1" aria-labelledby="modalSuaDeTaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%); color: white;">
                <h5 class="modal-title" id="modalSuaDeTaiLabel">
                    <i class="fas fa-edit"></i> Sửa đề tài
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="editFormContent">
                {{-- Nội dung form sẽ được load bằng AJAX --}}
                <div class="text-center p-5">
                    <div class="spinner-modern"></div>
                    <p class="mt-3 text-muted">Đang tải...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DUYỆT NHIỀU ĐỀ TÀI --}}
<div class="modal fade modal-modern" id="modalDuyetNhieu" tabindex="-1" aria-labelledby="modalDuyetNhieuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%); color: white;">
                <h5 class="modal-title" id="modalDuyetNhieuLabel">
                    <i class="fas fa-check-double"></i> Duyệt đề tài và thiết lập thời gian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formDuyetNhieu" action="{{ route('admin.detai.approve-multiple') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-modern" style="background: var(--light-blue); color: var(--dark-blue); border-left: 4px solid var(--primary-blue);">
                        <i class="fas fa-info-circle"></i> 
                        Bạn đang duyệt <strong><span id="modalCountSelected">0</span> đề tài</strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern">
                            <i class="far fa-calendar-alt"></i> Ngày mở đăng ký
                        </label>
                        <input type="datetime-local" name="ThoiGianMoDangKy" class="form-control form-control-modern" required>
                        <small class="text-muted">Thời gian sinh viên bắt đầu được đăng ký đề tài</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern">
                            <i class="far fa-calendar-times"></i> Ngày đóng đăng ký
                        </label>
                        <input type="datetime-local" name="ThoiGianDongDangKy" class="form-control form-control-modern" required>
                        <small class="text-muted">Thời gian kết thúc đăng ký đề tài</small>
                    </div>

                    <input type="hidden" name="detai_ids" id="detaiIdsInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern" style="background: var(--gray-200); color: var(--gray-600);" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-modern btn-success-modern">
                        <i class="fas fa-check"></i> Xác nhận duyệt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle tất cả checkbox
function toggleCheckAll(source) {
    const checkboxes = document.querySelectorAll('.detai-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateSelectedCount();
}

function toggleAddMemberCount() {
    const select = document.getElementById('addLoaiDeTai');
    const group = document.getElementById('addMemberCountGroup');
    if (select.value === 'Nhóm') {
        group.style.display = 'block';
        // Set default to 2 if current value is 1
        const input = group.querySelector('input');
        if (input.value <= 1) input.value = 2;
    } else {
        group.style.display = 'none';
        group.querySelector('input').value = 1;
    }
}

// Cập nhật số lượng đã chọn
function updateSelectedCount() {
    const selected = document.querySelectorAll('.detai-checkbox:checked');
    const count = selected.length;
    
    document.getElementById('countSelected').textContent = count;
    
    // Logic hiển thị nút
    // Nếu có chọn -> Hiện cả 2 nút (Duyệt/Xóa)
    // Lưu ý: Backend sẽ tự lọc cái nào duyệt rồi, cái nào chưa
    const btnDuyet = document.getElementById('btnDuyetNhieu');
    const btnXoa = document.getElementById('btnXoaNhieu');

    if (count > 0) {
        btnDuyet.style.display = 'inline-block';
        btnXoa.style.display = 'inline-block';
    } else {
        btnDuyet.style.display = 'none';
        btnXoa.style.display = 'none';
    }
    
    // Bỏ check "chọn tất cả" nếu không phải tất cả đều được chọn
    const allCheckboxes = document.querySelectorAll('.detai-checkbox');
    const checkAll = document.getElementById('checkAll');
    if (checkAll) {
        checkAll.checked = (count > 0 && count === allCheckboxes.length);
    }
}
</script>

{{-- Bulk Delete Confirmation Modal --}}
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trash-alt" style="font-size: 40px; color: #d63031;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Xóa nhiều đề tài?</h5>
                <p class="text-muted mb-4" id="bulkDeleteMessage">Bạn có chắc chắn muốn xóa các đề tài đã chọn?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-danger px-4" id="confirmBulkDeleteBtn" style="border-radius: 20px;">
                        <i class="fas fa-trash me-2"></i> Xóa ngay
                    </button>
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 20px; background-color: #f1f2f6; color: #636e72; border: none;">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Xử lý Xóa Nhiều - Updated to use Modal
function confirmDeleteMultiple() {
    const selected = document.querySelectorAll('.detai-checkbox:checked');
    if (selected.length === 0) return;

    // Hiển thị modal thay vì alert
    const modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
    document.getElementById('bulkDeleteMessage').innerHTML = `
        Bạn có chắc chắn muốn xóa <strong>${selected.length}</strong> đề tài đã chọn?<br>
        <small class="text-danger">(Lưu ý: Chỉ những đề tài KHÔNG có sinh viên đăng ký mới được xóa)</small>
    `;
    
    document.getElementById('confirmBulkDeleteBtn').onclick = function() {
        const ids = Array.from(selected).map(cb => cb.value);
        document.getElementById('deleteMultipleInput').value = ids.join(',');
        document.getElementById('formXoaNhieu').submit();
    };
    
    modal.show();
}

function confirmDelete(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteMessage').textContent = `Xóa đề tài "${name}"?`;
    
    document.getElementById('confirmDeleteBtn').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
    };
    
    
    modal.show();
}

// Mở modal duyệt
function openApprovalModal() {
    const selected = document.querySelectorAll('.detai-checkbox:checked');
    
    if (selected.length === 0) {
        alert('Vui lòng chọn ít nhất một đề tài!');
        return;
    }
    
    // Lấy danh sách ID
    const ids = Array.from(selected).map(cb => cb.value);
    
    // Cập nhật modal
    document.getElementById('modalCountSelected').textContent = selected.length;
    document.getElementById('detaiIdsInput').value = ids.join(',');
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('modalDuyetNhieu'));
    modal.show();
}

// Mở modal duyệt cho 1 đề tài
function openSingleApprovalModal(maDeTai) {
    // Cập nhật modal
    document.getElementById('modalCountSelected').textContent = 1;
    document.getElementById('detaiIdsInput').value = maDeTai;
    
    // Hiển thị modal
    const modal = new bootstrap.Modal(document.getElementById('modalDuyetNhieu'));
    modal.show();
}

function toggleEditMemberCount() {
    const select = document.getElementById('editLoaiDeTai');
    const group = document.getElementById('editMemberCountGroup');
    if (!select || !group) return; // Guard clause incase elements don't exist yet

    if (select.value === 'Nhóm') {
        group.style.display = 'block';
        // Nếu chưa có giá trị > 1 thì set default là 2
        const input = group.querySelector('input');
        if (input.value <= 1) input.value = 2;
    } else {
        group.style.display = 'none';
        group.querySelector('input').value = 1;
    }
}

function loadEditForm(maDeTai) {
    const modal = new bootstrap.Modal(document.getElementById('modalSuaDeTai'));
    modal.show();
    
    // Load form sửa bằng AJAX
    fetch(`/admin/detai/${maDeTai}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('editFormContent').innerHTML = html;
            // Initialize the toggle state after content is loaded
            toggleEditMemberCount(); 
        })
        .catch(error => {
            document.getElementById('editFormContent').innerHTML = 
                '<div class="alert alert-danger m-3">Lỗi tải dữ liệu!</div>';
        });
}
</script>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #856404;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Bạn có chắc chắn muốn xóa đề tài này?</h5>
                <p class="text-muted mb-4" id="deleteMessage">Xóa đề tài này?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn" style="border-radius: 20px;">OK</button>
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 20px; background-color: #f8d7da; color: #721c24; border: none;">Hủy</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Complete Confirmation Modal --}}
<div class="modal fade" id="completeModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #856404;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Đánh dấu hoàn thành đề tài này?</h5>
                <p class="text-muted mb-4" id="completeMessage">Hoàn thành đề tài này?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-primary px-4" id="confirmCompleteBtn" style="border-radius: 20px;">OK</button>
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 20px; background-color: #f8d7da; color: #721c24; border: none;">Hủy</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteMessage').textContent = `Xóa đề tài "${name}"?`;
    
    document.getElementById('confirmDeleteBtn').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
    };
    
    modal.show();
}

function confirmComplete(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('completeModal'));
    document.getElementById('completeMessage').textContent = `Đánh dấu hoàn thành đề tài "${name}"?`;
    
    document.getElementById('confirmCompleteBtn').onclick = function() {
        document.getElementById('complete-form-' + id).submit();
    };
    
    modal.show();
}
</script>


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
</script>
@endsection