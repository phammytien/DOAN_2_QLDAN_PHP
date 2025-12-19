@extends('layouts.sinhvien')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-3">
        {{-- LEFT COLUMN: STUDENT INFO --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 ps-3">
                    <h5 class="fw-bold text-primary mb-0">Thông tin sinh viên</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <div class="avatar-container mx-auto mb-2" style="width: 100px; height: 100px;">
                                @if(!empty($sinhvien->HinhAnh) && file_exists(public_path($sinhvien->HinhAnh)))
                                    <img src="{{ asset($sinhvien->HinhAnh) }}?v={{ time() }}" 
                                         class="rounded-circle object-fit-cover w-100 h-100" 
                                         style="border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);"
                                         alt="Avatar">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center w-100 h-100"
                                         style="border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <i class="bi bi-person-fill text-secondary" style="font-size: 50px;"></i>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('sinhvien.profile.index') }}" class="d-block mt-2 text-decoration-none small">Xem chi tiết</a>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 100px; display: inline-block;">MSSV:</span> <b>{{ $sinhvien->MaSV }}</b></p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 100px; display: inline-block;">Họ tên:</span> <b>{{ $sinhvien->TenSV }}</b></p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 100px; display: inline-block;">Giới tính:</span> {{ $sinhvien->GioiTinh }}</p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 100px; display: inline-block;">Ngày sinh:</span> {{ \Carbon\Carbon::parse($sinhvien->NgaySinh)->format('d/m/Y') }}</p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 100px; display: inline-block;">Nơi sinh:</span> {{ $sinhvien->NoiSinh }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 110px; display: inline-block;">Lớp học:</span> <b>{{ $sinhvien->lop->TenLop ?? '---' }}</b></p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 110px; display: inline-block;">Khóa học:</span> <b>{{ $sinhvien->namhoc->TenNamHoc ?? '---' }}</b></p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 110px; display: inline-block;">Bậc đào tạo:</span> {{ $sinhvien->BacDaoTao }}</p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 110px; display: inline-block;">Loại hình ĐT:</span> {{ $sinhvien->LoaiHinhDaoTao ?? 'Chính quy' }}</p>
                                    <p class="mb-1"><span class="text-muted fw-medium" style="width: 110px; display: inline-block;">Ngành:</span> <b>{{ $sinhvien->nganh->TenNganh ?? '---' }}</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: NOTIFICATIONS --}}
        <div class="col-lg-4">
            <div class="row g-3 h-100">

                {{-- Nhắc nhở mới --}}
                <div class="col-6">
                    <div class="card border-0 shadow-sm bg-danger bg-opacity-10 h-100">
                        <div class="card-body position-relative">
                            <p class="text-danger mb-1 small">Nhắc nhở mới, chưa xem</p>
                            <h2 class="fw-bold text-danger mb-0">{{ $reminders }}</h2>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalReminders" class="small text-decoration-none text-danger">Xem chi tiết</a>
                            <div class="position-absolute top-50 end-0 translate-middle-y me-3 text-danger opacity-50">
                                <i class="bi bi-bell fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Hạn nộp sắp tới --}}
                <div class="col-6">
                    <div class="card border-0 shadow-sm bg-info bg-opacity-10 h-100">
                        <div class="card-body position-relative">
                            <p class="text-info mb-1 small">Hạn nộp sắp tới</p>
                            <h2 class="fw-bold text-info mb-0">{{ $upcomingDeadlines }}</h2>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalDeadlines" class="small text-decoration-none text-info">Xem chi tiết</a>
                            <div class="position-absolute top-50 end-0 translate-middle-y me-3 text-info opacity-50">
                                <i class="bi bi-calendar-event fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="row g-3 mt-1">
        @php
            $actions = [
                ['icon' => 'bi-pencil-square', 'text' => 'Đăng ký đề tài', 'link' => route('sinhvien.detai.index')],
                ['icon' => 'bi-cloud-upload', 'text' => 'Nộp báo cáo', 'link' => route('sinhvien.baocao.index')],
                ['icon' => 'bi-trophy', 'text' => 'Xem kết quả', 'link' => route('sinhvien.diem.index')],
                ['icon' => 'bi-bell', 'text' => 'Thông báo', 'link' => route('sinhvien.thongbao.index')],
                ['icon' => 'bi-person-circle', 'text' => 'Hồ sơ cá nhân', 'link' => route('sinhvien.profile.index')],
                ['icon' => 'bi-key', 'text' => 'Đổi mật khẩu', 'link' => route('sinhvien.profile.changePasswordView')]
            ];
        @endphp
        @foreach($actions as $action)
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ $action['link'] }}" class="card border-0 shadow-sm text-center text-decoration-none h-100 py-3 action-card">
                <div class="card-body p-2">
                    <i class="bi {{ $action['icon'] }} fs-3 text-primary mb-2 d-block"></i>
                    <span class="small text-muted fw-medium">{{ $action['text'] }}</span>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    {{-- BOTTOM ROW: CHARTS & TABLES --}}
    <div class="row g-3 mt-1">
        {{-- Kết quả học tập (Chart) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-primary mb-0">Kết quả học tập</h6>
                    <select class="form-select form-select-sm w-auto border-0 bg-light">
                        <option>HK1 ({{ $sinhvien->namhoc->TenNamHoc ?? '2025-2026' }})</option>
                    </select>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    @if($diem)
                        <canvas id="gradeChart" style="max-height: 200px;"></canvas>
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-bar-chart fs-1 opacity-25"></i>
                            <p class="small mt-2">Chưa có dữ liệu hiển thị</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Điểm trung bình --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0">
                    <h6 class="fw-bold text-primary mb-0">Điểm trung bình</h6>
                </div>
                <div class="card-body text-center">
                    @php
                        $avgGrade = $diem->DiemCuoi ?? 0;
                        $gradeColor = $avgGrade >= 8 ? 'success' : ($avgGrade >= 6.5 ? 'warning' : 'danger');
                        $gradePercent = ($avgGrade / 10) * 100;
                    @endphp
                    <div class="mb-3">
                        <i class="bi bi-trophy-fill text-{{ $gradeColor }}" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="fw-bold text-{{ $gradeColor }} mb-2">
                        {{ $avgGrade > 0 ? number_format($avgGrade, 2) : '--' }}
                    </h2>
                    <p class="text-muted small mb-3">/ 10 điểm</p>
                    
                    @if($avgGrade > 0)
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $gradeColor }}" role="progressbar" 
                                 style="width: {{ $gradePercent }}%" 
                                 aria-valuenow="{{ $gradePercent }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <p class="mt-2 mb-0 small">
                            <span class="badge bg-{{ $gradeColor }}-subtle text-{{ $gradeColor }}">
                                @if($avgGrade >= 8) Giỏi
                                @elseif($avgGrade >= 6.5) Khá
                                @elseif($avgGrade >= 5) Trung bình
                                @else Yếu
                                @endif
                            </span>
                        </p>
                    @else
                        <p class="text-muted small mb-0">Chưa có điểm</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Lớp học phần (Table) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-primary mb-0">Đề tài đã đăng ký</h6>
                    <span class="badge bg-light text-primary border">{{ $sinhvien->namhoc->TenNamHoc ?? '2025-2026' }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-3 border-0">Tên đề tài</th>
                                    <th class="text-end pe-3 border-0">GVHD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($detai)
                                    <tr>
                                        <td class="ps-3 border-0">
                                            <div class="fw-bold text-primary">{{ $detai->MaDeTai }}</div>
                                            <div class="small text-muted text-truncate" style="max-width: 200px;">{{ $detai->TenDeTai }}</div>
                                        </td>
                                        <td class="text-end pe-3 border-0 fw-bold text-primary">
                                            {{ $detai->giangVien->TenGV ?? 'Chưa cập nhật' }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted small">
                                            Chưa đăng ký học phần nào
                                        </td>
                                    </tr>
                                @endif
                                {{-- Placeholder rows to match design look --}}
                                @if(!$detai)
                                    @for($i=0; $i<3; $i++)
                                    <tr>
                                        <td class="ps-3 border-0">
                                            <div class="fw-bold text-muted opacity-50">---</div>
                                            <div class="small text-muted opacity-50">---</div>
                                        </td>
                                        <td class="text-end pe-3 border-0 fw-bold opacity-50">0</td>
                                    </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grade Chart
        @if($diem)
        const ctxGrade = document.getElementById('gradeChart').getContext('2d');
        new Chart(ctxGrade, {
            type: 'bar',
            data: {
                labels: ['Đồ án'],
                datasets: [{
                    label: 'Điểm',
                    data: [{{ $diem->DiemCuoi ?? $diem->Diem ?? 0 }}],
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    borderRadius: 5,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 10 }
                }
            }
        });
        @endif

        // Progress Chart (Doughnut)
        const ctxProgress = document.getElementById('progressChart').getContext('2d');
        new Chart(ctxProgress, {
            type: 'doughnut',
            data: {
                labels: ['Hoàn thành', 'Chưa hoàn thành'],
                datasets: [{
                    data: [{{ $progressPercent }}, {{ 100 - $progressPercent }}],
                    backgroundColor: ['#20c997', '#e9ecef'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });
    });
</script>

<style>
    .action-card {
        transition: all 0.2s ease;
    }
    .action-card:hover {
        transform: translateY(-3px);
        background-color: #f8f9fa;
    }
    .avatar-container img {
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
</style>

{{-- Modal: Nhắc nhở mới --}}
<div class="modal fade" id="modalReminders" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-bell me-2"></i>Điểm mới chưa xem
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                @if($unreadGrades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Giảng viên</th>
                                    <th class="px-4 py-3">Loại điểm</th>
                                    <th class="px-4 py-3">Điểm</th>
                                    <th class="px-4 py-3">Ngày chấm</th>
                                    <th class="px-4 py-3">Nhận xét</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unreadGrades as $grade)
                                <tr>
                                    <td class="px-4 py-3">{{ $grade->giangVien->TenGV ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge {{ $grade->MaTienDo ? 'bg-info' : 'bg-warning' }}">
                                            {{ $grade->MaTienDo ? 'Tiến độ' : 'Báo cáo cuối' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="fw-bold text-success">{{ number_format($grade->Diem, 2) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <small class="text-muted">
                                            {{ $grade->NgayCham ? \Carbon\Carbon::parse($grade->NgayCham)->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <small>{{ $grade->NhanXet ?? '-' }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">Bạn đã xem hết tất cả điểm mới!</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-0 bg-light">
                <a href="{{ route('sinhvien.diem.index') }}" class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>Xem tất cả điểm
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Hạn nộp sắp tới --}}
<div class="modal fade" id="modalDeadlines" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-calendar-event me-2"></i>Tiến độ sắp đến hạn
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                @if($upcomingTiendos->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingTiendos as $tiendo)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 fw-bold">{{ $tiendo->NoiDung }}</h6>
                                    <p class="mb-2 text-muted small">{{ $tiendo->MoTa ?? 'Không có mô tả' }}</p>
                                    <div class="d-flex gap-3">
                                        <small class="text-danger">
                                            <i class="bi bi-clock me-1"></i>
                                            Hạn: {{ \Carbon\Carbon::parse($tiendo->HanNop)->format('d/m/Y H:i') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-hourglass me-1"></i>
                                            Còn {{ \Carbon\Carbon::parse($tiendo->HanNop)->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ route('sinhvien.tiendo.show', $tiendo->MaTienDo) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">Không có tiến độ nào sắp đến hạn!</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-0 bg-light">
                <a href="{{ route('sinhvien.tiendo.index') }}" class="btn btn-primary">
                    <i class="bi bi-list-check me-2"></i>Xem tất cả tiến độ
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@endsection