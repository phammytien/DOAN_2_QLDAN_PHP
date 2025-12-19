@extends('layouts.admin')

@section('title', 'Quản lý phân công')

@section('content')
<style>
/* ================================================
   MODERN BLUE THEME
   ================================================ */

/* === GENERAL === */
body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e8f4f8 100%);
    font-family: 'Inter', 'Segoe UI', sans-serif;
    color: #2c3e50;
}

/* === CONTAINER === */
.modern-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* === HEADER === */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(25, 118, 210, 0.3);
}

.page-title {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-add {
    background: white;
    color: #1565c0;
    border: none;
    padding: 12px 28px;
    font-weight: 600;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
    background: #f5f5f5;
}

/* === ALERT === */
.modern-alert {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: none;
    border-left: 4px solid #2196f3;
    border-radius: 12px;
    padding: 16px 20px;
    color: #0d47a1;
    font-weight: 500;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.15);
    animation: slideDown 0.4s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* === TABLE CARD === */
.table-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

/* === TABLE === */
.modern-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
}

.modern-table thead {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
}

.modern-table thead th {
    color: white;
    font-weight: 600;
    padding: 18px 20px;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border: none;
    text-align: left;
}

.modern-table tbody tr {
    border-bottom: 1px solid #f0f4f8;
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fbff 0%, #e8f4f8 100%);
    transform: scale(1.005);
}

.modern-table tbody td {
    padding: 18px 20px;
    vertical-align: middle;
    border: none;
    color: #37474f;
}

.modern-table tbody tr:last-child {
    border-bottom: none;
}

/* === BADGE === */
.role-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.role-huongdan {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0;
}

.role-phanbien {
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    color: #6a1b9a;
}

/* === ACTION BUTTONS === */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    padding: 8px 18px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-edit {
    background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%);
    color: white;
    box-shadow: 0 3px 10px rgba(33, 150, 243, 0.3);
}

.btn-edit:hover {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(33, 150, 243, 0.4);
}

.btn-delete {
    background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
    color: white;
    box-shadow: 0 3px 10px rgba(239, 83, 80, 0.3);
}

.btn-delete:hover {
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 83, 80, 0.4);
}

/* === MODAL === */
.modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: white;
    padding: 24px 28px;
    border: none;
}

.modal-title {
    font-weight: 700;
    font-size: 1.4rem;
    margin: 0;
}

.btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: all 0.3s ease;
}

.btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
}

.modal-body {
    padding: 28px;
    background: #fafbfc;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    color: #1565c0;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 0.95rem;
    display: block;
}

.form-control, .form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e3f2fd;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: #42a5f5;
    box-shadow: 0 0 0 4px rgba(66, 165, 245, 0.1);
    outline: none;
}

.form-control:hover, .form-select:hover {
    border-color: #90caf9;
}

