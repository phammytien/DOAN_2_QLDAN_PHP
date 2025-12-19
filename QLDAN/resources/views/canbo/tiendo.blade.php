@extends('layouts.canbo')

@section('title', 'Quản lý Tiến độ')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Tiến độ</h2>
            <p class="text-muted mb-0">Theo dõi và quản lý tất cả các đề tài của bạn.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tổng số</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                        <i class="fas fa-folder fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Đúng hạn</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['completed'] }}</h3>
                    </div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success rounded-circle p-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Chưa nộp</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['in_progress'] }}</h3>
                    </div>
                    <div class="icon-shape bg-info bg-opacity-10 text-info rounded-circle p-3">
                        <i class="fas fa-sync-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Trễ hạn</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['overdue'] }}</h3>
                    </div>
                    <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-circle p-3">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Filters -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <form action="{{ route('canbo.tiendo') }}" method="GET" class="d-flex gap-3 flex-grow-1 me-3">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm nội dung..." value="{{ request('search') }}">
                    </div>
                    <select name="trangthai" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="Hoàn thành" {{ request('trangthai') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Đang thực hiện" {{ request('trangthai') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="Trễ hạn" {{ request('trangthai') == 'Trễ hạn' ? 'selected' : '' }}>Trễ hạn</option>
                    </select>
                    <select name="madetai" class="form-select" style="max-width: 250px;" onchange="this.form.submit()">
                        <option value="all">Tất cả đề tài</option>
                        @foreach($all_detais as $dt)
                            <option value="{{ $dt->MaDeTai }}" {{ request('madetai') == $dt->MaDeTai ? 'selected' : '' }}>{{ $dt->TenDeTai }}</option>
                        @endforeach
                    </select>
                </form>

            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Mã</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Đề tài</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nội dung</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Cập nhật</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Deadline</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Trạng thái</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detais as $dt)
                        @php
                            $latestProgress = $dt->tiendos->first();
                        @endphp
                        <tr>
                            <td class="fw-bold text-dark">{{ $dt->MaDeTai }}</td>
                            <td>
                                {{ $dt->TenDeTai }}
                            </td>
                            <td>{{ $latestProgress->NoiDung ?? 'Chưa có cập nhật' }}</td>
                            <td class="text-secondary">{{ $latestProgress ? \Carbon\Carbon::parse($latestProgress->ThoiGianCapNhat)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                            <td class="text-secondary">{{ $latestProgress && $latestProgress->Deadline ? \Carbon\Carbon::parse($latestProgress->Deadline)->format('d/m/Y') : 'Chưa có' }}</td>
                            <td>
                                @php
                                    // Sử dụng TrangThaiTuDong để tự động tính trạng thái dựa trên deadline
                                    $status = $latestProgress ? $latestProgress->TrangThaiTuDong : 'Chưa bắt đầu';
                                    $statusClass = match($status) {
                                        'Đúng hạn' => 'bg-success bg-opacity-10 text-success',
                                        'Chưa nộp' => 'bg-warning bg-opacity-10 text-warning',
                                        'Trễ hạn' => 'bg-danger bg-opacity-10 text-danger',
                                        'Chưa bắt đầu' => 'bg-secondary bg-opacity-10 text-secondary',
                                        default => 'bg-secondary bg-opacity-10 text-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill">
                                    {{ $status }}
                                </span>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-tasks fa-3x mb-3"></i>
                                    <p>Không tìm thấy đề tài nào.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $detais->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
        border-bottom-width: 1px;
    }
    .text-xs {
        font-size: 0.75rem !important;
    }
</style>
@endsection
