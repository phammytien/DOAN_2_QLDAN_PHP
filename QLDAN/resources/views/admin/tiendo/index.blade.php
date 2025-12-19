@extends('layouts.admin')

@section('content')
<style>
    body {
        background-color: #f5f7fa;
    }
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        border-left: 4px solid #4f46e5;
    }
    .page-header h4 {
        margin: 0;
        color: #1f2937;
        font-size: 1.25rem;
        font-weight: 600;
    }
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .progress-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .table thead th {
        background-color: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }
    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-has-progress {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-no-progress {
        background-color: #fef3c7;
        color: #92400e;
    }
    .btn-add-progress {
        background: #10b981;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-add-progress:hover {
        background: #059669;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-view-progress {
        background: #06b6d4;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-view-progress:hover {
        background: #0891b2;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        font-size: 0.875rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .btn-filter {
        background: #4f46e5;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: all 0.2s;
        width: 100%;
    }
    .btn-filter:hover {
        background: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    .student-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .student-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
    }
    .student-badge {
        background: #eff6ff;
        color: #1e40af;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .progress-count {
        background: #f3f4f6;
        color: #374151;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="page-header">
        <h4>
            <i class="fas fa-tasks me-2"></i>
            Quản lý tiến độ đề tài
        </h4>
    </div>

    {{-- FILTER SECTION --}}
    <div class="filter-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Tên đề tài</label>
                <input type="text" name="detai" class="form-control" placeholder="Nhập tên đề tài..." value="{{ request('detai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Sinh viên</label>
                <input type="text" name="sinhvien" class="form-control" placeholder="Nhập tên sinh viên..." value="{{ request('sinhvien') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Trạng thái</label>
                <select name="trangthai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="Có tiến độ" {{ request('trangthai') == 'Có tiến độ' ? 'selected' : '' }}>Có tiến độ</option>
                    <option value="Chưa có tiến độ" {{ request('trangthai') == 'Chưa có tiến độ' ? 'selected' : '' }}>Chưa có tiến độ</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-filter">
                    <i class="fas fa-filter me-2"></i>Lọc
                </button>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="progress-table">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Mã đề tài</th>
                        <th>Tên đề tài</th>
                        <th>Giảng viên</th>
                        <th>Sinh viên đăng ký</th>
                        <th style="width: 120px;" class="text-center">Tiến độ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detais as $index => $detai)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $detais->firstItem() + $index }}</td>
                        
                        {{-- Mã đề tài --}}
                        <td>
                            <span class="badge bg-primary">{{ $detai->MaDeTai }}</span>
                        </td>
                        
                        {{-- Tên đề tài --}}
                        <td>
                            <div class="fw-semibold text-dark" style="max-width: 300px;">
                                {{ Str::limit($detai->TenDeTai, 80) }}
                            </div>
                        </td>

                        {{-- Giảng viên --}}
                        <td>
                            <div class="small">
                                {{ $detai->giangVien->TenGV ?? 'Chưa gán' }}
                            </div>
                        </td>

                        {{-- Sinh viên đăng ký --}}
                        <td>
                            <div class="student-list">
                                @foreach($detai->sinhViens as $sv)
                                    <div class="student-item">
                                        <i class="fas fa-user-circle text-primary"></i>
                                        <span>{{ $sv->HoTen ?? $sv->TenSV }}</span>
                                        @if($sv->lop)
                                            <span class="student-badge">{{ $sv->lop->TenLop }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- Số lượng tiến độ --}}
                        <td class="text-center">
                            @php
                                $progressCount = $detai->tiendos->count();
                            @endphp
                            @if($progressCount > 0)
                                <span class="progress-count">
                                    <i class="fas fa-check-circle text-success"></i>
                                    {{ $progressCount }} tiến độ
                                </span>
                            @else
                                <span class="status-badge status-no-progress">
                                    Chưa có
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">Chưa có đề tài nào có sinh viên đăng ký</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($detais, 'links') && $detais->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $detais->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@endsection
