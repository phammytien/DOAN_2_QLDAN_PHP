@extends('layouts.canbo')

@section('title', 'Chi tiết Lớp ' . $lop->TenLop)

@section('content')
<div class="container-fluid py-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('canbo.lop.index') }}" class="btn btn-light shadow-sm text-primary fw-semibold">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>

    <!-- Class Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-8 p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-gradient-cyan me-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div>
                                    <h2 class="fw-bold mb-1">{{ $lop->TenLop }}</h2>
                                    <p class="text-muted mb-0">Mã lớp: <span class="fw-bold text-dark">{{ $lop->MaLop }}</span></p>
                                </div>
                            </div>
                            
                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light p-2 me-3 text-primary">
                                            <i class="fas fa-building fa-lg"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Khoa</small>
                                            <span class="fw-semibold">{{ $lop->khoa->TenKhoa ?? 'Chưa cập nhật' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light p-2 me-3 text-info">
                                            <i class="fas fa-graduation-cap fa-lg"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Ngành</small>
                                            <span class="fw-semibold">{{ $lop->nganh->TenNganh ?? 'Chưa cập nhật' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 bg-light p-4 d-flex flex-column justify-content-center align-items-center border-start">
                            <h3 class="fw-bold text-primary display-4 mb-0">{{ $sinhviens->total() }}</h3>
                            <p class="text-muted fw-semibold">Sinh viên</p>
                            <a href="{{ route('canbo.lop.export_students', $lop->MaLop) }}" class="btn btn-success shadow-sm rounded-pill px-4 mt-2">
                                <i class="fas fa-file-excel me-2"></i>Xuất danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-primary">
                <i class="fas fa-user-graduate me-2"></i>Danh sách Sinh viên
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted text-uppercase small fw-bold">MSSV</th>
                            <th class="px-4 py-3 text-muted text-uppercase small fw-bold">Họ tên</th>
                            <th class="px-4 py-3 text-muted text-uppercase small fw-bold">Giới tính</th>
                            <th class="px-4 py-3 text-muted text-uppercase small fw-bold">Ngày sinh</th>
                            <th class="px-4 py-3 text-muted text-uppercase small fw-bold">Đề tài</th>
                            <th class="px-4 py-3 text-muted text-uppercase small fw-bold text-center">Điểm TB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sinhviens as $sv)
                        <tr>
                            <td class="px-4 py-3 fw-semibold text-primary">{{ $sv->MaSV }}</td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded-circle bg-light-primary text-primary me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">
                                        {{ substr($sv->TenSV, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $sv->TenSV }}</div>
                                        <small class="text-muted">{{ $sv->Email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($sv->GioiTinh == 'Nam')
                                    <span class="badge bg-light text-primary border border-primary-subtle rounded-pill">Nam</span>
                                @else
                                    <span class="badge bg-light text-danger border border-danger-subtle rounded-pill">Nữ</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-muted">
                                {{ \Carbon\Carbon::parse($sv->NgaySinh)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                @php $detai = $sv->detai->first(); @endphp
                                @if($detai)
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-dark text-truncate" style="max-width: 200px;" title="{{ $detai->TenDeTai }}">
                                            {{ $detai->TenDeTai }}
                                        </span>
                                        @if($detai->giangVien)
                                            <small class="text-muted">
                                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                                GVHD: {{ $detai->giangVien->TenGV }}
                                            </small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">Chưa đăng ký</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $diemTB = $sv->diems->avg('Diem');
                                @endphp
                                @if($diemTB !== null)
                                    <span class="badge {{ $diemTB >= 5 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                        {{ number_format($diemTB, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3 opacity-50"></i>
                                    <p>Lớp này chưa có sinh viên nào.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $sinhviens->links() }}
        </div>
    </div>
</div>

<style>
.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.bg-gradient-cyan {
    background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
}

.bg-light-primary {
    background-color: #e3f2fd;
}
</style>
@endsection
