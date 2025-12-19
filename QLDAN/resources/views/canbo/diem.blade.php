@extends('layouts.canbo')

@section('title', 'Quản lý điểm')

@section('content')
<style>
    :root {
        --primary-dark: #0f172a;
        --card-bg: #ffffff;
        --text-main: #334155;
        --text-light: #64748b;
    }
    .dashboard-card {
        background-color: var(--card-bg);
        color: var(--text-main);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .dashboard-card h6 {
        color: var(--text-light);
        font-size: 0.875rem;
        margin-bottom: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .dashboard-card h2 {
        font-size: 2.25rem;
        font-weight: 800;
        margin: 0;
        color: var(--primary-dark);
        line-height: 1;
    }
    .chart-container {
        background-color: var(--card-bg);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .table-custom {
        background-color: var(--card-bg);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    .table-custom thead {
        background-color: #f8fafc;
    }
    .table-custom th {
        border-bottom: 1px solid #e2e8f0;
        color: var(--text-light);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 16px 24px;
    }
    .table-custom td {
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        color: var(--text-main);
        padding: 16px 24px;
        font-size: 0.925rem;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
    }
    .status-cho-duyet { background: #fef9c3; color: #854d0e; }
    .status-da-duyet { background: #dcfce7; color: #166534; }
    .status-tu-choi { background: #fee2e2; color: #991b1b; }
    .status-chua-xac-nhan { background: #f1f5f9; color: #475569; }
    
    .section-title {
        color: var(--primary-dark);
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .stat-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 3rem;
        opacity: 0.1;
        color: currentColor;
    }

    /* Score colors */
    .score-under5 { color: #ef4444; font-weight: 600; }
    .score-5to7 { color: #f59e0b; font-weight: 600; }
    .score-7to9 { color: #3b82f6; font-weight: 600; }
    .score-above9 { color: #22c55e; font-weight: 600; }
</style>

<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary-dark);">Quản lý Chấm Điểm</h4>
            <p class="text-muted mb-0">Tổng quan và quản lý kết quả chấm điểm của sinh viên.</p>
        </div>
        <div class="d-flex gap-3">
            <div class="input-group shadow-sm" style="width: 300px; border-radius: 10px; overflow: hidden;">
                <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-0 ps-2" placeholder="Tìm kiếm nhanh...">
            </div>
            <a href="{{ route('canbo.diem.exportExcel') }}" class="btn btn-success text-white shadow-sm rounded-3">
                <i class="fas fa-file-excel me-2"></i>Xuất Excel
            </a>
            <button type="button" class="btn btn-primary text-white shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#exportByClassModal">
                <i class="fas fa-file-export me-2"></i>Xuất theo lớp
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Điểm trung bình</h6>
                <h2 class="text-primary">{{ $stats['average'] }}</h2>
                <i class="fas fa-chart-line stat-icon text-primary"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Điểm < 5</h6>
                <h2 class="text-danger">{{ $stats['distribution']['under5'] }}</h2>
                <i class="fas fa-exclamation-triangle stat-icon text-danger"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Điểm 5-8.9</h6>
                <h2 class="text-warning">{{ $stats['distribution']['from5to9'] }}</h2>
                <i class="fas fa-minus-circle stat-icon text-warning"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Điểm ≥ 9</h6>
                <h2 class="text-success">{{ $stats['distribution']['above9'] }}</h2>
                <i class="fas fa-star stat-icon text-success"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    @if($stats['by_project']->count() > 0)
    <div class="row g-4 mb-5">
        <!-- Mixed Chart: Avg Score & Student Count per Project -->
        <div class="col-md-8">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-chart-bar text-primary"></i>
                        Thống kê theo Đề tài (Top 10)
                    </h5>
                </div>
                <div style="height: 350px; position: relative;">
                    <canvas id="mixedChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Pie Chart: Score Distribution -->
        <div class="col-md-4">
            <div class="chart-container">
                <h5 class="section-title">
                    <i class="fas fa-chart-pie text-info"></i>
                    Phân bố điểm
                </h5>
                <div style="height: 350px; position: relative;">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter & Table Section -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('canbo.diem') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0" 
                                   placeholder="Tên đề tài..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-bold text-uppercase">Đề tài</label>
                        <select name="detai" class="form-select bg-light" onchange="this.form.submit()">
                            <option value="">Tất cả đề tài</option>
                            @foreach($allDetais as $dt)
                                <option value="{{ $dt->MaDeTai }}" {{ request('detai') == $dt->MaDeTai ? 'selected' : '' }}>
                                    {{ $dt->TenDeTai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Trạng thái</label>
                        <select name="trangthai" class="form-select bg-light" onchange="this.form.submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Chưa xác nhận" {{ request('trangthai') == 'Chưa xác nhận' ? 'selected' : '' }}>Chưa xác nhận</option>
                            <option value="Chờ duyệt" {{ request('trangthai') == 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="Đã duyệt" {{ request('trangthai') == 'Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="Từ chối" {{ request('trangthai') == 'Từ chối' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-bold text-uppercase">Lớp</label>
                        <select name="lop" class="form-select bg-light" onchange="this.form.submit()">
                            <option value="">Tất cả lớp</option>
                            @foreach($allLops as $lop)
                                <option value="{{ $lop->MaLop }}" {{ request('lop') == $lop->MaLop ? 'selected' : '' }}>
                                    {{ $lop->TenLop }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('canbo.diem') }}" class="btn btn-light w-100 fw-semibold border">
                            <i class="fas fa-sync-alt me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-responsive table-custom">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">#</th>
                    <th>Đề tài</th>
                    <th>Sinh viên</th>
                    <th>Lớp</th>
                    <th>GVHD / Điểm</th>
                    <th>GVPB / Điểm</th>
                    <th class="text-center">Điểm TB</th>
                    <th class="text-center">Điểm Cuối</th>
                    <th>Trạng thái</th>
                    <th class="text-end pe-4">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $index => $row)
                    @php
                        $deTai = $detais[$row->MaDeTai] ?? null;
                        if (!$deTai) continue;
                        
                        $sv = $deTai->sinhViens->firstWhere('MaSV', $row->MaSV);
                        if (!$sv) continue;
                        
                        $gvhd = $deTai->phancongs->first(fn($pc) => str_contains(strtolower($pc->VaiTro),'hướng dẫn'));
                        $gvpb = $deTai->phancongs->first(fn($pc) => str_contains(strtolower($pc->VaiTro),'phản biện'));
                        
                        $allCham = $deTai->chamdiems->where('MaSV', $sv->MaSV);
                        $cham = $allCham->first();

                        $diemGVHD = $gvhd ? optional($allCham->firstWhere('MaGV',$gvhd->MaGV))->Diem : null;
                        $diemGVPB = $gvpb ? optional($allCham->firstWhere('MaGV',$gvpb->MaGV))->Diem : null;

                        $diemTB = (is_numeric($diemGVHD) && is_numeric($diemGVPB))
                            ? ($diemGVHD + $diemGVPB) / 2
                            : ($diemGVHD ?? $diemGVPB ?? null);

                        $diemCuoi = $cham?->DiemCuoi ?? null;
                        $maCham = $cham?->MaCham;
                        
                        $scoreClass = '';
                        if ($diemTB !== null) {
                            if ($diemTB < 5) $scoreClass = 'score-under5';
                            elseif ($diemTB < 7) $scoreClass = 'score-5to7';
                            elseif ($diemTB < 9) $scoreClass = 'score-7to9';
                            else $scoreClass = 'score-above9';
                        }
                    @endphp

                    <tr>
                        <td class="ps-4">{{ $results->firstItem() + $index }}</td>
                        <td>
                            <div class="text-truncate fw-semibold" style="max-width: 200px;" title="{{ $deTai->TenDeTai }}">
                                {{ $deTai->TenDeTai }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-light text-primary d-flex align-items-center justify-content-center fw-bold me-2" style="width: 28px; height: 28px; font-size: 11px;">
                                    {{ substr($sv->TenSV, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $sv->TenSV }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $sv->MaSV }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $sv->lop->TenLop ?? $row->TenLop ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <small class="text-muted">{{ $gvhd ? $gvhd->giangVien->TenGV : '-' }}</small>
                                <span class="fw-bold {{ is_numeric($diemGVHD) ? 'text-primary' : 'text-muted' }}">
                                    {{ is_numeric($diemGVHD) ? number_format($diemGVHD, 2) : '--' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <small class="text-muted">{{ $gvpb ? $gvpb->giangVien->TenGV : '-' }}</small>
                                <span class="fw-bold {{ is_numeric($diemGVPB) ? 'text-primary' : 'text-muted' }}">
                                    {{ is_numeric($diemGVPB) ? number_format($diemGVPB, 2) : '--' }}
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="{{ $scoreClass }} fs-6">
                                {{ $diemTB !== null ? number_format($diemTB, 2) : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="{{ $scoreClass }} fs-6 fw-bold">
                                {{ $diemCuoi !== null ? number_format($diemCuoi, 2) : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($cham)
                                @php
                                    $statusClass = match($cham->TrangThai) {
                                        'Đã duyệt' => 'status-da-duyet',
                                        'Chờ duyệt' => 'status-cho-duyet',
                                        'Từ chối' => 'status-tu-choi',
                                        default => 'status-chua-xac-nhan'
                                    };
                                @endphp
                                
                                @if($cham->TrangThai === 'Đã duyệt')
                                    <span class="status-badge {{ $statusClass }}">{{ $cham->TrangThai }}</span>
                                @else
                                    <form action="{{ route('canbo.diem.updateStatus', $maCham) }}" method="POST">
                                        @csrf
                                        <select name="TrangThai" class="form-select form-select-sm border-0 bg-light fw-medium" 
                                                style="width: 130px; font-size: 0.8rem;" onchange="this.form.submit()">
                                            <option value="Chưa xác nhận" {{ $cham->TrangThai=='Chưa xác nhận' ? 'selected' : '' }}>Chưa xác nhận</option>
                                            <option value="Chờ duyệt" {{ $cham->TrangThai=='Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                                            <option value="Đã duyệt" {{ $cham->TrangThai=='Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
                                            <option value="Từ chối" {{ $cham->TrangThai=='Từ chối' ? 'selected' : '' }}>Từ chối</option>
                                        </select>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($cham)
                                <a href="{{ route('canbo.diem.show', $maCham) }}" class="btn btn-sm btn-light text-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-end">
        {{ $results->links() }}
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@if($stats['by_project']->count() > 0)
// 1. Pie Chart: Score Distribution
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
            backgroundColor: [
                '#ef4444', // Red 500
                '#f59e0b', // Amber 500
                '#22c55e'  // Green 500
            ],
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
                    padding: 20,
                    font: { size: 11, family: "'Inter', sans-serif" }
                }
            }
        }
    }
});

// 2. Mixed Chart: Avg Score (Line) & Student Count (Bar)
const mixedCtx = document.getElementById('mixedChart').getContext('2d');
const projectNames = {!! json_encode($stats['by_project']->pluck('name')) !!};
const avgScores = {!! json_encode($stats['by_project']->pluck('average')) !!};
const studentCounts = {!! json_encode($stats['by_project']->pluck('student_count')) !!};

// Truncate long names
const truncatedNames = projectNames.map(name => {
    return name.length > 20 ? name.substring(0, 17) + '...' : name;
});

new Chart(mixedCtx, {
    type: 'bar',
    data: {
        labels: truncatedNames,
        datasets: [
            {
                label: 'Điểm trung bình',
                data: avgScores,
                type: 'line',
                borderColor: '#3b82f6', // Blue 500
                backgroundColor: '#3b82f6',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                yAxisID: 'y',
                order: 1
            },
            {
                label: 'Số lượng SV',
                data: studentCounts,
                backgroundColor: 'rgba(34, 197, 94, 0.2)', // Green 500 transparent
                borderColor: '#22c55e',
                borderWidth: 1,
                borderRadius: 4,
                yAxisID: 'y1',
                order: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                align: 'end',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8,
                    font: { size: 12, family: "'Inter', sans-serif" }
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                titleColor: '#1e293b',
                bodyColor: '#475569',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                padding: 10,
                displayColors: true,
                boxPadding: 4,
                callbacks: {
                    title: function(context) {
                        return projectNames[context[0].dataIndex];
                    }
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: { display: true, text: 'Điểm trung bình' },
                min: 0,
                max: 10,
                grid: {
                    color: '#f1f5f9',
                    drawBorder: false
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: { display: true, text: 'Số lượng SV' },
                grid: {
                    drawOnChartArea: false
                },
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});
@endif
</script>

<!-- Export By Class Modal -->
<div class="modal fade" id="exportByClassModal" tabindex="-1" aria-labelledby="exportByClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" id="exportByClassModalLabel">
                    <i class="fas fa-file-export me-2"></i>Xuất điểm theo lớp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('canbo.diem.exportByClass') }}" method="GET">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">
                            <i class="fas fa-users me-2 text-primary"></i>Chọn lớp
                        </label>
                        <select name="lop" class="form-select form-select-lg bg-light" required style="border: 2px solid #e2e8f0; border-radius: 10px;">
                            <option value="">-- Chọn lớp cần xuất --</option>
                            @foreach($allLops as $lop)
                                <option value="{{ $lop->MaLop }}">{{ $lop->TenLop }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            File Excel sẽ chứa điểm của tất cả sinh viên trong lớp được chọn
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 10px;">
                        <i class="fas fa-download me-2"></i>Xuất Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
