@extends('layouts.admin')

@section('content')
<style>
    .stats-container {
        padding: 30px;
        background: #f5f7fa;
        min-height: calc(100vh - 70px);
    }

    .page-header {
        background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(25, 118, 210, 0.3);
    }

    .page-title {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 16px;
        border: none;
    }

    .table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr:hover {
        background: #f8f9ff;
    }

    .btn-export {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        color: white;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
        color: white;
    }

    .badge-score {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .score-excellent {
        background: #d1fae5;
        color: #065f46;
    }

    .score-good {
        background: #dbeafe;
        color: #1e40af;
    }

    .score-average {
        background: #fef3c7;
        color: #92400e;
    }

    .score-poor {
        background: #fee2e2;
        color: #991b1b;
    }

    .score-none {
        background: #f3f4f6;
        color: #6b7280;
    }
</style>

<div class="stats-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-chart-line"></i>
            Thống kê điểm theo lớp
        </h1>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Chọn lớp</label>
                <select name="lop" class="form-select" required>
                    <option value="">-- Chọn lớp --</option>
                    @foreach($lops as $lop)
                        <option value="{{ $lop->MaLop }}" {{ request('lop') == $lop->MaLop ? 'selected' : '' }}>
                            {{ $lop->TenLop }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Năm học</label>
                <select name="namhoc" class="form-select">
                    <option value="">-- Tất cả --</option>
                    @foreach($namhocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}" {{ request('namhoc') == $nh->MaNamHoc ? 'selected' : '' }}>
                            {{ $nh->TenNamHoc }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Xem thống kê
                </button>
            </div>
        </form>
    </div>

    @if(isset($sinhviens) && count($sinhviens) > 0)
    <!-- Export Button -->
    <div class="mb-3 text-end">
        <a href="{{ route('admin.baocao.export-diem', ['lop' => request('lop'), 'namhoc' => request('namhoc')]) }}" 
           class="btn-export">
            <i class="fas fa-file-excel"></i>
            Xuất file Excel điểm lớp {{ $lopName }}
        </a>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>MSSV</th>
                        <th>Họ tên</th>
                        <th>Đề tài</th>
                        <th>GVHD</th>
                        <th style="width: 120px;" class="text-center">Điểm TB</th>
                        <th style="width: 150px;" class="text-center">Xếp loại</th>
                        <!-- <th style="width: 120px;" class="text-center">File báo cáo</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($sinhviens as $index => $sv)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $sv->MaSV }}</td>
                        <td>{{ $sv->TenSV }}</td>
                        <td>
                            @if($sv->deTai)
                                <div style="max-width: 300px;">{{ $sv->deTai->TenDeTai }}</div>
                            @else
                                <span class="text-muted">Chưa có đề tài</span>
                            @endif
                        </td>
                        <td>
                            @if($sv->deTai && $sv->deTai->giangVien)
                                {{ $sv->deTai->giangVien->TenGV }}
                            @else
                                <span class="text-muted">Chưa có GVHD</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($sv->diemTrungBinh)
                                <span class="fw-bold" style="font-size: 1.1rem;">{{ number_format($sv->diemTrungBinh, 1) }}</span>
                            @else
                                <span class="text-muted">Chưa có điểm</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($sv->diemTrungBinh)
                                @if($sv->diemTrungBinh >= 8.5)
                                    <span class="badge-score score-excellent">Xuất sắc</span>
                                @elseif($sv->diemTrungBinh >= 8.0)
                                    <span class="badge-score score-good">Giỏi</span>
                                @elseif($sv->diemTrungBinh >= 6.5)
                                    <span class="badge-score score-average">Khá</span>
                                @elseif($sv->diemTrungBinh >= 5.0)
                                    <span class="badge-score score-poor">Trung bình</span>
                                @else
                                    <span class="badge-score score-poor">Yếu</span>
                                @endif
                            @else
                                <span class="badge-score score-none">Chưa chấm</span>
                            @endif
                        </td>
                        <!-- <td class="text-center">
                            @if($sv->baoCao && $sv->baoCao->fileBaoCao)
                                <a href="{{ asset($sv->baoCao->fileBaoCao->path) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mt-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-primary mb-1">{{ count($sinhviens) }}</h3>
                    <p class="text-muted small mb-0">Tổng sinh viên</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-success mb-1">{{ $sinhviens->where('diemTrungBinh', '>=', 5)->count() }}</h3>
                    <p class="text-muted small mb-0">Đạt (≥ 5.0)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-warning mb-1">{{ $sinhviens->where('diemTrungBinh', '<', 5)->where('diemTrungBinh', '>', 0)->count() }}</h3>
                    <p class="text-muted small mb-0">Không đạt (< 5.0)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-secondary mb-1">{{ $sinhviens->whereNull('diemTrungBinh')->count() }}</h3>
                    <p class="text-muted small mb-0">Chưa có điểm</p>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Vui lòng chọn lớp để xem thống kê điểm</p>
        </div>
    @endif
</div>
@endsection
