@extends('layouts.canbo')

@section('title', 'Quản lý Đề tài')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Đề tài</h2>
            <p class="text-muted mb-0">Tổng quan và quản lý tất cả đề tài trong hệ thống.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTopicModal">
            <i class="fas fa-plus me-2"></i> Tạo đề tài mới
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tổng số đề tài</h6>
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
                        <h6 class="text-muted mb-2">Chờ duyệt</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                        <i class="fas fa-hourglass-half fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Đang thực hiện</h6>
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
                        <h6 class="text-muted mb-2">Đã hoàn thành</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['completed'] }}</h3>
                    </div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success rounded-circle p-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('canbo.detai.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên đề tài..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="trangthai" class="form-select" onchange="this.form.submit()">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="Chờ duyệt" {{ request('trangthai') == 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="Đang thực hiện" {{ request('trangthai') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="Đã hoàn thành" {{ request('trangthai') == 'Đã hoàn thành' ? 'selected' : '' }}>Đã hoàn thành</option>
                        <option value="Hủy" {{ request('trangthai') == 'Hủy' ? 'selected' : '' }}>Hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="linhvuc" class="form-select" onchange="this.form.submit()">
                        <option value="all">Tất cả lĩnh vực</option>
                        @foreach($linhvucs as $lv)
                            <option value="{{ $lv }}" {{ request('linhvuc') == $lv ? 'selected' : '' }}>{{ $lv }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="width: 50px;">Mã ĐT</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Tên đề tài</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Lĩnh vực</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Giảng viên</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Sinh viên</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Trạng thái</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detais as $detai)
                        <tr>
                            <td class="fw-bold text-dark">{{ $detai->MaDeTai }}</td>
                            <td>
                                <h6 class="mb-0 text-sm fw-bold text-dark">{{ $detai->TenDeTai }}</h6>
                            </td>
                            <td class="text-sm text-secondary">{{ $detai->LinhVuc }}</td>
                            <td class="text-sm text-dark fw-semibold">{{ $detai->giangVien->TenGV ?? 'Chưa phân công' }}</td>
                            <td>
                                @if($detai->sinhViens->count() > 0)
                                    <div class="d-flex flex-column">
                                        @foreach($detai->sinhViens as $sv)
                                            <span class="text-xs text-secondary">{{ $sv->TenSV }} ({{ $sv->MaSV }})</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-muted">Chưa có</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($detai->TrangThai) {
                                        'Đã hoàn thành' => 'bg-success bg-opacity-10 text-success',
                                        'Đang thực hiện' => 'bg-primary bg-opacity-10 text-primary',
                                        'Chờ duyệt' => 'bg-warning bg-opacity-10 text-warning',
                                        'Hủy' => 'bg-danger bg-opacity-10 text-danger',
                                        default => 'bg-secondary bg-opacity-10 text-secondary'
                                    };
                                    $icon = match($detai->TrangThai) {
                                        'Đã hoàn thành' => 'fa-check-circle',
                                        'Đang thực hiện' => 'fa-spinner',
                                        'Chờ duyệt' => 'fa-clock',
                                        'Hủy' => 'fa-times-circle',
                                        default => 'fa-circle'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill">
                                    <i class="fas {{ $icon }} me-1"></i> {{ $detai->TrangThai }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        <li><a class="dropdown-item" href="{{ route('canbo.detai.show', $detai->MaDeTai) }}"><i class="fas fa-eye me-2 text-info"></i> Xem chi tiết</a></li>
                                        @if($detai->TrangThai == 'Chờ duyệt')
                                            <li>
                                                <form action="{{ route('canbo.detai.approve', $detai->MaDeTai) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"><i class="fas fa-check me-2 text-success"></i> Duyệt</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('canbo.detai.reject', $detai->MaDeTai) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"><i class="fas fa-times me-2 text-danger"></i> Từ chối</button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
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

<!-- Create Topic Modal -->
<div class="modal fade" id="createTopicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tạo đề tài mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('canbo.detai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Tên đề tài <span class="text-danger">*</span></label>
                            <input type="text" name="TenDeTai" class="form-control" required placeholder="Nhập tên đề tài...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Lĩnh vực <span class="text-danger">*</span></label>
                            <select name="LinhVuc" class="form-select" required>
                                <option value="">-- Chọn lĩnh vực --</option>
                                @foreach($linhvucOptions as $lv)
                                    <option value="{{ $lv }}">{{ $lv }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Năm học <span class="text-danger">*</span></label>
                            <select name="MaNamHoc" class="form-select" required>
                                <option value="">-- Chọn năm học --</option>
                                @foreach($namhocs as $nh)
                                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giảng viên hướng dẫn</label>
                            <select name="MaGV" class="form-select">
                                <option value="">-- Chọn giảng viên (nếu có) --</option>
                                @foreach($giangviens as $gv)
                                    <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số lượng sinh viên tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="SoLuongSV" class="form-control" value="1" min="1" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hạn nộp báo cáo</label>
                            <input type="date" name="DeadlineBaoCao" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="MoTa" class="form-control" rows="3" placeholder="Mô tả chi tiết về đề tài..."></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Yêu cầu</label>
                            <textarea name="YeuCau" class="form-control" rows="3" placeholder="Yêu cầu kiến thức, kỹ năng..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu đề tài</button>
                </div>
            </form>
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