.modal-footer {
    background: white;
    border-top: 2px solid #f0f4f8;
    padding: 20px 28px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn-modal {
    padding: 12px 28px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel {
    background: #e0e0e0;
    color: #616161;
}

.btn-cancel:hover {
    background: #bdbdbd;
}

.btn-submit {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
}

.btn-submit:hover {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
}

/* === INDEX BADGE === */
.index-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.9rem;
}

/* === ANIMATIONS === */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-container {
    animation: fadeIn 0.6s ease;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .modern-table {
        font-size: 0.85rem;
    }
    
    .modern-table thead th,
    .modern-table tbody td {
        padding: 12px 10px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="modern-container">
    <!-- HEADER -->
    <div class="page-header">
        <h2 class="page-title">
            🎯 Danh sách phân công
        </h2>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
            ➕ Thêm mới
        </button>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotificationModal('success', 'Thành công!', '{{ session('success') }}');
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

    <!-- TABLE CARD -->
    <div class="table-card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Đề tài</th>
                    <th>Giảng viên</th>
                    <th>Vai trò</th>
                    <th>Ngày phân công</th>
                    <th style="width: 200px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($phancongs as $pc)
                <tr>
                    <td>
                        <span class="index-badge">{{ $loop->iteration }}</span>
                    </td>
                    <td><strong>{{ $pc->detai->TenDeTai ?? '-' }}</strong></td>
                    <td>{{ $pc->giangvien->TenGV ?? '-' }}</td>
                    <td>
                        <span class="role-badge {{ $pc->VaiTro == 'Hướng dẫn' ? 'role-huongdan' : 'role-phanbien' }}">
                            {{ $pc->VaiTro }}
                        </span>
                    </td>
                    <td>{{ $pc->NgayPhanCong }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-edit"
                                data-id="{{ $pc->MaPC }}"
                                data-detai="{{ $pc->MaDeTai }}"
                                data-giangvien="{{ $pc->MaGV }}"
                                data-vaitro="{{ $pc->VaiTro }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal">
                                ✏️ Edit
                            </button>

                            <form id="delete-form-{{ $pc->MaPC }}" action="{{ route('admin.phancong.destroy', $pc->MaPC) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-action btn-delete"
                                        onclick="confirmDelete('{{ $pc->MaPC }}', '{{ $pc->detai->TenDeTai ?? 'phân công này' }}')">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($phancongs->hasPages())
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $phancongs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<style>
/* Pagination Styling */
.pagination {
    gap: 8px;
}

.pagination .page-link {
    border: 2px solid #e3f2fd;
    border-radius: 8px;
    color: #1565c0;
    padding: 10px 16px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-color: #42a5f5;
    color: #0d47a1;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    border-color: #1976d2;
    color: white;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.pagination .page-item.disabled .page-link {
    background: #f5f5f5;
    border-color: #e0e0e0;
    color: #9e9e9e;
}
</style>


{{-- ========================================================= --}}
{{-- ===============   MODAL THÊM PHÂN CÔNG   ================= --}}
{{-- ========================================================= --}}
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">➕ Thêm phân công mới</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <form action="{{ route('admin.phancong.store') }}" method="POST">
              @csrf

              <div class="modal-body">
                  
                  <div class="form-group">
                      <label class="form-label">📚 Đề tài</label>
                      <select name="MaDeTai" class="form-select" required>
                          <option value="">-- Chọn đề tài --</option>
                          @foreach($detais as $dt)
                              <option value="{{ $dt->MaDeTai }}">{{ $dt->TenDeTai }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="form-group">
                      <label class="form-label">👨‍🏫 Giảng viên</label>
                      <select name="MaGV" class="form-select" required>
                          <option value="">-- Chọn giảng viên --</option>
                          @foreach($giangviens as $gv)
                              <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="form-group">
                      <label class="form-label">🎭 Vai trò</label>
                      <select name="VaiTro" class="form-select" required>
                          <option value="Hướng dẫn">Hướng dẫn</option>
                          <option value="Phản biện">Phản biện</option>
                      </select>
                  </div>

              </div>

              <div class="modal-footer">
                  <button type="button" class="btn-modal btn-cancel" data-bs-dismiss="modal">❌ Hủy</button>
                  <button type="submit" class="btn-modal btn-submit">💾 Lưu</button>
              </div>

          </form>
      </div>
  </div>
</div>



{{-- ========================================================= --}}
{{-- ===============       MODAL EDIT         ================= --}}
{{-- ========================================================= --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">✏️ Chỉnh sửa phân công</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <form id="editForm" method="POST">
              @csrf
              @method('PUT')

              <div class="modal-body">

                  <div class="form-group">
                      <label class="form-label">📚 Đề tài</label>
                      <select name="MaDeTai" id="editMaDeTai" class="form-select" required>
                          @foreach($detais as $dt)
                              <option value="{{ $dt->MaDeTai }}">{{ $dt->TenDeTai }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="form-group">
                      <label class="form-label">👨‍🏫 Giảng viên</label>
                      <select name="MaGV" id="editMaGV" class="form-select" required>
                          @foreach($giangviens as $gv)
                              <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="form-group">
                      <label class="form-label">🎭 Vai trò</label>
                      <select name="VaiTro" id="editVaiTro" class="form-select" required>
                          <option value="Hướng dẫn">Hướng dẫn</option>
                          <option value="Phản biện">Phản biện</option>
                      </select>
                  </div>

              </div>

              <div class="modal-footer">
                  <button type="button" class="btn-modal btn-cancel" data-bs-dismiss="modal">❌ Hủy</button>
                  <button type="submit" class="btn-modal btn-submit">✅ Cập nhật</button>
              </div>

          </form>
      </div>
  </div>
</div>


@endsection


@section('scripts')
<script>
    // Khi bấm nút EDIT → mở modal và fill dữ liệu
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.dataset.id;
            let detai = this.dataset.detai;
            let giangvien = this.dataset.giangvien;
            let vaitro = this.dataset.vaitro;

            // Set action động cho form edit
            document.getElementById('editForm').action =
                "/admin/phancong/" + id;

            // Fill giá trị vào select
            document.getElementById('editMaDeTai').value = detai;
            document.getElementById('editMaGV').value = giangvien;
            document.getElementById('editVaiTro').value = vaitro;
        });
    });
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
                <h5 class="fw-bold mb-3">Bạn có chắc chắn muốn xóa?</h5>
                <p class="text-muted mb-4" id="deleteMessage">Xóa phân công này?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn" style="border-radius: 20px;">OK</button>
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 20px; background-color: #f8d7da; color: #721c24; border: none;">Hủy</button>
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
function confirmDelete(id, name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteMessage').textContent = `Xóa phân công cho đề tài "${name}"?`;
    
    document.getElementById('confirmDeleteBtn').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
    };
    
    modal.show();
}

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