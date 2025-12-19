@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

{{-- DEBUG: Kiểm tra giá trị pending --}}
<script>
console.log('🔍 DEBUG - Pending Projects:', {{ $data['pendingProjects'] ?? 0 }});
console.log('🔍 DEBUG - Pending Scores:', {{ $data['pendingScores'] ?? 0 }});
console.log('🔍 DEBUG - Should show modal:', {{ ($data['pendingProjects'] ?? 0) > 0 || ($data['pendingScores'] ?? 0) > 0 ? 'true' : 'false' }});
</script>

{{-- MODAL THÔNG BÁO CHỜ DUYỆT - HIỆN MỖI KHI LOAD TRANG --}}
@if(($data['pendingProjects'] ?? 0) > 0 || ($data['pendingScores'] ?? 0) > 0)
<div class="modal fade" id="pendingNotificationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="notification-icon" style="width: 100px; height: 100px; margin: 0 auto; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: bounce 2s infinite;">
                        <i class="fas fa-bell" style="font-size: 50px; color: #f59e0b;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" style="color: #1f2937;">⚠️ Có công việc cần xử lý!</h4>
                
                @if($data['pendingProjects'] > 0)
                    <div class="alert alert-warning border-0 mb-3" style="background: #fef3c7; color: #92400e; border-radius: 12px;">
                        <i class="fas fa-folder-open me-2"></i>
                        <strong>{{ $data['pendingProjects'] }}</strong> đề tài mới chờ duyệt
                    </div>
                @endif
                
                @if($data['pendingScores'] > 0)
                    <div class="alert alert-info border-0 mb-3" style="background: #dbeafe; color: #1e40af; border-radius: 12px;">
                        <i class="fas fa-star me-2"></i>
                        <strong>{{ $data['pendingScores'] }}</strong> điểm chờ duyệt
                    </div>
                @endif
                
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" class="btn btn-light px-4 py-2 fw-semibold" data-bs-dismiss="modal" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                    
                    @if($data['pendingProjects'] > 0)
                        <a href="{{ route('admin.detai.index') }}" class="btn btn-warning px-4 py-2 fw-semibold" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);">
                            <i class="fas fa-folder-open me-2"></i>Duyệt đề tài
                        </a>
                    @endif
                    
                    @if($data['pendingScores'] > 0)
                        <a href="{{ route('admin.baocao.index') }}" class="btn btn-primary px-4 py-2 fw-semibold" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);">
                            <i class="fas fa-star me-2"></i>Duyệt điểm
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔔 Admin Dashboard Loaded');
    
    var modalElement = document.getElementById('pendingNotificationModal');
    console.log('📋 Modal Element:', modalElement);
    
    if (modalElement) {
        console.log('✅ Modal found! Showing...');
        // Hiển thị modal TỰ ĐỘNG mỗi khi load trang (không cần kiểm tra session)
        var pendingModal = new bootstrap.Modal(modalElement);
        pendingModal.show();
        
        // Tự động đóng sau 10 giây nếu không có tương tác
        setTimeout(function() {
            console.log('⏰ Auto-closing modal after 10 seconds');
            pendingModal.hide();
        }, 10000);
    } else {
        console.log('❌ Modal element NOT found!');
    }
});
</script>
@endif

