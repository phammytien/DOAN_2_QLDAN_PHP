@extends('layouts.sinhvien')

@section('title', 'Chi tiết tiến độ')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Chi tiết tiến độ</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('sinhvien.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('sinhvien.tiendo.index') }}">Tiến độ</a></li>
                            <li class="breadcrumb-item active">Chi tiết</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('sinhvien.tiendo.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            {{-- Thông tin tiến độ --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Thông tin tiến độ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Đề tài:</strong> {{ $detai->TenDeTai }}</p>
                            <p><strong>Nội dung:</strong> {{ $tiendo->NoiDung }}</p>
                            <p><strong>Ghi chú:</strong> {{ $tiendo->GhiChu ?? 'Không có ghi chú' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Hạn nộp:</strong> 
                                <span class="badge {{ \Carbon\Carbon::parse($tiendo->Deadline)->isPast() ? 'bg-danger' : 'bg-warning' }}">
                                    {{ \Carbon\Carbon::parse($tiendo->Deadline)->format('d/m/Y H:i') }}
                                </span>
                            </p>
                            <p><strong>Ngày nộp:</strong> 
                                @if($tiendo->NgayNop)
                                    <span class="badge bg-success">{{ \Carbon\Carbon::parse($tiendo->NgayNop)->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="badge bg-secondary">Chưa nộp</span>
                                @endif
                            </p>
                            <p><strong>Trạng thái:</strong> 
                                @if($tiendo->NgayNop)
                                    <span class="badge bg-success">Đã nộp</span>
                                @elseif(\Carbon\Carbon::parse($tiendo->Deadline)->isPast())
                                    <span class="badge bg-danger">Quá hạn</span>
                                @else
                                    <span class="badge bg-warning">Chưa nộp</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lịch sử công việc --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Lịch sử công việc</h5>
                </div>
                <div class="card-body">
                    @if($baocao)
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Đã nộp báo cáo</h6>
                                    <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($tiendo->NgayNop)->format('d/m/Y H:i') }}</p>
                                    @if($baocao->LinkFile)
                                        <a href="{{ asset($baocao->LinkFile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>Xem file báo cáo
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>Chưa có lịch sử công việc cho tiến độ này.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Nút nộp báo cáo --}}
            @if(!$tiendo->NgayNop)
                <div class="text-center">
                    <a href="{{ route('sinhvien.baocao.create', ['tiendo_id' => $tiendo->MaTienDo]) }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-cloud-upload me-2"></i>Nộp báo cáo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #198754;
    }
    .timeline-content {
        padding-left: 10px;
    }
</style>
@endsection
