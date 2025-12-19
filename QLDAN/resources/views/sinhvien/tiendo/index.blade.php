@extends('layouts.sinhvien')

@section('content')
<style>
    body {
        background-color: #f5f7fa;
    }
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        border-left: 4px solid #4f46e5;
    }
    .page-header h4 {
        margin: 0;
        color: #1f2937;
        font-size: 1.25rem;
        font-weight: 600;
    }
    .topic-info {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .progress-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .table thead th {
        background-color: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        border-bottom: 2px solid #e5e7eb;
    }
    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
    }
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    .score-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: #10b981;
    }
    .no-score {
        color: #9ca3af;
        font-style: italic;
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="page-header">
        <h4>
            <i class="bi bi-list-check me-2"></i>
            Tiến độ đề tài của tôi
        </h4>
    </div>

    @if(!$detai)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Bạn chưa đăng ký đề tài nào. Vui lòng đăng ký đề tài trước.
        </div>
    @else
        {{-- THÔNG TIN ĐỀ TÀI --}}
        <div class="topic-info">
            <h5 class="mb-3"><i class="bi bi-journal-text me-2"></i>Thông tin đề tài</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã đề tài:</strong> <span class="badge bg-primary">{{ $detai->MaDeTai }}</span></p>
                    <p><strong>Tên đề tài:</strong> {{ $detai->TenDeTai }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Giảng viên hướng dẫn:</strong> {{ $detai->giangVien->TenGV ?? 'N/A' }}</p>
                    <p><strong>Tổng số tiến độ:</strong> {{ $tiendos->count() }} tiến độ</p>
                </div>
            </div>
        </div>

        {{-- BẢNG TIẾN ĐỘ --}}
        <div class="progress-table">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th>Nội dung công việc</th>
                            <th style="width: 120px;">Deadline</th>
                            <th style="width: 150px;">Ngày nộp</th>
                            <th style="width: 120px;">Trạng thái</th>
                            <th style="width: 100px;" class="text-center">Điểm</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tiendos as $index => $tiendo)
                        <tr>
                            <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                            
                            {{-- Nội dung với link --}}
                            <td>
                                <a href="{{ route('sinhvien.tiendo.show', $tiendo->MaTienDo) }}" class="text-decoration-none text-primary fw-semibold">
                                    {{ $tiendo->NoiDung }}
                                    <i class="bi bi-arrow-right-circle ms-1"></i>
                                </a>
                            </td>
                            
                            {{-- Deadline --}}
                            <td>
                                @if($tiendo->Deadline)
                                    <span class="{{ \Carbon\Carbon::parse($tiendo->Deadline)->isPast() && !$tiendo->NgayNop ? 'text-danger fw-bold' : '' }}">
                                        {{ \Carbon\Carbon::parse($tiendo->Deadline)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            {{-- Ngày nộp --}}
                            <td>
                                @if($tiendo->NgayNop)
                                    {{ \Carbon\Carbon::parse($tiendo->NgayNop)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Chưa nộp</span>
                                @endif
                            </td>
                            
                            {{-- Trạng thái --}}
                            <td>
                                @if($tiendo->TrangThai == 'Xin nộp bổ sung')
                                    <span class="badge bg-warning text-dark">Chờ duyệt nộp lại</span>
                                @elseif($tiendo->TrangThai == 'Được nộp bổ sung')
                                    <span class="badge bg-info">Được nộp lại</span>
                                @elseif(!$tiendo->NgayNop)
                                    <span class="badge bg-secondary">Chưa nộp</span>
                                @elseif($tiendo->Deadline && \Carbon\Carbon::parse($tiendo->NgayNop)->gt(\Carbon\Carbon::parse($tiendo->Deadline)))
                                    <span class="badge bg-danger">Nộp trễ</span>
                                @else
                                    <span class="badge bg-success">Đúng hạn</span>
                                @endif
                            </td>
                            
                            {{-- Điểm --}}
                            <td class="text-center">
                                @php
                                    $chamDiem = $chamdiems->get($tiendo->MaTienDo);
                                    // Fallback: Nếu không có điểm cá nhân thì lấy điểm chung của tiến độ
                                    $diem = $chamDiem ? $chamDiem->Diem : $tiendo->Diem;
                                @endphp
                                @if(isset($diem))
                                    <div class="score-display">{{ number_format($diem, 1) }}</div>
                                    @if($tiendo->Deadline && $tiendo->NgayNop && \Carbon\Carbon::parse($tiendo->NgayNop)->gt(\Carbon\Carbon::parse($tiendo->Deadline)))
                                        <small class="text-danger">(-10%: {{ number_format($diem * 0.9, 1) }})</small>
                                    @endif
                                @else
                                    <span class="no-score">Chưa chấm</span>
                                @endif
                            </td>
                            
                            {{-- Nhận xét --}}
                            <td>
                                @php
                                    $nhanXetText = $chamDiem ? $chamDiem->NhanXet : $tiendo->NhanXet;
                                @endphp
                                @if($nhanXetText)
                                    <div class="small">{{ $nhanXetText }}</div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0">Chưa có tiến độ nào được tạo</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