<div class="container-fluid p-0" style="background-color: #f8f9fa;">
    {{-- FILTER SECTION --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Năm học</label>
                    <select name="namhoc" class="form-select border-0 bg-light" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Tất cả năm học</option>
                        @foreach($namhocs as $nh)
                            <option value="{{ $nh->MaNamHoc }}" {{ isset($filterNamHoc) && $filterNamHoc == $nh->MaNamHoc ? 'selected' : '' }}>
                                {{ $nh->TenNamHoc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Khoa</label>
                    <select name="khoa" class="form-select border-0 bg-light" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Tất cả khoa</option>
                        @foreach($khoas as $k)
                            <option value="{{ $k->MaKhoa }}" {{ isset($filterKhoa) && $filterKhoa == $k->MaKhoa ? 'selected' : '' }}>
                                {{ $k->TenKhoa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Giảng viên</label>
                    <select name="giangvien" class="form-select border-0 bg-light" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Tất cả giảng viên</option>
                        @foreach($gvs as $gv)
                            <option value="{{ $gv->MaGV }}" {{ isset($filterGV) && $filterGV == $gv->MaGV ? 'selected' : '' }}>
                                {{ $gv->TenGV }}
                            </option>
                        @endforeach
                    </select>
                </div> -->
                <div class="col-md-3">
                    @if(isset($filterNamHoc) || isset($filterKhoa) || isset($filterGV))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary w-100 fw-bold">
                            <i class="fas fa-redo me-2"></i>Xóa lọc
                        </a>
                    @else
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fas fa-filter me-2"></i>Lọc dữ liệu
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="row mb-4">
        <div class="col-12">
            <h6 class="fw-bold text-dark mb-3">
                <i class="fas fa-bolt text-warning me-2"></i>Thao tác nhanh
            </h6>
        </div>
        
        {{-- Tạo thông báo --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 action-card" style="border-radius: 15px; cursor: pointer; transition: all 0.3s ease;" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                <div class="card-body p-4 text-center">
                    <div class="icon-wrapper mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #e3f2fd; border-radius: 15px;">
                        <i class="fas fa-bullhorn text-primary" style="font-size: 24px;"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Tạo thông báo</h6>
                </div>
            </div>
        </div>

        {{-- Thêm giảng viên --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 action-card" style="border-radius: 15px; cursor: pointer; transition: all 0.3s ease;" data-bs-toggle="modal" data-bs-target="#addLecturerModal">
                <div class="card-body p-4 text-center">
                    <div class="icon-wrapper mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #e8f5e9; border-radius: 15px;">
                        <i class="fas fa-chalkboard-teacher text-success" style="font-size: 24px;"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Thêm giảng viên</h6>
                </div>
            </div>
        </div>

        {{-- Thêm sinh viên --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 action-card" style="border-radius: 15px; cursor: pointer; transition: all 0.3s ease;" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <div class="card-body p-4 text-center">
                    <div class="icon-wrapper mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #fff3e0; border-radius: 15px;">
                        <i class="fas fa-user-graduate text-warning" style="font-size: 24px;"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Thêm sinh viên</h6>
                </div>
            </div>
        </div>

        {{-- Thêm đề tài --}}
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 action-card" style="border-radius: 15px; cursor: pointer; transition: all 0.3s ease;" data-bs-toggle="modal" data-bs-target="#addTopicModal">
                <div class="card-body p-4 text-center">
                    <div class="icon-wrapper mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #f3e5f5; border-radius: 15px;">
                        <i class="fas fa-book text-purple" style="font-size: 24px; color: #9c27b0;"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Thêm đề tài</h6>
                </div>
            </div>
        </div>
    </div>

    <style>
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
    </style>

    {{-- REAL-TIME STATS WIDGETS & DONUT CHARTS --}}
    <div class="row g-4 mb-4">
        {{-- Real-time Stats Widgets --}}
        <div class="col-lg-8">
            <div class="row g-3">
                {{-- Widget 1: Báo cáo hôm nay --}}
                <div class="col-md-6">
                    <div class="stat-widget bg-gradient-blue-1">
                        <div class="widget-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="widget-content">
                            <div class="widget-label">Báo cáo hôm nay</div>
                            <div class="widget-value" id="reportsToday">{{ $data['reportsToday'] }}</div>
                            <div class="widget-trend">
                                <i class="fas fa-arrow-up"></i>
                                <span>Đã nộp trong ngày</span>
                            </div>
                        </div>
                        <div class="widget-pulse"></div>
                    </div>
                </div>

                {{-- Widget 2: Chưa chấm --}}
                <div class="col-md-6">
                    <div class="stat-widget bg-gradient-blue-2">
                        <div class="widget-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="widget-content">
                            <div class="widget-label">Chưa chấm</div>
                            <div class="widget-value" id="reportsNotGraded">{{ $data['reportsNotGraded'] }}</div>
                            <div class="widget-trend">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Cần duyệt gấp</span>
                            </div>
                        </div>
                        <div class="widget-pulse warning"></div>
                    </div>
                </div>

                {{-- Widget 3: Chưa có đề tài --}}
                <div class="col-md-6">
                    <div class="stat-widget bg-gradient-blue-3">
                        <div class="widget-icon">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div class="widget-content">
                            <div class="widget-label">Chưa có đề tài</div>
                            <div class="widget-value" id="studentsNoTopic">{{ $data['studentsNoTopic'] }}</div>
                            <div class="widget-trend">
                                <i class="fas fa-user-friends"></i>
                                <span>Sinh viên chưa ĐK</span>
                            </div>
                        </div>
                        <div class="widget-pulse"></div>
                    </div>
                </div>

                {{-- Widget 4: Tiến độ TB --}}
                <div class="col-md-6">
                    <div class="stat-widget bg-gradient-blue-4">
                        <div class="widget-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="widget-content">
                            <div class="widget-label">Tiến độ TB</div>
                            <div class="widget-value" id="avgProgress">{{ $data['avgProgress'] }}%</div>
                            <div class="widget-trend">
                                <i class="fas fa-globe-asia"></i>
                                <span>Toàn hệ thống</span>
                            </div>
                        </div>
                        <div class="widget-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ADMIN INFO CARD --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('img/uploads/images/default-avatar.png') }}" 
                                 class="rounded-circle img-thumbnail object-fit-cover shadow-sm" 
                                 alt="Avatar" style="width: 100px; height: 100px; border: 3px solid white;">
                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-light rounded-circle"></span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">NGUYỄN VĂN ANH</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Quản trị viên</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3">
                        <span class="text-muted small">Email</span>
                        <span class="fw-bold text-dark small">vananh@dthu.edu.vn</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                        <span class="text-muted small">Đăng nhập</span>
                        <span class="fw-bold text-dark small" id="currentTime">{{ now()->format('d/m H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIVITY TIMELINE & DONUT CHART --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="fas fa-history text-primary me-2"></i>Hoạt động gần đây
                        </h6>
                        <span class="badge bg-primary-subtle text-primary">Realtime</span>
                    </div>
                    
                    <div class="timeline">
                        @forelse($data['latestNotifications'] as $tb)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-semibold mb-1">{{ Str::limit($tb->NoiDung, 50) }}</h6>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-user-edit me-1"></i>{{ $tb->canBo->TenCB ?? 'Admin' }}
                                            @if($tb->MucDo == 'Khan')
                                                <span class="badge bg-danger ms-2" style="font-size: 0.7em;">Khẩn cấp</span>
                                            @elseif($tb->MucDo == 'QuanTrong')
                                                <span class="badge bg-warning ms-2" style="font-size: 0.7em;">Quan trọng</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ \Carbon\Carbon::parse($tb->TGDang)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">
                            <small>Chưa có thông báo nào</small>
                        </div>
                        @endforelse
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('admin.thongbao.index') }}" class="text-primary text-decoration-none fw-semibold small">
                            Xem tất cả thông báo <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>


        {{-- Report Stats by Year - Line Chart --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="fas fa-chart-line text-primary me-2"></i>Sinh viên làm đồ án theo năm
                    </h6>
                    
                    @if(!empty($data['reportsByYear']) && count($data['reportsByYear']) > 0)
                        <div style="height: 250px;">
                            <canvas id="yearLineChart"></canvas>
                        </div>
                        
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('yearLineChart').getContext('2d');
                            
                            const yearData = @json($data['reportsByYear']);
                            const labels = yearData.map(item => item.TenNamHoc || 'N/A');
                            const data = yearData.map(item => item.SoLuong);
                            
                            // Create gradient
                            const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
                            
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Số sinh viên',
                                        data: data,
                                        borderColor: '#3b82f6',
                                        backgroundColor: gradient,
                                        borderWidth: 4,
                                        fill: true,
                                        tension: 0.4,
                                        pointRadius: 8,
                                        pointBackgroundColor: '#3b82f6',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 3,
                                        pointHoverRadius: 10,
                                        pointHoverBackgroundColor: '#2563eb',
                                        pointHoverBorderWidth: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                                            padding: 15,
                                            titleFont: {
                                                size: 15,
                                                weight: 'bold'
                                            },
                                            bodyFont: {
                                                size: 14
                                            },
                                            cornerRadius: 8,
                                            displayColors: false,
                                            callbacks: {
                                                label: function(context) {
                                                    return '👨‍🎓 ' + context.parsed.y + ' sinh viên';
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1,
                                                font: {
                                                    size: 12,
                                                    weight: '500'
                                                },
                                                color: '#64748b'
                                            },
                                            grid: {
                                                color: 'rgba(148, 163, 184, 0.15)',
                                                lineWidth: 1
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                font: {
                                                    size: 12,
                                                    weight: '600'
                                                },
                                                color: '#475569'
                                            },
                                            grid: {
                                                display: true,
                                                color: 'rgba(148, 163, 184, 0.08)'
                                            }
                                        }
                                    }
                                }
                            });
                        });
                        </script>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-chart-line fa-2x mb-2 opacity-50"></i>
                            <p class="small mb-0">Chưa có dữ liệu</p>
                        </div>
                    @endif
                    
                    <div class="text-center mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Số sinh viên đã nộp đồ án
                        </small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- STATS CARDS (3 columns) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: white;">
                <div class="card-body p-4 text-center">
                    <div class="icon-box mx-auto mb-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-graduate fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $data['tongSV'] }}</h3>
                    <p class="text-muted small mb-0">Tổng sinh viên</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: white;">
                <div class="card-body p-4 text-center">
                    <div class="icon-box mx-auto mb-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-chalkboard-teacher fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $data['tongGV'] }}</h3>
                    <p class="text-muted small mb-0">Tổng giảng viên</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: white;">
                <div class="card-body p-4 text-center">
                    <div class="icon-box mx-auto mb-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-book fs-4"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $data['tongDT'] }}</h3>
                    <p class="text-muted small mb-0">Tổng đề tài</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: white;">
                <div class="card-body p-4">
                    <div class="icon-box mx-auto mb-3 bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-double fs-4"></i>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-dark mb-1">{{ $data['registeredProjects'] }}/{{ $data['approvedProjects'] }}</h3>
                        <p class="text-muted small mb-2">Đề tài đã đăng ký</p>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $data['registrationRate'] }}%"></div>
                        </div>
                        <small class="text-success fw-bold mt-1 d-block">{{ $data['registrationRate'] }}%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.baocao.index') }}" class="card border-0 shadow-sm text-center text-decoration-none h-100 action-card" style="border-radius: 12px; background: white;">
                <div class="card-body p-3">
                    <div class="icon-wrapper mb-2">
                        <i class="fas fa-file-alt fs-4 text-primary"></i>
                    </div>
                    <span class="text-dark fw-bold small d-block">Quản lý báo cáo</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.taikhoan.index') }}" class="card border-0 shadow-sm text-center text-decoration-none h-100 action-card" style="border-radius: 12px; background: white;">
                <div class="card-body p-3">
                    <div class="icon-wrapper mb-2">
                        <i class="fas fa-users-cog fs-4 text-primary"></i>
                    </div>
                    <span class="text-dark fw-bold small d-block">Quản lý tài khoản</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.cauhinh.index') }}" class="card border-0 shadow-sm text-center text-decoration-none h-100 action-card" style="border-radius: 12px; background: white;">
                <div class="card-body p-3">
                    <div class="icon-wrapper mb-2">
                        <i class="fas fa-cog fs-4 text-primary"></i>
                    </div>
                    <span class="text-dark fw-bold small d-block">Cấu hình hệ thống</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.baocao.thongke') }}" class="card border-0 shadow-sm text-center text-decoration-none h-100 action-card" style="border-radius: 12px; background: white;">
                <div class="card-body p-3">
                    <div class="icon-wrapper mb-2">
                        <i class="fas fa-chart-bar fs-4 text-primary"></i>
                    </div>
                    <span class="text-dark fw-bold small d-block">Báo cáo thống kê</span>
                </div>
            </a>
        </div>
    </div>

    {{-- CHARTS SECTION --}}
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
                <div class="card-header bg-white border-bottom-0 pt-4 ps-4 pe-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="fas fa-building me-2"></i>Đề tài theo khoa
                    </h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="barChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
                <div class="card-header bg-white border-bottom-0 pt-4 ps-4 pe-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Kết quả bảo vệ
                    </h6>
                </div>
                <div class="card-body text-center position-relative p-4">
                    <div class="position-relative d-inline-block">
                        <canvas id="chartKetQua" width="220" height="220"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h3 class="fw-bold mb-0 text-dark">{{ $data['ketqua']->Dat + $data['ketqua']->KhongDat + $data['ketqua']->Cho }}</h3>
                            <span class="text-muted small">Tổng SV</span>
                        </div>
                    </div>
                    <div class="mt-4 d-flex flex-wrap justify-content-center gap-2">
                        <span class="badge bg-success rounded-pill px-3 py-2">Đậu: {{ $data['ketqua']->Dat }}</span>
                        <span class="badge bg-danger rounded-pill px-3 py-2">Rớt: {{ $data['ketqua']->KhongDat }}</span>
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Chờ: {{ $data['ketqua']->Cho }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
                <div class="card-header bg-white border-bottom-0 pt-4 ps-4 pe-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="fas fa-users me-2"></i>Thành phần hệ thống
                    </h6>
                </div>
                <div class="card-body p-4 d-flex align-items-center justify-content-center">
                     <canvas id="pieChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW CHARTS ROW: LINE & COLUMN CHARTS --}}
    <div class="row g-4 mt-2">
        {{-- LINE CHART: Báo cáo nộp theo ngày --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
                <div class="card-header bg-white border-bottom-0 pt-4 ps-4 pe-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="fas fa-chart-line me-2"></i>Báo cáo nộp 7 ngày gần nhất
                    </h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="lineChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        {{-- COLUMN CHART: Đề tài theo trạng thái --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: white;">
                <div class="card-header bg-white border-bottom-0 pt-4 ps-4 pe-4 pb-0">
                    <h6 class="fw-bold text-primary mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Đề tài theo trạng thái
                    </h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="columnChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
@include('admin.partials.modals')

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bar Chart: Đề tài theo khoa
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($data['tenKhoa']) !!},
                datasets: [{
                    label: 'Số lượng',
                    data: {!! json_encode($data['soLuongDT']) !!},
                    backgroundColor: '#4361ee',
                    borderRadius: 8,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { 
                            borderDash: [5, 5],
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // Doughnut Chart: Kết quả bảo vệ
        new Chart(document.getElementById('chartKetQua'), {
            type: 'doughnut',
            data: {
                labels: ['Đậu', 'Rớt', 'Chờ'],
                datasets: [{
                    data: [{{ $data['ketqua']->Dat }}, {{ $data['ketqua']->KhongDat }}, {{ $data['ketqua']->Cho }}],
                    backgroundColor: ['#4cc9f0', '#f72585', '#f8961e'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                }
            }
        });

        // Pie Chart: Thành phần hệ thống
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Sinh viên', 'Giảng viên', 'Cán bộ'],
                datasets: [{
                    data: [{{ $data['tongSV'] }}, {{ $data['tongGV'] }}, {{ $data['tongCB'] }}],
                    backgroundColor: ['#4361ee', '#4cc9f0', '#f72585'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            usePointStyle: true, 
                            padding: 15,
                            font: { size: 12 }
                        } 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                }
            }
        });

        // NEW: Donut Chart for System Distribution
        new Chart(document.getElementById('systemDonutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Sinh viên', 'Giảng viên', 'Đề tài'],
                datasets: [{
                    data: [{{ $data['tongSV'] }}, {{ $data['tongGV'] }}, {{ $data['tongDT'] }}],
                    backgroundColor: ['#1e40af', '#3b82f6', '#60a5fa'],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed;
                            }
                        }
                    }
                }
            }
        });

        // NEW: Line Chart - Báo cáo nộp 7 ngày gần nhất
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($data['lineChartLabels']) !!},
                datasets: [{
                    label: 'Số báo cáo',
                    data: {!! json_encode($data['lineChartData']) !!},
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#4361ee',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { 
                            borderDash: [5, 5],
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: { size: 11 },
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // NEW: Column Chart - Đề tài theo trạng thái
        new Chart(document.getElementById('columnChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($data['columnChartLabels']) !!},
                datasets: [{
                    label: 'Số lượng',
                    data: {!! json_encode($data['columnChartData']) !!},
                    backgroundColor: ['#4cc9f0', '#4361ee', '#f8961e', '#f72585'],
                    borderRadius: 8,
                    barThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { 
                            borderDash: [5, 5],
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: { size: 11 },
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // Real-time updates simulation
        function animateValue(id, end, duration = 1000) {
            const obj = document.getElementById(id);
            if (!obj) return;
            
            const start = 0;
            const range = end - start;
            const increment = Math.ceil(range / 50);
            const stepTime = Math.abs(Math.floor(duration / (range / increment)));
            let current = start;
            
            const timer = setInterval(function() {
                current += increment;
                if (current >= end) {
                    current = end;
                    clearInterval(timer);
                }
                obj.textContent = current + (id === 'avgProgress' ? '%' : '');
            }, stepTime);
        }

        // Animate widget values on page load
        animateValue('reportsToday', {{ $data['reportsToday'] }});
        animateValue('reportsNotGraded', {{ $data['reportsNotGraded'] }});
        animateValue('studentsNoTopic', {{ $data['studentsNoTopic'] }});
        animateValue('avgProgress', {{ $data['avgProgress'] }});

        // Update current time every minute
        setInterval(function() {
            const now = new Date();
            const timeStr = now.getDate().toString().padStart(2, '0') + '/' + 
                           (now.getMonth() + 1).toString().padStart(2, '0') + ' ' +
                           now.getHours().toString().padStart(2, '0') + ':' + 
                           now.getMinutes().toString().padStart(2, '0');
            const timeEl = document.getElementById('currentTime');
            if (timeEl) timeEl.textContent = timeStr;
        }, 60000);
    });
</script>

<style>
    /* Quick Action Cards */
    .action-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(67, 97, 238, 0.1);
    }
    
    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(67, 97, 238, 0.15) !important;
        border-color: rgba(67, 97, 238, 0.3);
    }
    
    .icon-wrapper {
        width: 50px;
        height: 50px;
        background: #eff6ff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: all 0.3s ease;
    }
    
    .icon-wrapper i {
        transition: all 0.3s ease;
    }
    
    .action-card:hover .icon-wrapper {
        background: #dbeafe;
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
        transform: scale(1.05);
    }
    
    .action-card:hover .icon-wrapper i {
        transform: scale(1.15);
    }
    
    /* Real-time Stat Widgets - Blue Theme */
    .stat-widget {
        position: relative;
        border-radius: 16px;
        padding: 24px;
        color: white;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .stat-widget:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(67, 97, 238, 0.2);
    }
    
    .bg-gradient-blue-1 {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }
    
    .bg-gradient-blue-2 {
        background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%);
    }
    
    .bg-gradient-blue-3 {
        background: linear-gradient(135deg, #3b82f6 0%, #93c5fd 100%);
    }
    
    .bg-gradient-blue-4 {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    }
    
    .widget-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 48px;
        opacity: 0.15;
    }
    
    .widget-content {
        position: relative;
        z-index: 2;
    }
    
    .widget-label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 8px;
        opacity: 0.9;
    }
    
    .widget-value {
        font-size: 36px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 12px;
    }
    
    .widget-trend {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        opacity: 0.85;
    }
    
    .widget-pulse {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 12px;
        height: 12px;
        background: white;
        border-radius: 50%;
        animation: pulseGlow 2s ease-in-out infinite;
    }
    
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.7); }
        50% { box-shadow: 0 0 0 8px rgba(255,255,255,0); }
    }
    
    /* Timeline Styles - Blue Theme */
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, #3b82f6 0%, #60a5fa 100%);
        opacity: 0.3;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 24px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -36px;
        top: 4px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    
    .timeline-content:hover {
        background: #eff6ff;
        transform: translateX(5px);
    }
    
    .timeline-content h6 {
        font-size: 14px;
    }
    
    /* Info Cards - Icon scale and rotate */
    .card .icon-box {
        transition: all 0.4s ease;
    }
    
    .card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }
    
    .card .icon-box i {
        display: inline-block;
    }
    
    /* Cards General */
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.12) !important;
    }
    
    /* Badge hover */
    .badge {
        transition: all 0.2s ease;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
    
    /* Form elements */
    .form-select, .form-control {
        transition: all 0.2s ease;
    }
    
    .form-select:focus, .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        border-color: #4361ee;
        transform: translateY(-1px);
    }
    
    /* Button with ripple effect */
    .btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }
    
    .btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }
    
    .btn:active {
        transform: translateY(0);
    }
    
    /* Avatar zoom */
    .rounded-circle.img-thumbnail {
        transition: transform 0.3s ease;
    }
    
    .rounded-circle.img-thumbnail:hover {
        transform: scale(1.05);
    }
    
    /* Chart card icon rotate */
    .card-header h6 i {
        display: inline-block;
        transition: transform 0.5s ease;
    }
    
    .card:hover .card-header h6 i {
        transform: rotate(360deg);
    }
    
    /* Smooth page load animation */
    .card {
        animation: fadeInUp 0.5s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection