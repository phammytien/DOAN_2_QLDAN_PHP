@extends('layouts.canbo')

@section('title', 'Chi tiết Đề tài')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('canbo.detai.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        <h2 class="fw-bold text-dark mb-0">Chi tiết Đề tài: {{ $detai->TenDeTai }}</h2>
    </div>

    <div class="row">
        <!-- Thông tin chính -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>Thông tin chung</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Mã đề tài:</label>
                            <p class="fw-bold text-dark">{{ $detai->MaDeTai }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Lĩnh vực:</label>
                            <p class="text-dark">{{ $detai->LinhVuc }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Năm học:</label>
                            <p class="text-dark">{{ $detai->namHoc->TenNamHoc ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted">Hạn nộp báo cáo:</label>
                            <p class="text-danger fw-bold">
                                {{ $detai->DeadlineBaoCao ? \Carbon\Carbon::parse($detai->DeadlineBaoCao)->format('d/m/Y') : 'Chưa thiết lập' }}
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold text-muted">Mô tả:</label>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($detai->MoTa ?? 'Không có mô tả')) !!}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold text-muted">Yêu cầu:</label>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($detai->YeuCau ?? 'Không có yêu cầu')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sinh viên thực hiện -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-success"><i class="fas fa-users me-2"></i>Sinh viên thực hiện</h5>
                </div>
                <div class="card-body">
                    @if($detai->sinhViens && $detai->sinhViens->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>MSSV</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>SĐT</th>
                                        <th>Lớp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detai->sinhViens as $sv)
                                    <tr>
                                        <td class="fw-bold">{{ $sv->MaSV }}</td>
                                        <td>{{ $sv->TenSV }}</td>
                                        <td>{{ $sv->Email }}</td>
                                        <td>{{ $sv->SDT }}</td>
                                        <td>{{ $sv->lop->TenLop ?? 'Chưa cập nhật' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p>Chưa có sinh viên đăng ký đề tài này.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar thông tin -->
        <div class="col-md-4">
            <!-- Trạng thái -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-3">Trạng thái đề tài</h6>
                    @php
                        $statusClass = match($detai->TrangThai) {
                            'Đã hoàn thành' => 'bg-success',
                            'Đang thực hiện' => 'bg-primary',
                            'Chờ duyệt' => 'bg-warning text-dark',
                            'Hủy' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }} fs-6 px-4 py-2 rounded-pill mb-3">
                        {{ $detai->TrangThai }}
                    </span>
                    
                    @if($detai->TrangThai == 'Chờ duyệt')
                        <div class="d-grid gap-2 mt-3">
                            <form action="{{ route('canbo.detai.approve', $detai->MaDeTai) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100"><i class="fas fa-check me-2"></i> Duyệt đề tài</button>
                            </form>
                            <form action="{{ route('canbo.detai.reject', $detai->MaDeTai) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100"><i class="fas fa-times me-2"></i> Từ chối</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Giảng viên hướng dẫn -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-info"><i class="fas fa-chalkboard-teacher me-2"></i>Giảng viên hướng dẫn</h5>
                </div>
                <div class="card-body">
                    @if($detai->giangVien)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar bg-light text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-tie fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $detai->giangVien->TenGV }}</h6>
                                <small class="text-muted">{{ $detai->giangVien->Email }}</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-phone me-2 text-muted"></i> {{ $detai->giangVien->SDT }}</li>
                            <li><i class="fas fa-graduation-cap me-2 text-muted"></i> {{ $detai->giangVien->HocVi }} {{ $detai->giangVien->HocHam }}</li>
                        </ul>
                    @else
                        <p class="text-muted text-center mb-0">Chưa phân công giảng viên.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
