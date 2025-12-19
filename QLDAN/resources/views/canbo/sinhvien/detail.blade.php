@extends('layouts.canbo')

@section('title', 'Chi tiết Sinh viên')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fas fa-graduation-cap text-primary me-2"></i>Chi tiết Sinh viên
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('canbo.sinhvien.index') }}" class="text-decoration-none text-muted">Sinh viên</a></li>
                    <li class="breadcrumb-item active text-dark" aria-current="page">{{ $sinhvien->TenSV }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('canbo.sinhvien.index') }}" class="btn btn-dark btn-sm shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row g-4">
        <!-- Cột trái: Thông tin cá nhân -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div class="card-header bg-light-green border-0 py-3">
                    <h6 class="mb-0 fw-bold text-success"><i class="far fa-id-card me-2"></i>Thông tin cá nhân</h6>
                </div>
                <div class="card-body text-center pt-4 pb-4">
                    <div class="avatar-container mb-3 mx-auto">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $sinhvien->TenSV }}</h5>
                    <p class="text-muted small mb-4">{{ $sinhvien->MaSV }}</p>
                    
                    <div class="text-start px-3">
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-venus-mars text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Giới tính</small>
                                <span class="fw-medium">{{ $sinhvien->GioiTinh }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="far fa-calendar-alt text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Ngày sinh</small>
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($sinhvien->NgaySinh)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="far fa-envelope text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Email</small>
                                <span class="fw-medium">{{ $sinhvien->Email }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-phone-alt text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Số điện thoại</small>
                                <span class="fw-medium">{{ $sinhvien->SDT }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="far fa-id-card text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">CCCD</small>
                                <span class="fw-medium">{{ $sinhvien->MaCCCD }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-university text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Khoa</small>
                                <span class="fw-medium">{{ $sinhvien->khoa->TenKhoa ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-laptop-code text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Ngành</small>
                                <span class="fw-medium">{{ $sinhvien->nganh->TenNganh ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="info-row border-0">
                            <div class="icon-col"><i class="fas fa-users text-primary"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Lớp</small>
                                <span class="fw-medium">{{ $sinhvien->lop->TenLop ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Thông tin học tập -->
        <div class="col-lg-8">
            <!-- Đề tài đăng ký -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-light-blue border-0 py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-project-diagram me-2"></i>Đề tài đăng ký</h6>
                </div>
                <div class="card-body p-4">
                    @if($deTai)
                        <h5 class="fw-bold text-primary mb-2">{{ $deTai->TenDeTai }}</h5>
                        <p class="text-muted mb-4">{{ $deTai->MoTa }}</p>
                        
                        <div class="row g-4">
                            <div class="col-md-4">
                                <small class="text-muted d-block mb-1">Giảng viên hướng dẫn</small>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-tie text-primary me-2"></i>
                                    <span class="fw-medium">{{ $deTai->giangVien->TenGV ?? 'Chưa phân công' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block mb-1">Trạng thái</small>
                                <span class="badge bg-light-success text-success border border-success px-3 py-2 rounded-pill">
                                    {{ $deTai->TrangThai }}
                                </span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block mb-1">Năm học</small>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-calendar-check text-primary me-2"></i>
                                    <span class="fw-medium">{{ $deTai->namHoc->TenNamHoc ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No Data" width="60" class="mb-3 opacity-50">
                            <p class="text-muted">Sinh viên chưa đăng ký đề tài nào.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Điểm số -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-light-orange border-0 py-3">
                    <h6 class="mb-0 fw-bold text-warning"><i class="far fa-star me-2"></i>Điểm số</h6>
                </div>
                <div class="card-body p-0">
                    @if($deTai && $diems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted text-uppercase small fw-bold">Giảng viên chấm</th>
                                        <th class="py-3 text-muted text-uppercase small fw-bold">Loại điểm</th>
                                        <th class="py-3 text-muted text-uppercase small fw-bold text-center">Điểm</th>
                                        <th class="pe-4 py-3 text-muted text-uppercase small fw-bold">Nhận xét</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diems as $diem)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $diem->giangvien->TenGV ?? 'N/A' }}</td>
                                        <td>
                                            @if($diem->MaTienDo)
                                                <span class="badge bg-info">Điểm tiến độ</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Điểm báo cáo cuối</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill px-3">{{ $diem->Diem }}</span>
                                        </td>
                                        <td class="pe-4 text-muted">{{ $diem->NhanXet ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Chưa có dữ liệu điểm số.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Báo cáo -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light-cyan border-0 py-3">
                    <h6 class="mb-0 fw-bold text-info"><i class="far fa-file-alt me-2"></i>Báo cáo</h6>
                </div>
                <div class="card-body p-3">
                    @if($deTai && $baoCaos->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($baoCaos as $bc)
                            <div class="list-group-item border-0 d-flex justify-content-between align-items-center py-3 px-3 rounded-3 mb-2 bg-light-gray-hover">
                                <div class="d-flex align-items-center">
                                    <div class="icon-square bg-white text-info shadow-sm me-3 rounded">
                                        <i class="far fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">{{ $bc->TenBaoCao }}</h6>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($bc->NgayNop)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                                @php
                                    $fileUrl = null;
                                    if ($bc->fileBaoCao) {
                                        $path = str_replace('\\', '/', $bc->fileBaoCao->path);
                                        $path = str_replace('//', '/', $path);
                                        $fileUrl = Str::startsWith($path, 'storage/') ? asset($path) : asset('storage/' . $path);
                                    } elseif ($bc->FileBaoCao && is_string($bc->FileBaoCao) && !Str::startsWith($bc->FileBaoCao, '{')) {
                                        $fileUrl = asset('img/uploads/' . $bc->FileBaoCao);
                                    }
                                @endphp
                                @if($fileUrl)
                                <a href="{{ $fileUrl }}" class="btn btn-light btn-sm text-primary fw-bold" target="_blank">
                                    <i class="fas fa-download me-1"></i> Tải về
                                </a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Chưa có báo cáo nào được nộp.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Colors matching the image */
.bg-light-green { background-color: #e8f5e9; }
.text-success { color: #2e7d32 !important; }

.bg-light-blue { background-color: #e3f2fd; }
.text-primary { color: #1976d2 !important; }

.bg-light-orange { background-color: #fff3e0; }
.text-warning { color: #ef6c00 !important; }

.bg-light-cyan { background-color: #e0f7fa; }
.text-info { color: #0097a7 !important; }

.bg-light-success { background-color: #e8f5e9; }

.bg-light-gray-hover:hover { background-color: #f8f9fa; }

/* Avatar */
.avatar-container {
    width: 100px;
    height: 100px;
    background-color: #4caf50;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
    box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
}

/* Info List */
.info-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}
.icon-col {
    width: 30px;
    padding-top: 2px;
}
.info-col {
    flex: 1;
}

/* Icon Square for Reports */
.icon-square {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

/* Card Styling */
.card {
    transition: transform 0.2s;
}
</style>
@endsection
