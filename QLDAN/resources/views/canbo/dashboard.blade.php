@extends('layouts.canbo')

@section('title', 'Trang chủ')

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
    .activity-list {
        background-color: var(--card-bg);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .activity-item {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        align-items: flex-start;
        position: relative;
    }
    .activity-item:last-child {
        margin-bottom: 0;
    }
    .activity-item::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 40px;
        bottom: -24px;
        width: 2px;
        background-color: #f1f5f9;
        z-index: 0;
    }
    .activity-item:last-child::before {
        display: none;
    }
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        z-index: 1;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .activity-content p {
        margin: 0 0 4px 0;
        font-size: 0.925rem;
        line-height: 1.5;
        color: var(--text-main);
        font-weight: 500;
    }
    .activity-content small {
        color: var(--text-light);
        font-size: 0.75rem;
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
    .status-tre-han { background: #fee2e2; color: #991b1b; }
    .status-dang-lam { background: #dbeafe; color: #1e40af; }
    .status-hoan-thanh { background: #dcfce7; color: #166534; }
    
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
</style>

<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary-dark);">Tổng quan</h4>
            <p class="text-muted mb-0">Chào mừng trở lại, <span class="fw-semibold text-primary">{{ Auth::user()->canBoQL->TenCB ?? 'Cán bộ' }}</span></p>
        </div>
        <!-- <div class="d-flex gap-3">
            <div class="input-group shadow-sm" style="width: 300px; border-radius: 10px; overflow: hidden;">
                <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-0 ps-2" placeholder="Tìm kiếm nhanh...">
            </div>
            <button class="btn btn-white bg-white border-0 shadow-sm rounded-3 position-relative">
                <i class="fas fa-bell text-muted"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            </button>
        </div> -->
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Tổng số đề tài</h6>
                <h2 class="text-primary">{{ $data['tongDeTai'] }}</h2>
                <i class="fas fa-folder-open stat-icon text-primary"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Đã hoàn thành</h6>
                <h2 class="text-success">{{ $data['daHoanThanh'] }}</h2>
                <i class="fas fa-check-circle stat-icon text-success"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Chờ duyệt</h6>
                <h2 class="text-warning">{{ $data['choDuyet'] }}</h2>
                <i class="fas fa-clock stat-icon text-warning"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card position-relative overflow-hidden">
                <h6>Sinh viên chưa nộp</h6>
                <h2 class="text-danger">{{ $data['sinhVienChuaNop'] }}</h2>
                <i class="fas fa-exclamation-circle stat-icon text-danger"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-5">
        <!-- Mixed Chart: Projects by Major -->
        <div class="col-md-8">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-chart-bar text-primary"></i>
                        Thống kê Đề tài theo Ngành
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Năm học hiện tại
                        </button>
                    </div>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="mixedChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Pie Chart: Project Status -->
        <div class="col-md-4">
            <div class="chart-container">
                <h5 class="section-title">
                    <i class="fas fa-chart-pie text-info"></i>
                    Trạng thái Đề tài
                </h5>
                <div style="height: 300px; position: relative;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Table & Activity -->
    <div class="row g-4">
        <!-- Registered Students Table -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-user-graduate text-success"></i>
                        Sinh viên đăng ký gần đây
                    </h5>
                    <a href="{{ route('canbo.sinhvien.index') }}" class="btn btn-sm btn-light text-primary fw-semibold">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Sinh viên</th>
                                <th>Lớp</th>
                                <th>Đề tài</th>
                                <th class="text-end pe-4">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['registeredStudents'] as $sv)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle bg-light text-primary d-flex align-items-center justify-content-center fw-bold me-3" style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ substr($sv->TenSV, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $sv->TenSV }}</div>
                                            <small class="text-muted">{{ $sv->MaSV }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $sv->TenLop }}</span></td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $sv->TenDeTai }}">
                                        {{ $sv->TenDeTai }}
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    @php
                                        $statusClass = match($sv->TrangThai) {
                                            'Hoàn thành' => 'status-hoan-thanh',
                                            'Chờ duyệt' => 'status-cho-duyet',
                                            'Trễ hạn' => 'status-tre-han',
                                            default => 'status-dang-lam'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $sv->TrangThai }}</span>
                                </td>
                            </tr>
                            @endforeach
                            @if($data['registeredStudents']->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3 opacity-50"></i>
                                        <p class="mb-0">Chưa có dữ liệu đăng ký.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity List -->
        <div class="col-md-4">
            <div class="activity-list">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0">
                        <i class="fas fa-history text-warning"></i>
                        Hoạt động
                    </h5>
                    <button class="btn btn-sm btn-light"><i class="fas fa-ellipsis-h"></i></button>
                </div>
                
                <div class="overflow-auto" style="max-height: 400px;">
                    @foreach($data['activities'] as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="{{ $activity['icon'] }} {{ $activity['color'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <p>{!! $activity['content'] !!}</p>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($activity['time'])->locale('vi')->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    @endforeach

                    @if(count($data['activities']) == 0)
                        <div class="text-center py-5">
                            <p class="text-muted">Chưa có hoạt động nào.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Status Chart (Doughnut)
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    const chartData = @json($data['chartData']);
    
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Mới', 'Đang làm', 'Chờ duyệt', 'Hoàn thành', 'Trễ hạn'],
            datasets: [{
                data: [
                    chartData.Moi, 
                    chartData.DangLam, 
                    chartData.ChoDuyet, 
                    chartData.HoanThanh, 
                    chartData.TreHan
                ],
                backgroundColor: [
                    '#60a5fa', // Mới - Blue 400
                    '#3b82f6', // Đang làm - Blue 500
                    '#facc15', // Chờ duyệt - Yellow 400
                    '#22c55e', // Hoàn thành - Green 500
                    '#ef4444'  // Trễ hạn - Red 500
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
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

    // 2. Mixed Chart (Bar + Line) - Projects by Major
    const ctxMixed = document.getElementById('mixedChart').getContext('2d');
    const mixedData = @json($data['mixedChartData']);
    
    const labels = mixedData.map(item => item.TenNganh);
    const totalData = mixedData.map(item => item.Total);
    const completedData = mixedData.map(item => item.Completed);

    new Chart(ctxMixed, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Tổng đề tài',
                    data: totalData,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)', // Blue 500 transparent
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 4,
                    order: 2
                },
                {
                    label: 'Đã hoàn thành',
                    data: completedData,
                    type: 'line',
                    borderColor: '#22c55e', // Green 500
                    backgroundColor: '#22c55e',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#22c55e',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4, // Smooth line
                    order: 1
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
                    boxPadding: 4
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false
                    },
                    ticks: {
                        font: { size: 11 },
                        color: '#64748b'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11 },
                        color: '#64748b'
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
</script>
@endsection
