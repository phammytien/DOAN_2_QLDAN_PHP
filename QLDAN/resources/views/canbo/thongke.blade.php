@extends('layouts.canbo')

@section('title', 'Thống kê báo cáo')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Thống kê báo cáo</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Tổng đề tài</h5>
                <h2>{{ $tongDeTai }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Đề tài hoàn thành</h5>
                <h2>{{ $deTaiHoanThanh }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Đang thực hiện</h5>
                <h2>{{ $deTaiDangThucHien }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Reports Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Danh sách báo cáo</h5>
    </div>
    <div class="card-body">
        @if($baocaos->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã BC</th>
                            <th>Đề tài</th>
                            <th>Sinh viên</th>
                            <th>Tên file</th>
                            <th>Lần nộp</th>
                            <th>Ngày nộp</th>
                            <th>Deadline</th>
                            <th>Trạng thái</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($baocaos as $bc)
                            <tr>
                                <td>{{ $bc->MaBC }}</td>
                                <td>{{ $bc->deTai->TenDeTai ?? 'N/A' }}</td>
                                <td>{{ $bc->sinhVien->TenSV ?? 'N/A' }}</td>
                                <td>{{ $bc->TenFile ?? 'N/A' }}</td>
                                <td><span class="badge bg-secondary">Lần {{ $bc->LanNop ?? 1 }}</span></td>
                                <td>{{ $bc->NgayNop ? \Carbon\Carbon::parse($bc->NgayNop)->format('d/m/Y H:i') : 'Chưa nộp' }}</td>
                                <td>{{ $bc->Deadline ? \Carbon\Carbon::parse($bc->Deadline)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($bc->TrangThai) {
                                            'Đã duyệt' => 'success',
                                            'Chờ duyệt' => 'warning',
                                            'Yêu cầu chỉnh sửa' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $bc->TrangThai ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $bc->NhanXet ?? 'Chưa có' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Chưa có báo cáo nào.
            </div>
        @endif
    </div>
</div>
@endsection
