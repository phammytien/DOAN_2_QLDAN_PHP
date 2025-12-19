@extends('layouts.admin')

@section('title', 'Quản lý chấm điểm')

@section('content')
<style>
    body {
        background: #f5f7fa !important;
    }

    .grading-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .header-icon {
        width: 48px;
        height: 48px;
        background: #e0e7ff;
        color: #4338ca;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .grading-table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    }

    .grading-table {
        width: 100%;
        border-collapse: collapse;
    }

    .grading-table th {
        background: #fff;
        padding: 1.25rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        border-bottom: 1px solid #f3f4f6;
        white-space: nowrap;
    }

    .grading-table td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: middle;
    }

    .grading-table tbody tr:hover {
        background: #f9fafb;
    }

    .grading-table tbody tr:last-child td {
        border-bottom: none;
    }

    .score-bold {
        font-weight: 700;
        color: #1f2937;
    }

    .score-final {
        font-weight: 700;
        color: #4f46e5;
    }

    .text-muted-custom {
        color: #9ca3af;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-none {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Custom select for status inside table */
    .status-select {
        border: none;
        background: transparent;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        width: 100%;
    }
    
    .status-select:focus {
        outline: none;
    }

    .pagination-container {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
</style>

<div class="grading-container">
    <div class="page-header">
        <div class="header-icon">
            <i class="fas fa-star"></i>
        </div>
        <h1 class="page-title">Danh sách chấm điểm</h1>
    </div>

    {{-- Summary Bar --}}
    <div class="summary-bar mb-4">
        <div class="summary-item">
            <i class="fas fa-clipboard-list text-primary"></i>
            <span class="summary-label">Tổng số:</span>
            <span class="summary-value">{{ $stats['total'] }}</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <i class="fas fa-check-circle text-success"></i>
            <span class="summary-label">Đã duyệt:</span>
            <span class="summary-value">{{ $stats['approved'] }}</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <i class="fas fa-clock text-warning"></i>
            <span class="summary-label">Chờ duyệt:</span>
            <span class="summary-value">{{ $stats['pending'] }}</span>
        </div>
        <div class="summary-divider"></div>
        <!-- <div class="summary-item">
            <i class="fas fa-star text-info"></i>
            <span class="summary-label">Điểm TB:</span>
            <span class="summary-value">{{ $stats['average'] }}</span>
        </div> -->
    </div>

    {{-- Distribution & Progress --}}
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="info-card">
                <h6 class="info-title"><i class="fas fa-chart-pie me-2"></i>Phân bố điểm</h6>
                <div class="distribution-badges">
                    <div class="dist-badge dist-danger">
                        <span class="dist-label">< 5</span>
                        <span class="dist-count">{{ $stats['distribution']['under5'] }}</span>
                    </div>
                    <div class="dist-badge dist-warning">
                        <span class="dist-label">5 - 8.9</span>
                        <span class="dist-count">{{ $stats['distribution']['from5to9'] }}</span>
                    </div>
                    <div class="dist-badge dist-success">
                        <span class="dist-label">≥ 9</span>
                        <span class="dist-count">{{ $stats['distribution']['above9'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <h6 class="info-title"><i class="fas fa-tasks me-2"></i>Tiến độ duyệt</h6>
                @php
                    $percentage = $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0;
                @endphp
                <div class="progress-info">
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="progress-text">{{ $percentage }}% hoàn thành</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .summary-bar {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 24px;
        }
        
        .summary-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .summary-item i {
            font-size: 18px;
        }
        
        .summary-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .summary-divider {
            width: 1px;
            height: 24px;
            background: #e5e7eb;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            height: 100%;
        }

        .info-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
        }

        .distribution-badges {
            display: flex;
            gap: 12px;
        }

        .dist-badge {
            flex: 1;
            padding: 16px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.2s;
        }

        .dist-badge:hover {
            transform: translateY(-2px);
        }

        .dist-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 2px solid #ef4444;
        }

        .dist-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
        }

        .dist-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 2px solid #22c55e;
        }

        .dist-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .dist-count {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        .progress-info {
            margin-top: 8px;
        }

        .progress-bar-custom {
            height: 12px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 10px;
            transition: width 1s ease;
        }

        .progress-text {
            display: block;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
        }
    </style>

    {{-- Filter Trigger Button --}}
    <div class="mb-4 d-flex justify-content-end">
        <button class="btn btn-white shadow-sm border d-flex align-items-center gap-2 px-3 py-2" 
                type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas"
                style="border-radius: 10px; font-weight: 500; color: #374151;">
            <i class="bi bi-funnel-fill text-primary"></i> Bộ lọc tìm kiếm
            @if($selectedLop !== 'all')
                <span class="badge bg-primary rounded-pill ms-1">1</span>
            @endif
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="grading-table-container">
        <table class="grading-table">
            <thead>
                <tr>
                    <th style="width: 50px;">STT</th>
                    <th style="width: 25%;">ĐỀ TÀI</th>
                    <th style="width: 15%;">SINH VIÊN</th>
                    <th style="width: 10%;">LỚP</th>
                    <th style="width: 15%;">GVHD</th>
                    <th class="text-center">ĐIỂM GVHD</th>
                    <th style="width: 15%;">GVPB</th>
                    <th class="text-center">ĐIỂM GVPB</th>
                    <th class="text-center">ĐIỂM TB</th>
                    <th class="text-center">ĐIỂM CUỐI</th>
                    <th style="width: 150px;">TRẠNG THÁI CHUNG</th>
                    <th class="text-center" style="width: 100px;">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
            @foreach($results as $index => $row)
                @php
                    $deTai = $cds[$row->MaDeTai] ?? null;
                    if (!$deTai) continue;
                    
                    $sv = $deTai->sinhViens->firstWhere('MaSV', $row->MaSV);
                    if (!$sv) continue;
                    
                    $gvhd = $deTai->phancongs->first(fn($pc) => str_contains(strtolower($pc->VaiTro),'hướng dẫn'));
                    $gvpb = $deTai->phancongs->first(fn($pc) => str_contains(strtolower($pc->VaiTro),'phản biện'));
                    
                    // Chỉ lấy điểm báo cáo cuối (MaTienDo = NULL), không lấy điểm tiến độ
                    $allCham = $deTai->chamdiems
                        ->where('MaSV', $sv->MaSV)
                        ->whereNull('MaTienDo'); // QUAN TRỌNG: Chỉ lấy điểm báo cáo cuối
                    
                    $cham = $allCham->first();
                    
                    $diemGVHD = $gvhd ? optional($allCham->firstWhere('MaGV',$gvhd->MaGV))->Diem : null;
                    $diemGVPB = $gvpb ? optional($allCham->firstWhere('MaGV',$gvpb->MaGV))->Diem : null;
                    
                    $diemTB = (is_numeric($diemGVHD) && is_numeric($diemGVPB))
                        ? ($diemGVHD + $diemGVPB) / 2
                        : ($diemGVHD ?? $diemGVPB ?? null);
                        
                    $diemCuoi = $cham?->DiemCuoi ?? null;
                    $maCham = $cham?->MaCham;
                @endphp

                <tr>
                    <td class="text-muted-custom">{{ $results->firstItem() + $index }}</td>
                    <td>
                        <div style="font-weight: 600; color: #111827;">{{ $deTai->TenDeTai }}</div>
                    </td>
                    <td>{{ $sv->TenSV }}</td>
                    <td>{{ $sv->lop->TenLop ?? $row->TenLop ?? 'Chưa cập nhật' }}</td>
                    
                    <td class="text-muted-custom">{{ $gvhd ? $gvhd->giangVien->TenGV : 'Chưa cập nhật' }}</td>
                    <td class="text-center score-bold">{{ is_numeric($diemGVHD) ? number_format($diemGVHD, 2) : 'Chưa cập nhật' }}</td>
                    
                    <td class="text-muted-custom">{{ $gvpb ? $gvpb->giangVien->TenGV : 'Chưa cập nhật' }}</td>
                    <td class="text-center score-bold">{{ is_numeric($diemGVPB) ? number_format($diemGVPB, 2) : 'Chưa cập nhật' }}</td>
                    
                    <td class="text-center score-bold">{{ $diemTB !== null ? number_format($diemTB, 2) : 'Chưa cập nhật' }}</td>
                    <td class="text-center score-final">{{ $diemCuoi !== null ? number_format($diemCuoi, 2) : 'Chưa cập nhật' }}</td>

                    <td>
                        @if($cham)
                            <form action="{{ route('admin.chamdiem.updateStatus', $maCham) }}" method="POST" id="form-status-{{ $maCham }}">
                                @csrf
                                @php
                                    $statusClass = match($cham->TrangThai) {
                                        'Đã duyệt' => 'badge-approved',
                                        'Chờ duyệt' => 'badge-pending',
                                        'Từ chối' => 'badge-rejected',
                                        default => 'badge-none'
                                    };
                                    $icon = match($cham->TrangThai) {
                                        'Đã duyệt' => 'fa-check-circle',
                                        'Chờ duyệt' => 'fa-hourglass-half',
                                        'Từ chối' => 'fa-times-circle',
                                        default => 'fa-circle'
                                    };
                                @endphp
                                
                                <div class="badge-status {{ $statusClass }}" style="cursor: pointer; position: relative;">
                                    <i class="fas {{ $icon }}"></i>
                                    <select name="TrangThai" class="status-select" 
                                            onchange="document.getElementById('form-status-{{ $maCham }}').submit()"
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                        <option value="Chưa xác nhận" {{ $cham->TrangThai=='Chưa xác nhận' ? 'selected' : '' }}>Chưa xác nhận</option>
                                        <option value="Chờ duyệt" {{ $cham->TrangThai=='Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                                        <option value="Đã duyệt" {{ $cham->TrangThai=='Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
                                        <option value="Từ chối" {{ $cham->TrangThai=='Từ chối' ? 'selected' : '' }}>Từ chối</option>
                                    </select>
                                    <span>{{ $cham->TrangThai }}</span>
                                </div>
                            </form>
                        @else
                            <span class="badge-status badge-none"> Chưa có điểm</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($cham)
                            <button type="button" class="btn btn-light text-danger btn-sm shadow-sm border-0" 
                                    onclick="confirmDelete('{{ $maCham }}', '{{ $sv->TenSV }}')"
                                    title="Xóa điểm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $results->links('pagination::bootstrap-4') }}
    </div>
</div>

{{-- Offcanvas Filter --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel" style="width: 350px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="filterOffcanvasLabel">
            <i class="bi bi-funnel me-2"></i>Bộ lọc tìm kiếm
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-light">
        <form action="{{ route('admin.chamdiem.index') }}" method="GET">
            <div class="mb-4">
                <label class="form-label fw-bold text-uppercase text-muted small mb-3">Lớp học</label>
                <div class="d-flex flex-column gap-2">
                    {{-- Option: Tất cả --}}
                    <label class="card shadow-sm border-0 cursor-pointer hover-shadow transition-all" style="cursor: pointer;">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-layers text-primary"></i>
                                </div>
                                <span class="fw-medium">Tất cả các lớp</span>
                            </div>
                            <input class="form-check-input" type="radio" name="lop_id" value="all" 
                                   {{ $selectedLop == 'all' ? 'checked' : '' }} style="transform: scale(1.2);" onchange="this.form.submit()">
                        </div>
                    </label>

                    {{-- Options: Các lớp --}}
                    @foreach($lops as $lop)
                        <label class="card shadow-sm border-0 cursor-pointer hover-shadow transition-all" style="cursor: pointer;">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <span class="small fw-bold text-muted">{{ substr($lop->TenLop, 0, 2) }}</span>
                                    </div>
                                    <span class="fw-medium">{{ $lop->TenLop }}</span>
                                </div>
                                <input class="form-check-input" type="radio" name="lop_id" value="{{ $lop->MaLop }}" 
                                       {{ $selectedLop == $lop->MaLop ? 'checked' : '' }} style="transform: scale(1.2);" onchange="this.form.submit()">
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

        </form>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <i class="bi bi-trash-fill text-danger" style="font-size: 32px;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Xác nhận xóa điểm?</h5>
                <p class="text-muted mb-4">
                    Bạn có chắc chắn muốn xóa điểm của sinh viên <b id="deleteSvName" class="text-dark"></b> không?<br>
                    Hành động này không thể hoàn tác.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4 fw-bold rounded-pill" data-bs-dismiss="modal">Hủy bỏ</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill">Xóa ngay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function confirmDelete(id, name) {
        document.getElementById('deleteSvName').innerText = name;
        document.getElementById('deleteForm').action = "{{ url('/admin/chamdiem') }}/" + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    @if($stats['by_project']->count() > 0)
    // Distribution Chart
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['< 5', '5-8.9', '≥ 9'],
            datasets: [{
                data: [
                    {{ $stats['distribution']['under5'] }},
                    {{ $stats['distribution']['from5to9'] }},
                    {{ $stats['distribution']['above9'] }}
                ],
                backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: { size: 11 }
                    }
                }
            }
        }
    });

    // Project Chart
    const projectCtx = document.getElementById('projectChart').getContext('2d');
    const projectNames = {!! json_encode($stats['by_project']->pluck('name')) !!};
    const avgScores = {!! json_encode($stats['by_project']->pluck('average')) !!};

    const truncatedNames = projectNames.map(name => {
        return name.length > 25 ? name.substring(0, 22) + '...' : name;
    });

    new Chart(projectCtx, {
        type: 'bar',
        data: {
            labels: truncatedNames,
            datasets: [{
                label: 'Điểm trung bình',
                data: avgScores,
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: '#6366f1',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return projectNames[context[0].dataIndex];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    @endif
</script>

@endsection
