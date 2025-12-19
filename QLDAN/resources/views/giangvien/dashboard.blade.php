@extends('layouts.giangvien')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid">
    <!-- INFO CARD -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title text-primary fw-bold mb-3">Thông tin giảng viên</h5>
                    <div class="d-flex align-items-start gap-4">

    <!-- Avatar + chữ bên dưới -->
    <div class="text-center" style="min-width: 120px;">
        @if(!empty($giangvien->HinhAnh))
            <img src="{{ asset($giangvien->HinhAnh) }}" 
                 class="rounded-circle object-fit-cover mb-2"
                 style="width: 100px; height: 100px; border: 2px solid #ddd;">
        @else
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-2"
                 style="width: 100px; height: 100px;">
                <i class="bi bi-person-fill text-secondary" style="font-size: 50px;"></i>
            </div>
        @endif

        <a href="{{ route('giangvien.hoso.index') }}" class="text-decoration-none small d-block mt-1">
            Xem chi tiết
        </a>
    </div>

                        <div class="flex-grow-1">
                            <div class="row g-3">

                                                            <div class="col-md-6">
                                    <div class="small text-muted">Họ tên:</div>
                                    <div class="fw-bold">{{ $giangvien->TenGV }}</div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="small text-muted">Mã giảng viên:</div>
                                    <div class="fw-bold">{{ $giangvien->MaGV }}</div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="small text-muted">Khoa:</div>
                                    <div class="fw-bold">{{ $giangvien->khoa->TenKhoa ?? 'Chưa cập nhật' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="small text-muted">Ngành:</div>
                                    <div class="fw-bold">{{ $giangvien->nganh->TenNganh ?? 'Chưa cập nhật' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="small text-muted">Email:</div>
                                    <div class="fw-bold">{{ $giangvien->Email }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="small text-muted">Số điện thoại:</div>
                                    <div class="fw-bold">{{ $giangvien->SDT }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- NOTIFICATIONS & REMINDERS -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title text-muted mb-0">Nhắc nhở mới</h6>
                        <span class="badge bg-danger rounded-pill">{{ $thongbaos->count() }}</span>
                    </div>
                    
                    @if($thongbaos->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($thongbaos as $tb)
                                <div class="list-group-item px-0 py-2 border-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-bell text-warning"></i>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $tb->NoiDung }}">
                                            {{ \Illuminate\Support\Str::limit($tb->NoiDung, 40) }}
                                        </div>
                                    </div>
                                    <small class="text-muted ps-4" style="font-size: 11px;">
                                        {{ \Carbon\Carbon::parse($tb->TGDang)->diffForHumans() }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('giangvien.thongbao.index') }}" class="small text-decoration-none">Xem chi tiết</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
                            <p class="mt-2 small">Không có thông báo mới</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK STATS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-primary">
                        <i class="bi bi-journal-text fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $soLuongDeTai }}</h3>
                    <div class="text-muted small">Đề tài hướng dẫn</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-success">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $soLuongSinhVien }}</h3>
                    <div class="text-muted small">Sinh viên hướng dẫn</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-info">
                        <i class="bi bi-check2-all fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $daChamDiem }}</h3>
                    <div class="text-muted small">Sinh viên đã chấm</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-2 text-warning">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $deadlines->count() }}</h3>
                    <div class="text-muted small">Deadline sắp tới</div>
                </div>
            </div>
        </div>
    </div>

    <!-- HOẠT ĐỘNG GẦN ĐÂY -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-activity me-2"></i>Hoạt động
                        </h6>
                        <span class="badge bg-primary rounded-pill">{{ $activities->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($activities->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($activities as $activity)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex gap-3 align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="{{ $activity['icon'] }} {{ $activity['color'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="mb-1">{{ $activity['content'] }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($activity['time'])->locale('vi')->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if($activity['type'] === 'report')
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('giangvien.baocao.index') }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>Xem
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 opacity-50"></i>
                            <p class="mt-2 small">Chưa có hoạt động nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- COURSE LIST / TOPICS -->
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">Danh sách đề tài đang hướng dẫn</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Mã đề tài</th>
                                <th>Tên đề tài</th>
                                <th>Năm học</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detai as $dt)
                                <tr>
                                    <td class="ps-3 fw-bold text-secondary">#{{ $dt->MaDeTai }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;" title="{{ $dt->TenDeTai }}">
                                            {{ $dt->TenDeTai }}
                                        </div>
                                    </td>
                                    <td>{{ $dt->NamHoc }}</td>
                                    <td>
                                        @if($dt->TrangThai == 'Hoàn thành' || $dt->TrangThai == 'Đã hoàn thành')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Hoàn thành</span>
                                        @elseif($dt->TrangThai == 'Đang thực hiện')
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Đang thực hiện</span>
                                        @elseif($dt->TrangThai == 'Chờ duyệt')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Chờ duyệt</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">{{ $dt->TrangThai }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Chưa có đề tài nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-center py-3">
                    <a href="{{ route('giangvien.detai.index') }}" class="text-decoration-none fw-bold small">Xem tất cả <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- DEADLINES / SCHEDULE -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-danger">Lịch trình / Deadline</h6>
                </div>
                <div class="card-body p-0">
                    @if($deadlines->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($deadlines as $dl)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex gap-3">
                                        <div class="text-center bg-light rounded p-2" style="min-width: 60px;">
                                            <div class="small text-danger fw-bold">{{ \Carbon\Carbon::parse($dl->DeadlineBaoCao)->format('M') }}</div>
                                            <div class="h4 mb-0 fw-bold">{{ \Carbon\Carbon::parse($dl->DeadlineBaoCao)->format('d') }}</div>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-1">Hạn nộp báo cáo</div>
                                            <div class="small text-muted text-truncate" style="max-width: 200px;">{{ $dl->TenDeTai }}</div>
                                            <div class="small text-danger mt-1">
                                                <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($dl->DeadlineBaoCao)->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                            <p class="mt-2 small">Không có deadline sắp tới</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection