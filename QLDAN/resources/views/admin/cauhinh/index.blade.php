@extends('layouts.admin')

@section('content')
<style>
    body {
        background: #f5f7fa !important;
    }

    .config-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .card-custom {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: none;
        height: 100%;
    }

    .card-header-custom {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .card-title-custom {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    .form-label-custom {
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control-custom {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .form-control-custom:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .btn-submit {
        background: #4f46e5;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #4338ca;
        transform: translateY(-1px);
    }

    .config-table {
        width: 100%;
        border-collapse: collapse;
    }

    .config-table th {
        background: #f9fafb;
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
    }

    .config-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: middle;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 0.5rem;
    }

    .btn-edit-icon {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .btn-delete-icon {
        background: #fee2e2;
        color: #ef4444;
    }

    .btn-edit-icon:hover, .btn-delete-icon:hover {
        transform: translateY(-2px);
    }
</style>

<div class="config-container">
    <div class="page-header">
        <h1 class="page-title">Cấu hình đăng ký</h1>
        <p class="page-subtitle">Quản lý thời gian đăng ký cho các năm học.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4 border-0 shadow-sm" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- LEFT COLUMN: FORM --}}
        <div class="col-lg-4">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">
                        <i class="fas fa-cog text-primary"></i>
                        Thêm cấu hình mới
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('admin.cauhinh.update') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label-custom">Năm học</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border: 2px solid #e5e7eb; border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-graduation-cap text-muted"></i>
                                </span>
                                <select name="MaNamHoc" class="form-select form-control-custom border-start-0 ps-0" style="border-radius: 0 8px 8px 0;" required>
                                    <option value="">-- Chọn năm học --</option>
                                    @foreach($namhoc as $nh)
                                        <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Thời gian mở</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border: 2px solid #e5e7eb; border-radius: 8px 0 0 8px;">
                                    <i class="far fa-calendar-alt text-muted"></i>
                                </span>
                                <input type="datetime-local" name="ThoiGianMoDangKy" class="form-control form-control-custom border-start-0 ps-0" style="border-radius: 0 8px 8px 0;" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Thời gian đóng</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border: 2px solid #e5e7eb; border-radius: 8px 0 0 8px;">
                                    <i class="far fa-calendar-times text-muted"></i>
                                </span>
                                <input type="datetime-local" name="ThoiGianDongDangKy" class="form-control form-control-custom border-start-0 ps-0" style="border-radius: 0 8px 8px 0;" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save me-2"></i>Lưu cấu hình
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: LIST --}}
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Danh sách cấu hình</h5>
                </div>
                <div class="card-body-custom p-0">
                    <div class="table-responsive">
                        <table class="config-table">
                            <thead>
                                <tr>
                                    <th>Năm học</th>
                                    <th>Mở đăng ký</th>
                                    <th>Đóng đăng ký</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configTheoNam as $c)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $c->namhoc->TenNamHoc }}</span>
                                    </td>
                                    <td>{{ $c->ThoiGianMoDangKy }}</td>
                                    <td>{{ $c->ThoiGianDongDangKy }}</td>
                                    <td class="text-end">
                                        <button type="button" 
                                                class="action-btn btn-edit-icon btn-edit" 
                                                data-id="{{ $c->id }}"
                                                title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.cauhinh.delete', $c->id) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn btn-delete-icon" onclick="return confirm('Xóa cấu hình này?')" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Cấu Hình -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border-radius: 16px 16px 0 0;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa cấu hình
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST">
                @csrf
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút Sửa
    const editButtons = document.querySelectorAll('.btn-edit');
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const editForm = document.getElementById('editForm');
    const editContent = document.getElementById('editContent');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            
            // Reset form action
            editForm.action = `/admin/cauhinh/${id}/update`; // Đã sửa lại đúng route updateTime
            
            // Show modal
            editModal.show();
            
            // Show loading
            editContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            // Fetch data
            fetch(`/admin/cauhinh/${id}/edit`, {
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
});
</script>

@endsection
