@extends('layouts.canbo')

@section('title', 'Quản lý Báo cáo')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý Báo cáo</h2>
            <p class="text-muted mb-0">Theo dõi báo cáo và duyệt yêu cầu nộp bổ sung.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Tổng số báo cáo</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Chờ duyệt</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Yêu cầu nộp bù</h6>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['late_request'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('canbo.baocao.index') }}" method="GET" class="mb-4 d-flex gap-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên đề tài hoặc sinh viên..." value="{{ request('search') }}" style="max-width: 300px;">
                <select name="trangthai" class="form-select" style="max-width: 200px;" onchange="this.form.submit()">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="Chờ duyệt" {{ request('trangthai') == 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="Xin nộp bổ sung" {{ request('trangthai') == 'Xin nộp bổ sung' ? 'selected' : '' }}>Xin nộp bổ sung</option>
                    <option value="Đã duyệt" {{ request('trangthai') == 'Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
                </select>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Đề tài</th>
                            <th>Sinh viên</th>
                            <th>Lần nộp</th>
                            <th>File</th>
                            <th>Ngày nộp</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($baocaos as $bc)
                        <tr>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $bc->deTai->TenDeTai ?? 'N/A' }}">
                                    {{ $bc->deTai->TenDeTai ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $bc->sinhVien->TenSV ?? 'N/A' }}</td>
                            <td>{{ $bc->LanNop }}</td>
                            <td>
                                @if($bc->fileBaoCao)
                                    <div class="mb-1">
                                        <a href="{{ asset(Str::startsWith(ltrim($bc->fileBaoCao->path, '/'), 'storage/') ? ltrim($bc->fileBaoCao->path, '/') : 'storage/' . ltrim($bc->fileBaoCao->path, '/')) }}" 
                                           target="_blank" 
                                           class="text-decoration-none"
                                           title="{{ $bc->fileBaoCao->name }}">
                                            <i class="fas fa-file-pdf me-1 text-danger"></i> {{ Str::limit($bc->fileBaoCao->name, 30) }}
                                        </a>
                                    </div>
                                @elseif($bc->LinkFile)
                                    <div class="mb-1">
                                        <a href="{{ asset(Str::startsWith(ltrim($bc->LinkFile, '/'), 'storage/') ? ltrim($bc->LinkFile, '/') : 'storage/' . ltrim($bc->LinkFile, '/')) }}" 
                                           target="_blank" 
                                           class="text-decoration-none"
                                           title="{{ $bc->TenFile }}">
                                            <i class="fas fa-file-pdf me-1 text-danger"></i> {{ Str::limit($bc->TenFile, 30) }}
                                        </a>
                                    </div>
                                @endif
                                @if($bc->fileCode)
                                    <div>
                                        <a href="{{ asset(Str::startsWith(ltrim($bc->fileCode->path, '/'), 'storage/') ? ltrim($bc->fileCode->path, '/') : 'storage/' . ltrim($bc->fileCode->path, '/')) }}" 
                                           target="_blank" 
                                           class="text-decoration-none"
                                           title="{{ $bc->fileCode->name }}">
                                            <i class="fas fa-file-code me-1 text-warning"></i> {{ Str::limit($bc->fileCode->name, 30) }}
                                        </a>
                                    </div>
                                @elseif($bc->LinkFileCode)
                                    <div>
                                        <a href="{{ asset(Str::startsWith(ltrim($bc->LinkFileCode, '/'), 'storage/') ? ltrim($bc->LinkFileCode, '/') : 'storage/' . ltrim($bc->LinkFileCode, '/')) }}" 
                                           target="_blank" 
                                           class="text-decoration-none"
                                           title="{{ $bc->TenFileCode }}">
                                            <i class="fas fa-file-code me-1 text-warning"></i> {{ Str::limit($bc->TenFileCode, 30) }}
                                        </a>
                                    </div>
                                @endif
                                @if(!$bc->fileBaoCao && !$bc->fileCode && !$bc->LinkFile && !$bc->LinkFileCode)
                                    <span class="text-muted">Chưa có file</span>
                                @endif
                            </td>
                            <td>{{ $bc->NgayNop ? \Carbon\Carbon::parse($bc->NgayNop)->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = match($bc->TrangThai) {
                                        'Đã duyệt' => 'bg-success bg-opacity-10 text-success',
                                        'Chờ duyệt' => 'bg-primary bg-opacity-10 text-primary',
                                        'Xin nộp bổ sung' => 'bg-warning bg-opacity-10 text-warning',
                                        'Được nộp bổ sung' => 'bg-info bg-opacity-10 text-info',
                                        'Trễ hạn' => 'bg-danger bg-opacity-10 text-danger',
                                        default => 'bg-secondary bg-opacity-10 text-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill">
                                    {{ $bc->TrangThai }}
                                </span>
                            </td>
                            <td class="text-end">
                                {{-- Cán bộ chỉ xem, không duyệt --}}
                                @if($bc->fileBaoCao)
                                    <a href="{{ asset(Str::startsWith(ltrim($bc->fileBaoCao->path, '/'), 'storage/') ? ltrim($bc->fileBaoCao->path, '/') : 'storage/' . ltrim($bc->fileBaoCao->path, '/')) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-info mb-1" 
                                       title="Xem báo cáo">
                                        <i class="fas fa-eye"></i> Báo cáo
                                    </a>
                                @elseif($bc->LinkFile)
                                    <a href="{{ asset(Str::startsWith(ltrim($bc->LinkFile, '/'), 'storage/') ? ltrim($bc->LinkFile, '/') : 'storage/' . ltrim($bc->LinkFile, '/')) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-info mb-1" 
                                       title="Xem báo cáo">
                                        <i class="fas fa-eye"></i> Báo cáo
                                    </a>
                                @endif
                                @if($bc->fileCode)
                                    <a href="{{ asset(Str::startsWith(ltrim($bc->fileCode->path, '/'), 'storage/') ? ltrim($bc->fileCode->path, '/') : 'storage/' . ltrim($bc->fileCode->path, '/')) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-warning text-dark" 
                                       title="Xem code">
                                        <i class="fas fa-code"></i> Code
                                    </a>
                                @elseif($bc->LinkFileCode)
                                    <a href="{{ asset(Str::startsWith(ltrim($bc->LinkFileCode, '/'), 'storage/') ? ltrim($bc->LinkFileCode, '/') : 'storage/' . ltrim($bc->LinkFileCode, '/')) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-warning text-dark" 
                                       title="Xem code">
                                        <i class="fas fa-code"></i> Code
                                    </a>
                                @endif
                                @if(!$bc->fileBaoCao && !$bc->fileCode && !$bc->LinkFile && !$bc->LinkFileCode)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        

                        
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Không có dữ liệu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $baocaos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
