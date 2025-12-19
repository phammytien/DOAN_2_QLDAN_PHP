@extends('layouts.sinhvien')

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary mb-2">
                        <i class="fas fa-bullhorn me-3"></i>Thông Báo Mới
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Tổng số: <span class="fw-semibold text-primary">{{ $thongBao->count() }}</span> thông báo
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Grid -->
    <div class="row g-4">
        @forelse($thongBao as $tb)
        <div class="col-12">
            <div class="card notification-card shadow-sm border-0 rounded-3 h-100">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <!-- Icon & Priority Badge -->
                        <div class="col-auto">
                            <div class="notification-icon-wrapper">
                                @if($tb->MucDo == 'Khẩn')
                                    <div class="notification-icon bg-danger-subtle">
                                        <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                    </div>
                                @elseif($tb->MucDo == 'Quan trọng')
                                    <div class="notification-icon bg-warning-subtle">
                                        <i class="fas fa-exclamation-circle text-warning fa-2x"></i>
                                    </div>
                                @else
                                    <div class="notification-icon bg-info-subtle">
                                        <i class="fas fa-info-circle text-info fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="col">
                            <div class="mb-2">
                                <h5 class="card-title mb-0 text-dark fw-bold">
                                    <i class="fas fa-comment-dots text-primary me-2"></i>{{ $tb->NoiDung }}
                                </h5>
                            </div>

                            <div class="notification-meta d-flex flex-wrap gap-3 mb-3">
                                <!-- Priority -->
                                @if($tb->MucDo)
                                <div class="meta-item">
                                    <i class="fas fa-flag text-primary me-2"></i>
                                    @php
                                        $mucDoClass = match($tb->MucDo) {
                                            'Khẩn' => 'danger',
                                            'Quan trọng' => 'warning',
                                            'Bình thường' => 'info',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $mucDoClass }}-subtle text-{{ $mucDoClass }} px-3 py-2">
                                        {{ $tb->MucDo }}
                                    </span>
                                </div>
                                @endif

                                <!-- Time -->
                                <div class="meta-item">
                                    <i class="far fa-calendar-alt text-primary me-2"></i>
                                    <small class="text-muted">
                                        Đăng lúc: {{ $tb->TGDang ? \Carbon\Carbon::parse($tb->TGDang)->format('d/m/Y H:i') : $tb->TGDang }}
                                    </small>
                                </div>

                                <!-- File -->
                                @if($tb->TenFile)
                                <div class="meta-item">
                                    <a href="{{ asset('storage/uploads/thongbao/' . $tb->TenFile) }}" 
                                       target="_blank"
                                       class="text-decoration-none btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>Tải tệp đính kèm
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có thông báo nào</h5>
                    <p class="text-muted mb-0">Hiện tại chưa có thông báo mới từ nhà trường</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

</div>

<style>
/* Card Styles */
.notification-card {
    transition: all 0.3s ease;
    border-left: 4px solid #0d6efd !important;
}

.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}

.notification-icon-wrapper {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.notification-meta {
    font-size: 0.95rem;
}

.meta-item {
    display: flex;
    align-items: center;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    font-size: 0.85rem;
    border-radius: 6px;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

/* Button Styles */
.btn {
    border-radius: 8px;
    font-weight: 500;
}

/* Empty State */
.fa-inbox {
    opacity: 0.3;
}
</style>
@endsection
