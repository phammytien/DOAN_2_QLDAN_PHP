@extends('layouts.canbo')

@section('title', 'Phân công phản biện')

@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex align-items-center mb-3">
        <h3 class="me-auto">📋 Phân công phản biện</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Thêm phân công
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($phancongs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã PC</th>
                                <th>Đề tài</th>
                                <th>Giảng viên</th>
                                <th>Vai trò</th>
                                <th>Ngày phân công</th>
                                <th>Ghi chú</th>
                                <th width="100">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phancongs as $pc)
                                <tr>
                                    <td>{{ $pc->MaPC }}</td>
                                    <td>
                                        <strong>{{ $pc->detai->TenDeTai ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">Mã: {{ $pc->MaDeTai }}</small>
                                    </td>
                                    <td>{{ $pc->giangvien->TenGV ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($pc->VaiTro) {
                                                'Chấm chính' => 'primary',
                                                'Chấm phụ' => 'info',
                                                'Phản biện' => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">{{ $pc->VaiTro ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $pc->NgayPhanCong ? \Carbon\Carbon::parse($pc->NgayPhanCong)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $pc->GhiChu ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-primary btn-edit"
                                                data-id="{{ $pc->MaPC }}"
                                                data-detai="{{ $pc->MaDeTai }}"
                                                data-giangvien="{{ $pc->MaGV }}"
                                                data-vaitro="{{ $pc->VaiTro }}"
                                                data-ngay="{{ $pc->NgayPhanCong }}"
                                                data-ghichu="{{ $pc->GhiChu }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('canbo.phanbien.destroy', $pc->MaPC) }}" method="POST" onsubmit="return confirm('Xóa phân công này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Chưa có phân công nào.
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL THÊM PHÂN CÔNG --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Thêm phân công</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('canbo.phanbien.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Đề tài <span class="text-danger">*</span></label>
                        <select name="MaDeTai" class="form-select" required>
                            <option value="">-- Chọn đề tài --</option>
                            @foreach($detais as $dt)
                                <option value="{{ $dt->MaDeTai }}">{{ $dt->TenDeTai }} ({{ $dt->MaDeTai }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giảng viên <span class="text-danger">*</span></label>
                        <select name="MaGV" class="form-select" required>
                            <option value="">-- Chọn giảng viên --</option>
                            @foreach($giangviens as $gv)
                                <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="VaiTro" class="form-select" required>
                            <option value="Chấm chính">Chấm chính</option>
                            <option value="Chấm phụ">Chấm phụ</option>
                            <option value="Phản biện">Phản biện</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày phân công <span class="text-danger">*</span></label>
                        <input type="date" name="NgayPhanCong" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="GhiChu" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SỬA PHÂN CÔNG --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Cập nhật phân công</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Đề tài <span class="text-danger">*</span></label>
                        <select name="MaDeTai" id="editMaDeTai" class="form-select" required>
                            @foreach($detais as $dt)
                                <option value="{{ $dt->MaDeTai }}">{{ $dt->TenDeTai }} ({{ $dt->MaDeTai }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giảng viên <span class="text-danger">*</span></label>
                        <select name="MaGV" id="editMaGV" class="form-select" required>
                            @foreach($giangviens as $gv)
                                <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="VaiTro" id="editVaiTro" class="form-select" required>
                            <option value="Chấm chính">Chấm chính</option>
                            <option value="Chấm phụ">Chấm phụ</option>
                            <option value="Phản biện">Phản biện</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày phân công <span class="text-danger">*</span></label>
                        <input type="date" name="NgayPhanCong" id="editNgayPhanCong" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="GhiChu" id="editGhiChu" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const detai = button.getAttribute('data-detai');
            const giangvien = button.getAttribute('data-giangvien');
            const vaitro = button.getAttribute('data-vaitro');
            const ngay = button.getAttribute('data-ngay');
            const ghichu = button.getAttribute('data-ghichu');

            const form = document.getElementById('editForm');
            form.action = `/canbo/phanbien/${id}`;

            document.getElementById('editMaDeTai').value = detai;
            document.getElementById('editMaGV').value = giangvien;
            document.getElementById('editVaiTro').value = vaitro;
            document.getElementById('editNgayPhanCong').value = ngay ? ngay.split(' ')[0] : '';
            document.getElementById('editGhiChu').value = ghichu || '';
        });
    });
</script>
@endsection
