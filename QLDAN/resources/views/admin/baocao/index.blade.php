@extends('layouts.admin')

@section('content')
<style>
    body {
        background-color: #f5f7fa;
    }
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .report-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .table thead th {
        background-color: #f8f9fa;
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        border: none;
        padding: 16px;
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
    .status-approved {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .status-request {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .btn-view {
        background: #4f46e5;
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
    }
    .btn-view:hover {
        background: #4338ca;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(79, 70, 229, 0.3);
    }
    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        font-size: 0.875rem;
    }
    .form-select:focus, .form-control:focus {
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
    }
    .btn-filter:hover {
        background: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    .file-link {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .file-link:hover {
        text-decoration: underline;
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="page-header">
        <h4 class="mb-0 fw-bold text-dark">
            <i class="fas fa-file-alt text-primary me-2"></i>
            Danh sách báo cáo 
        </h4>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- FILTER SECTION --}}
    <div class="filter-card">
        <form method="GET" id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-muted">Đề tài</label>
                <select name="detai" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Lọc theo đề tài...</option>
                    @foreach($detais as $dt)
                        <option value="{{ $dt->MaDeTai }}" {{ request('detai') == $dt->MaDeTai ? 'selected' : '' }}>
                            {{ Str::limit($dt->TenDeTai, 50) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-muted">Trạng thái</label>
                <select name="trangthai" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Tất cả</option>
                    <option value="Chờ duyệt" {{ request('trangthai') == 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="Đã duyệt" {{ request('trangthai') == 'Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="Yêu cầu chỉnh sửa" {{ request('trangthai') == 'Yêu cầu chỉnh sửa' ? 'selected' : '' }}>Yêu cầu chỉnh sửa</option>
                    <option value="Xin nộp bổ sung" {{ request('trangthai') == 'Xin nộp bổ sung' ? 'selected' : '' }}>Xin nộp bổ sung</option>
                </select>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.baocao.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-2"></i>Đặt lại
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="report-table">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Mã đề tài</th>
                        <th>Tên đề tài</th>
                        <th>Giảng viên</th>
                        <th>Sinh viên đăng ký</th>
                        <th style="width: 130px;">Trạng thái</th>
                        <th style="width: 100px;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Lấy danh sách đề tài có sinh viên đăng ký
                        $detaisWithStudents = \App\Models\DeTai::with(['giangVien', 'sinhViens.lop', 'baocaos'])
                            ->whereHas('sinhViens')
                            ->paginate(15);
                    @endphp
                    
                    @forelse($detaisWithStudents as $index => $detai)
                        @php
                            // Lấy báo cáo mới nhất của đề tài này
                            $latestBaoCao = $detai->baocaos->sortByDesc('NgayNop')->first();
                        @endphp
                        <tr>
                            <td class="fw-semibold text-muted">{{ $detaisWithStudents->firstItem() + $index }}</td>
                            
                            {{-- Mã đề tài --}}
                            <td>
                                <span class="badge bg-primary">{{ $detai->MaDeTai }}</span>
                            </td>
                            
                            {{-- Tên đề tài --}}
                            <td>
                                <div class="fw-semibold text-dark" style="max-width: 250px;">
                                    {{ Str::limit($detai->TenDeTai, 60) }}
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
                                @foreach($detai->sinhViens as $sv)
                                    <div class="{{ !$loop->last ? 'mb-2 pb-2 border-bottom' : '' }}">
                                        <div class="fw-medium">
                                            <i class="fas fa-user-circle text-primary me-1"></i>
                                            {{ $sv->HoTen ?? $sv->TenSV }}
                                        </div>
                                        <small class="text-muted">{{ $sv->MaSV }}</small>
                                        @if($sv->lop)
                                            <span class="badge bg-secondary ms-2">{{ $sv->lop->TenLop }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>

                            {{-- Trạng thái --}}
                            <td>
                                @php
                                    // Kiểm tra trạng thái duyệt điểm từ bảng ChamDiem
                                    $chamDiemStatus = \App\Models\ChamDiem::where('MaDeTai', $detai->MaDeTai)
                                        ->whereIn('MaSV', $detai->sinhViens->pluck('MaSV'))
                                        ->whereNull('MaTienDo') // Chỉ kiểm tra điểm báo cáo cuối
                                        ->first();
                                    
                                    $displayStatus = 'Chưa nộp';
                                    $statusClass = 'status-pending';
                                    
                                    if ($chamDiemStatus && $chamDiemStatus->DiemCuoi !== null) {
                                        // Có điểm cuối trong ChamDiem - ưu tiên hiển thị trạng thái từ đây
                                        if ($chamDiemStatus->TrangThai == 'Đã duyệt') {
                                            $displayStatus = 'Đã duyệt điểm';
                                            $statusClass = 'status-approved';
                                        } elseif ($chamDiemStatus->TrangThai == 'Chờ duyệt') {
                                            $displayStatus = 'Chờ duyệt điểm';
                                            $statusClass = 'status-pending';
                                        } else {
                                            $displayStatus = $chamDiemStatus->TrangThai;
                                            $statusClass = 'status-pending';
                                        }
                                    } elseif ($chamDiemStatus && $chamDiemStatus->Diem !== null) {
                                        // Có điểm nhưng chưa có DiemCuoi (chưa duyệt)
                                        $displayStatus = 'Chờ duyệt điểm';
                                        $statusClass = 'status-pending';
                                    } elseif ($latestBaoCao) {
                                        // Chưa có điểm, hiển thị trạng thái báo cáo
                                        $displayStatus = $latestBaoCao->TrangThai;
                                        $statusClass = 'status-pending';
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $displayStatus }}</span>
                            </td>

                            {{-- Hành động --}}
                            <td class="text-center">
                                @if($latestBaoCao)
                                    <button type="button" 
                                            class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $latestBaoCao->MaBC }}"
                                            title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @else
                                    <span class="text-muted small"> Chưa nộp</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
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
        @if($detaisWithStudents->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $detaisWithStudents->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- Modal chi tiết báo cáo --}}
@foreach($detaisWithStudents as $detai)
    @php
        // Lấy báo cáo mới nhất của đề tài này
        $latestBaoCao = \App\Models\BaoCao::where('MaDeTai', $detai->MaDeTai)
            ->orderBy('NgayNop', 'desc')
            ->first();
    @endphp
    
    @if($latestBaoCao)
    <div class="modal fade" id="detailModal{{ $latestBaoCao->MaBC }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Chi tiết báo cáo / tiến độ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Thông tin đề tài --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-book me-2"></i>Thông tin đề tài
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small">Tên đề tài:</label>
                                    <div class="fw-semibold">{{ $latestBaoCao->deTai->TenDeTai ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="text-muted small">Mã đề tài:</label>
                                    <div class="fw-semibold">{{ $latestBaoCao->deTai->MaDeTai ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="text-muted small">Giảng viên hướng dẫn:</label>
                                    <div class="fw-semibold">{{ $latestBaoCao->deTai->giangVien->TenGV ?? 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="text-muted small">Mô tả:</label>
                                    <div class="text-muted">{{ $latestBaoCao->deTai->MoTa ?? 'Chưa có mô tả' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin sinh viên --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-success">
                                <i class="fas fa-user-graduate me-2"></i>Thông tin sinh viên
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                // Lấy tất cả sinh viên trong đề tài
                                $allStudents = $latestBaoCao->deTai->sinhViens ?? collect();
                            @endphp
                            
                            @if($allStudents->count() > 0)
                                @foreach($allStudents as $sv)
                                    @php
                                        // Lấy điểm từ ChamDiem theo MaSV của sinh viên này
                                        $maDeTai = $latestBaoCao->MaDeTai;
                                        $maSV = $sv->MaSV;
                                        
                                        // Lấy tất cả điểm báo cáo cuối của đề tài này cho sinh viên này
                                        $allChamDiem = \App\Models\ChamDiem::with('giangVien')
                                            ->where('MaDeTai', $maDeTai)
                                            ->where('MaSV', $maSV)
                                            ->whereNull('MaTienDo') // Chỉ lấy điểm báo cáo cuối
                                            ->get();
                                        
                                        // Lấy thông tin phân công để xác định vai trò
                                        $phanCongs = \App\Models\PhanCong::where('MaDeTai', $maDeTai)->get();
                                        
                                        // Tìm GVHD (Giảng viên hướng dẫn chính)
                                        $gvhdPhanCong = $phanCongs->first(function($pc) {
                                            return str_contains(strtolower($pc->VaiTro ?? ''), 'hướng dẫn');
                                        });
                                        
                                        $chamDiemGVHD = null;
                                        $diemGVHD = null;
                                        $tenGVHD = null;
                                        
                                        if ($gvhdPhanCong) {
                                            $chamDiemGVHD = $allChamDiem->firstWhere('MaGV', $gvhdPhanCong->MaGV);
                                            if ($chamDiemGVHD) {
                                                $diemGVHD = $chamDiemGVHD->Diem;
                                                $tenGVHD = $chamDiemGVHD->giangVien ? $chamDiemGVHD->giangVien->TenGV : null;
                                            }
                                        }
                                        
                                        // Tìm GVPB (Giảng viên phản biện)
                                        $gvpbPhanCong = $phanCongs->first(function($pc) {
                                            return str_contains(strtolower($pc->VaiTro ?? ''), 'phản biện');
                                        });
                                        
                                        $chamDiemGVPB = null;
                                        $diemGVPB = null;
                                        $tenGVPB = null;
                                        
                                        if ($gvpbPhanCong) {
                                            $chamDiemGVPB = $allChamDiem->firstWhere('MaGV', $gvpbPhanCong->MaGV);
                                            if ($chamDiemGVPB) {
                                                $diemGVPB = $chamDiemGVPB->Diem;
                                                $tenGVPB = $chamDiemGVPB->giangVien ? $chamDiemGVPB->giangVien->TenGV : null;
                                            }
                                        }
                                        
                                        // Lấy điểm cuối (DiemCuoi) - điểm admin đã duyệt
                                        $chamDiemFinal = $allChamDiem->first(function($cd) {
                                            return $cd->DiemCuoi !== null;
                                        });
                                        $diemCuoi = $chamDiemFinal ? $chamDiemFinal->DiemCuoi : null;
                                        
                                        // Lấy điểm tiến độ (trung bình các lần chấm tiến độ)
                                        // Lấy danh sách tiến độ để tính điểm trung bình (bao gồm cả điểm nhóm)
                                        $listTienDo = \App\Models\TienDo::where('MaDeTai', $maDeTai)->get();
                                        $listChamDiemTienDo = \App\Models\ChamDiem::where('MaDeTai', $maDeTai)
                                            ->where('MaSV', $maSV)
                                            ->whereNotNull('MaTienDo')
                                            ->get();

                                        $totalTD = 0;
                                        $countTD = 0;
                                        foreach ($listTienDo as $td) {
                                            $score = null;
                                            $personal = $listChamDiemTienDo->firstWhere('MaTienDo', $td->MaTienDo);
                                            
                                            if ($personal) {
                                                $score = $personal->Diem;
                                            } elseif ($td->Diem !== null) {
                                                $score = $td->Diem;
                                            }

                                            if ($score !== null) {
                                                $totalTD += $score;
                                                $countTD++;
                                            }
                                        }
                                        $diemTienDo = $countTD > 0 ? ($totalTD / $countTD) : null;
                                    @endphp
                                    
                                    <div class="{{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="text-muted small">Mã sinh viên:</label>
                                                <div class="fw-semibold">{{ $sv->MaSV ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="text-muted small">Họ tên:</label>
                                                <div class="fw-semibold">{{ $sv->HoTen ?? $sv->TenSV ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="text-muted small">Email:</label>
                                                <div>{{ $sv->Email ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="text-muted small">Lớp:</label>
                                                <div>
                                                    <span class="badge bg-secondary">{{ $sv->lop->TenLop ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            
                                            {{-- Điểm của sinh viên này --}}
                                            <div class="col-12 mt-3">
                                                <div class="bg-light p-3 rounded">
                                                    <div class="row">
                                                        @if($diemTienDo !== null)
                                                            {{-- Có điểm tiến độ - hiển thị 4 cột --}}
                                                            <div class="col-md-3 mb-3">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block mb-1">Điểm tiến độ</small>
                                                                    <div class="small text-muted mb-1">(Trung bình)</div>
                                                                    <strong class="text-warning fs-5">
                                                                        {{ number_format($diemTienDo, 2) }}
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block mb-1">Điểm GVHD</small>
                                                                    @if($tenGVHD)
                                                                        <div class="small text-muted mb-1">{{ $tenGVHD }}</div>
                                                                    @endif
                                                                    <strong class="text-primary fs-5">
                                                                        {{ $diemGVHD !== null ? number_format($diemGVHD, 2) : '-' }}
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block mb-1">Điểm GVPB</small>
                                                                    @if($tenGVPB)
                                                                        <div class="small text-muted mb-1">{{ $tenGVPB }}</div>
                                                                    @endif
                                                                    <strong class="text-info fs-5">
                                                                        {{ $diemGVPB !== null ? number_format($diemGVPB, 2) : '-' }}
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block mb-1">Tổng điểm</small>
                                                                    <div class="small text-muted mb-1">(Admin duyệt)</div>
                                                                    <strong class="text-danger fs-5">
                                                                        {{ $diemCuoi !== null ? number_format($diemCuoi, 2) : '-' }}
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                        @else
                                                            {{-- Không có điểm tiến độ - hiển thị 3 cột --}}
                                                            <div class="col-md-4 mb-3">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block mb-1">Điểm GVHD</small>
                                                                    @if($tenGVHD)
                                                                        <div class="small text-muted mb-1">{{ $tenGVHD }}</div>
                                                                    @endif
                                                                    <strong class="text-primary fs-5">
                                                                        {{ $diemGVHD !== null ? number_format($diemGVHD, 2) : '-' }}
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block mb-1">Điểm GVPB</small>
                                                                    @if($tenGVPB)
                                                                        <div class="small text-muted mb-1">{{ $tenGVPB }}</div>
                                                                    @endif
                                                                    <strong class="text-info fs-5">
                                                                        {{ $diemGVPB !== null ? number_format($diemGVPB, 2) : '-' }}
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <div class="text-center">
                                                                    <small class="text-muted d-block mb-1">Tổng điểm</small>
                                                                    <div class="small text-muted mb-1">(Admin duyệt)</div>
                                                                    <strong class="text-danger fs-5">
                                                                        {{ $diemCuoi !== null ? number_format($diemCuoi, 2) : '-' }}
                                                                    </strong>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                {{-- Giải thích cách tính điểm --}}
                                                <div class="alert alert-info mt-3 mb-0" style="font-size: 0.875rem;">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <strong>Cách tính điểm tổng:</strong>
                                                    @if($diemTienDo !== null)
                                                        <div class="mt-2">
                                                            • Điểm tiến độ: Trung bình các lần chấm tiến độ<br>
                                                            • Điểm GVHD: Điểm của giảng viên hướng dẫn chính<br>
                                                            • Điểm GVPB: Điểm của giảng viên phản biện<br>
                                                            • Điểm báo cáo cuối = (Điểm GVHD + Điểm GVPB) / 2<br>
                                                            • <strong>Tổng điểm = Điểm tiến độ × 40% + Điểm báo cáo cuối × 60%</strong> (do Admin duyệt)
                                                        </div>
                                                    @else
                                                        <div class="mt-2">
                                                            • Điểm GVHD: Điểm của giảng viên hướng dẫn chính<br>
                                                            • Điểm GVPB: Điểm của giảng viên phản biện<br>
                                                            • <strong>Tổng điểm = (Điểm GVHD + Điểm GVPB) / 2</strong> (do Admin duyệt)<br>
                                                            <small class="text-muted">(Không có điểm tiến độ nên tổng điểm = điểm báo cáo cuối)</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-muted">Chưa có sinh viên đăng ký</div>
                            @endif
                        </div>
                    </div>

                    {{-- Thông tin báo cáo --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-info">
                                <i class="fas fa-file-alt me-2"></i>Thông tin báo cáo
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Ngày nộp:</label>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($latestBaoCao->NgayNop)->format('d/m/Y H:i:s') }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Lần nộp:</label>
                                    <div class="fw-semibold">
                                        <span class="badge bg-secondary">Lần {{ $latestBaoCao->LanNop }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Trạng thái:</label>
                                    <div>
                                        @if($latestBaoCao->TrangThai == 'Đã duyệt điểm')
                                            <span class="status-badge status-approved">Đã duyệt điểm</span>
                                        @elseif($latestBaoCao->TrangThai == 'Chờ duyệt điểm')
                                            <span class="status-badge status-pending">Chờ duyệt điểm</span>
                                        @elseif($latestBaoCao->TrangThai == 'Yêu cầu chấm lại')
                                            <span class="status-badge status-rejected">Yêu cầu chấm lại</span>
                                        @else
                                            <span class="status-badge status-pending">{{ $latestBaoCao->TrangThai }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small">File báo cáo:</label>
                                    <div>
                                        @if($latestBaoCao->fileBaoCao)
                                            @php
                                                $pathFile = str_replace('\\', '/', $latestBaoCao->fileBaoCao->path);
                                                $pathFile = ltrim($pathFile, '/');
                                                $urlFile = asset($pathFile);
                                            @endphp
                                            <a href="{{ $urlFile }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-2"></i>
                                                {{ $latestBaoCao->fileBaoCao->name }}
                                            </a>
                                        @elseif($latestBaoCao->LinkFile)
                                            @php
                                                $pathLink = str_replace('\\', '/', $latestBaoCao->LinkFile);
                                                $pathLink = ltrim($pathLink, '/');
                                                $urlLink = asset($pathLink);
                                            @endphp
                                            <a href="{{ $urlLink }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-2"></i>
                                                {{ $latestBaoCao->TenFile }}
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa có file</span>
                                        @endif
                                    </div>
                                </div>
                                @if($latestBaoCao->NhanXet)
                                    <div class="col-md-12">
                                        <label class="text-muted small">Nhận xét:</label>
                                        <div class="alert alert-info mb-0">
                                            {{ $latestBaoCao->NhanXet }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Modal từ chối điểm --}}
@foreach($baocaos as $bc)
    @if($bc->TrangThai == 'Chờ duyệt điểm')
        <div class="modal fade" id="tuChoiModal{{ $bc->MaBC }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Từ chối điểm</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.baocao.tuChoiDiem', $bc->MaBC) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p><strong>Đề tài:</strong> {{ $bc->deTai->TenDeTai }}</p>
                            <p><strong>Sinh viên:</strong> {{ $bc->sinhVien->TenSV }}</p>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lý do từ chối:</label>
                                <textarea name="LyDo" class="form-control" rows="4" required 
                                          placeholder="Nhập lý do yêu cầu chấm lại..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">Yêu cầu chấm lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
