@extends('layouts.canbo')

@section('title', 'Chi tiết Giảng viên')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Chi tiết Giảng viên
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('canbo.giangvien.index') }}" class="text-decoration-none text-muted">Giảng viên</a></li>
                    <li class="breadcrumb-item active text-dark" aria-current="page">{{ $giangvien->TenGV }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('canbo.giangvien.index') }}" class="btn btn-dark btn-sm shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row g-4">
        <!-- Cột trái: Thông tin cá nhân -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div class="card-body text-center pt-5 pb-4">
                    <div class="avatar-container mb-3 mx-auto">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $giangvien->TenGV }}</h5>
                    <p class="text-muted small mb-2">{{ $giangvien->MaGV }}</p>
                    
                    @if($giangvien->HocVi || $giangvien->HocHam)
                        <span class="badge bg-light-purple text-purple rounded-pill px-3 py-2 mb-4">
                            {{ $giangvien->HocHam ? $giangvien->HocHam . ' - ' : '' }}{{ $giangvien->HocVi }}
                        </span>
                    @else
                        <div class="mb-4"></div>
                    @endif
                    
                    <div class="text-start px-3 mt-2">
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-venus-mars text-purple"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Giới tính</small>
                                <span class="fw-medium">{{ $giangvien->GioiTinh }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-birthday-cake text-purple"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Ngày sinh</small>
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($giangvien->NgaySinh)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-envelope text-purple"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Email</small>
                                <span class="fw-medium">{{ $giangvien->Email }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-phone-alt text-purple"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Số điện thoại</small>
                                <span class="fw-medium">{{ $giangvien->SDT }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="far fa-id-card text-purple"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">CCCD</small>
                                <span class="fw-medium">{{ $giangvien->MaCCCD }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="icon-col"><i class="fas fa-university text-purple"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Khoa</small>
                                <span class="fw-medium">{{ $giangvien->khoa->TenKhoa ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="info-row border-0">
                            <div class="icon-col"><i class="fas fa-book text-purple"></i></div>
                            <div class="info-col">
                                <small class="text-muted d-block">Ngành</small>
                                <span class="fw-medium">{{ $giangvien->nganh->TenNganh ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Thông tin công tác -->
        <div class="col-lg-8">
            <!-- Lớp chủ nhiệm -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-light-cyan border-0 py-3">
                    <h6 class="mb-0 fw-bold text-dark-cyan"><i class="fas fa-users me-2"></i>Lớp chủ nhiệm</h6>
                </div>
                <div class="card-body p-4">
                    @if($lops->count() > 0)
                        <div class="row g-3">
                            @foreach($lops as $lop)
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 border rounded bg-white shadow-sm h-100">
                                    <h6 class="fw-bold text-dark-cyan mb-1">{{ $lop->TenLop }}</h6>
                                    <small class="text-muted">Mã lớp: {{ $lop->MaLop }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0 fst-italic">Chưa chủ nhiệm lớp nào</p>
                    @endif
                </div>
            </div>

            <!-- Đề tài hướng dẫn -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-light-indigo border-0 py-3">
                    <h6 class="mb-0 fw-bold text-indigo"><i class="fas fa-lightbulb me-2"></i>Đề tài hướng dẫn</h6>
                </div>
                <div class="card-body p-0">
                    @if($huongdans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted text-uppercase small fw-bold">Tên đề tài</th>
                                        <th class="py-3 text-muted text-uppercase small fw-bold">Sinh viên</th>
                                        <th class="py-3 text-muted text-uppercase small fw-bold">Năm học</th>
                                        <th class="pe-4 py-3 text-muted text-uppercase small fw-bold text-end">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($huongdans as $hd)
                                    <tr>
                                        <td class="ps-4 fw-medium" style="max-width: 250px;">{{ $hd->TenDeTai }}</td>
                                        <td>
                                            @foreach($hd->sinhviens as $sv)
                                                <div class="small">{{ $sv->TenSV }}</div>
                                            @endforeach
                                        </td>
                                        <td class="small">{{ $hd->namHoc->TenNamHoc ?? '' }}</td>
                                        <td class="pe-4 text-end">
                                            <span class="badge bg-light-success text-success border border-success rounded-pill px-3">
                                                {{ $hd->TrangThai }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Chưa hướng dẫn đề tài nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Đề tài phản biện -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-light-green border-0 py-3">
                    <h6 class="mb-0 fw-bold text-success"><i class="fas fa-tasks me-2"></i>Đề tài phản biện</h6>
                </div>
                <div class="card-body p-0">
                    @if($phanbiens->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted text-uppercase small fw-bold">Tên đề tài</th>
                                        <th class="py-3 text-muted text-uppercase small fw-bold">Vai trò</th>
                                        <th class="py-3 text-muted text-uppercase small fw-bold">Năm học</th>
                                        <th class="pe-4 py-3 text-muted text-uppercase small fw-bold text-end">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($phanbiens as $pb)
                                    <tr>
                                        <td class="ps-4 fw-medium" style="max-width: 250px;">{{ $pb->TenDeTai }}</td>
                                        <td>
                                            <span class="badge bg-light-info text-info border border-info rounded-pill px-2">
                                                {{ $pb->VaiTro }}
                                            </span>
                                        </td>
                                        <td class="small">{{ $pb->TenNamHoc }}</td>
                                        <td class="pe-4 text-end">
                                            <span class="badge bg-light-success text-success border border-success rounded-pill px-3">
                                                {{ $pb->TrangThai }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Chưa tham gia phản biện đề tài nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Colors matching the image */
.bg-light-cyan { background-color: #e0f7fa; }
.text-dark-cyan { color: #006064 !important; }

.bg-light-indigo { background-color: #e8eaf6; }
.text-indigo { color: #283593 !important; }

.bg-light-green { background-color: #e8f5e9; }
.text-success { color: #2e7d32 !important; }

.bg-light-purple { background-color: #f3e5f5; }
.text-purple { color: #7b1fa2 !important; }

.bg-light-info { background-color: #e1f5fe; }
.text-info { color: #0288d1 !important; }

.bg-light-success { background-color: #e8f5e9; }

/* Avatar */
.avatar-container {
    width: 120px;
    height: 120px;
    background-color: #ed8936; /* Orange color from image */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 50px;
    box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);
}

/* Info List */
.info-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}
.icon-col {
    width: 35px;
    padding-top: 2px;
}
.info-col {
    flex: 1;
}

/* Card Styling */
.card {
    transition: transform 0.2s;
}
</style>
@endsection
