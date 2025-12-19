@extends('layouts.sinhvien')

@section('title','Nộp báo cáo & Tiến độ')

@section('content')
<div class="container-fluid py-4">
    
    @php
        $lastReport = $baoCao->last();
        $isReportApproved = ($lastReport && $lastReport->TrangThai === 'Đã duyệt') || $hasGrade;
    @endphp

    {{-- ===================== TIẾN ĐỘ THỰC HIỆN (MILESTONES) ===================== --}}
    <div class="mb-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-2">
                    <i class="fas fa-tasks me-3"></i>Tiến Độ Thực Hiện Đề Tài
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Theo dõi và nộp bài theo từng mốc tiến độ
                </p>
            </div>
        </div>

        @if($isReportApproved)
            <div class="alert alert-success border-0 d-flex align-items-center mb-4">
                <i class="fas fa-check-double fa-2x me-3"></i>
                <div>
                    <h6 class="mb-1 fw-bold">Đề tài đã hoàn thành!</h6>
                    <p class="mb-0">Báo cáo tổng kết đã được duyệt. Các mốc tiến độ đã được khóa.</p>
                </div>
            </div>
        @endif

        <!-- Milestones Cards -->
        <div class="row g-4">
            @forelse($tiendos as $td)
            <div class="col-12">
                <div class="card milestone-card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Icon & Status -->
                            <div class="col-auto">
                                <div class="milestone-icon-wrapper">
                                    @if($td->NgayNop)
                                        <div class="milestone-icon bg-success-subtle">
                                            <i class="fas fa-check-circle text-success fa-2x"></i>
                                        </div>
                                    @elseif($td->Deadline && \Carbon\Carbon::parse($td->Deadline)->isPast())
                                        <div class="milestone-icon bg-danger-subtle">
                                            <i class="fas fa-exclamation-circle text-danger fa-2x"></i>
                                        </div>
                                    @else
                                        <div class="milestone-icon bg-primary-subtle">
                                            <i class="fas fa-clock text-primary fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="col">
                                <h5 class="card-title mb-3 text-dark fw-bold">
                                    <i class="fas fa-clipboard-list text-primary me-2"></i>{{ $td->NoiDung }}
                                </h5>

                                <div class="milestone-meta d-flex flex-wrap gap-3 mb-3">
                                    <!-- Deadline -->
                                    @if($td->Deadline)
                                    <div class="meta-item">
                                        <i class="far fa-calendar-alt text-primary me-2"></i>
                                        <span class="{{ \Carbon\Carbon::parse($td->Deadline)->isPast() && !$td->NgayNop ? 'text-danger fw-bold' : 'text-muted' }}">
                                            Hạn: {{ \Carbon\Carbon::parse($td->Deadline)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    @endif

                                    <!-- Status Badge -->
                                    <div class="meta-item">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        @if($td->NgayNop)
                                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                                <i class="fas fa-check me-1"></i>Đã nộp
                                            </span>
                                            <small class="text-muted ms-2">
                                                {{ \Carbon\Carbon::parse($td->NgayNop)->format('d/m/Y H:i') }}
                                            </small>
                                        @else
                                            @if($td->Deadline && \Carbon\Carbon::parse($td->Deadline)->isPast())
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Trễ hạn
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                                    <i class="fas fa-hourglass-half me-1"></i>Chưa nộp
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- File -->
                                    @if($td->fileBaoCao)
                                    <div class="meta-item">
                                        @php
                                            $pathBaoCao = str_replace('\\', '/', $td->fileBaoCao->path);
                                            $urlBaoCao = Str::startsWith($pathBaoCao, 'storage/') ? asset($pathBaoCao) : asset('storage/' . $pathBaoCao);
                                        @endphp
                                        <a href="{{ $urlBaoCao }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            <span class="text-primary">{{ $td->fileBaoCao->name }}</span>
                                        </a>
                                    </div>
                                    @endif

                                    @if($td->fileCode)
                                    <div class="meta-item">
                                        @php
                                            $pathCode = str_replace('\\', '/', $td->fileCode->path);
                                            $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                                        @endphp
                                        <a href="{{ $urlCode }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-file-code text-warning me-2"></i>
                                            <span class="text-warning">{{ $td->fileCode->name }}</span>
                                        </a>
                                    </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-3">
                                    @if($isReportApproved)
                                        <span class="badge bg-secondary text-white px-3 py-2">
                                            <i class="fas fa-lock me-1"></i>Đã khóa (Đề tài hoàn thành)
                                        </span>
                                    @elseif($td->TrangThai === 'Đã duyệt')
                                        <span class="badge bg-success text-white px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Đã được duyệt
                                        </span>
                                    @else
                                        @if($td->NgayNop)
                                            @if($td->Deadline && \Carbon\Carbon::parse($td->Deadline)->isPast())
                                                @if($td->TrangThai === 'Xin nộp bổ sung')
                                                    <span class="badge bg-warning text-dark px-3 py-2">
                                                        <i class="fas fa-hourglass-split me-1"></i>Đang chờ duyệt nộp lại
                                                    </span>
                                                @elseif($td->TrangThai === 'Được nộp bổ sung')
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNopTienDo{{ $td->MaTienDo }}">
                                                        <i class="fas fa-upload me-2"></i>Nộp lại (Đã duyệt)
                                                    </button>
                                                @else
                                                    <form action="{{ route('sinhvien.tiendo.requestLate', $td->MaTienDo) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-question-circle me-2"></i>Xin nộp lại
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNopTienDo{{ $td->MaTienDo }}">
                                                    <i class="fas fa-upload me-2"></i>Nộp lại
                                                </button>
                                            @endif
                                        @else
                                            @if($td->Deadline && \Carbon\Carbon::parse($td->Deadline)->isPast())
                                                @if($td->TrangThai === 'Xin nộp bổ sung')
                                                    <span class="badge bg-warning text-dark px-3 py-2">
                                                        <i class="fas fa-hourglass-split me-1"></i>Đang chờ duyệt
                                                    </span>
                                                @elseif($td->TrangThai === 'Được nộp bổ sung')
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNopTienDo{{ $td->MaTienDo }}">
                                                        <i class="fas fa-upload me-2"></i>Nộp bổ sung
                                                    </button>
                                                @else
                                                    <form action="{{ route('sinhvien.tiendo.requestLate', $td->MaTienDo) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-question-circle me-2"></i>Xin nộp bổ sung
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNopTienDo{{ $td->MaTienDo }}">
                                                    <i class="fas fa-upload me-2"></i>Nộp bài
                                                </button>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Nộp bài -->
                @if(!$isReportApproved)
                <div class="modal fade" id="modalNopTienDo{{ $td->MaTienDo }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white border-0">
                                <h5 class="modal-title fw-bold">
                                    <i class="fas fa-upload me-2"></i>Nộp báo cáo: {{ $td->NoiDung }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('sinhvien.tiendo.update', $td->MaTienDo) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-file-upload text-primary me-2"></i>
                                            Chọn file báo cáo (PDF/Word)
                                        </label>
                                        <input type="file" name="file_baocao" class="form-control shadow-sm" accept=".pdf,.doc,.docx">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Định dạng: PDF, DOC, DOCX
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-file-code text-primary me-2"></i>
                                            Chọn file Source Code (nếu có)
                                        </label>
                                        <input type="file" name="file_code" class="form-control shadow-sm" accept=".zip,.rar,.7z">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Định dạng: ZIP, RAR, 7Z
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 bg-light">
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i>Hủy
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-paper-plane me-2"></i>Nộp bài
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có mốc tiến độ nào</h5>
                        <p class="text-muted mb-0">Giảng viên chưa tạo mốc tiến độ cho đề tài của bạn</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===================== BÁO CÁO TỔNG KẾT / KHÁC ===================== --}}
    <div class="mb-4">
        <h3 class="fw-bold text-primary mb-4">
            <i class="fas fa-file-alt me-3"></i>Báo Cáo Tổng Kết / Khác
        </h3>

        <!-- Deadline Info -->
        @if($deadline)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="deadline-icon me-3">
                        <i class="far fa-calendar-check fa-3x text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">Hạn nộp báo cáo chung</h6>
                        <p class="mb-0 text-muted">
                            <i class="far fa-clock me-2"></i>{{ date('d/m/Y H:i', strtotime($deadline)) }}
                        </p>
                        @if(\Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($deadline)))
                            <span class="badge bg-danger-subtle text-danger mt-2 px-3 py-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>ĐÃ QUÁ HẠN
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success mt-2 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Đang trong thời hạn
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Submit Form -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                @php
                    $isLate = $deadline && now()->greaterThan(\Carbon\Carbon::parse($deadline));
                    $isAllowedLate = $lastReport && $lastReport->TrangThai === 'Được nộp bổ sung';
                    $isPendingLate = $lastReport && $lastReport->TrangThai === 'Xin nộp bổ sung';
                @endphp

                @if($isReportApproved)
                    <div class="alert alert-success border-0 d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1 fw-bold">Báo cáo đã được duyệt!</h6>
                            <p class="mb-0">Bạn đã hoàn thành báo cáo này. Không cần nộp thêm.</p>
                        </div>
                    </div>
                @elseif($isPendingLate)
                    <div class="alert alert-warning border-0 d-flex align-items-center">
                        <i class="fas fa-hourglass-split fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1 fw-bold">Yêu cầu nộp bổ sung đang chờ duyệt</h6>
                            <p class="mb-0">Vui lòng đợi giảng viên/cán bộ duyệt yêu cầu của bạn.</p>
                        </div>
                    </div>
                @elseif(!$isLate || $isAllowedLate)
                    <form method="POST" action="{{ route('sinhvien.baocao.nop') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-file-upload text-primary me-2"></i>
                                Nộp file báo cáo chung (nếu được yêu cầu)
                            </label>
                            <input type="file" name="FileBC" class="form-control shadow-sm" accept=".pdf,.doc,.docx">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Định dạng: PDF, DOC, DOCX
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-file-code text-primary me-2"></i>
                                Nộp file Source Code (nếu có)
                            </label>
                            <input type="file" name="FileCode" class="form-control shadow-sm" accept=".zip,.rar,.7z">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Định dạng: ZIP, RAR, 7Z
                            </small>
                        </div>
                        <button class="btn btn-{{ $isAllowedLate ? 'primary' : 'success' }} px-4">
                            <i class="fas fa-paper-plane me-2"></i>{{ $isAllowedLate ? 'Nộp bổ sung' : 'Nộp báo cáo chung' }}
                        </button>
                    </form>
                @else
                    <div class="alert alert-danger border-0 d-flex align-items-center mb-3">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1 fw-bold">Đã quá hạn nộp báo cáo!</h6>
                            <p class="mb-0">Bạn cần xin phép nộp bổ sung bằng cách nhấn nút bên dưới.</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('sinhvien.baocao.requestLate') }}">
                        @csrf
                        <button class="btn btn-warning px-4">
                            <i class="fas fa-question-circle me-2"></i>Xin nộp bổ sung
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- History -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-history me-2 text-primary"></i>Lịch sử nộp báo cáo chung
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Lần nộp</th>
                                <th class="px-4 py-3">File Báo cáo</th>
                                <th class="px-4 py-3">File Code</th>
                                <th class="px-4 py-3">Ngày nộp</th>
                                <th class="px-4 py-3">Trạng thái</th>
                                <th class="px-4 py-3">Nhận xét</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($baoCao as $b)
                            <tr>
                                <td class="px-4">
                                    <span class="badge bg-primary-subtle text-primary">Lần {{ $b->LanNop }}</span>
                                </td>
                                <td class="px-4">
                                    @if($b->fileBaoCao)
                                        @php
                                            $pathBaoCao = str_replace('\\', '/', $b->fileBaoCao->path);
                                            $urlBaoCao = Str::startsWith($pathBaoCao, 'storage/') ? asset($pathBaoCao) : asset('storage/' . $pathBaoCao);
                                        @endphp
                                        <a href="{{ $urlBaoCao }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>{{ $b->fileBaoCao->name ?? 'File báo cáo' }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4">
                                    @if($b->fileCode)
                                        @php
                                            $pathCode = str_replace('\\', '/', $b->fileCode->path);
                                            $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                                        @endphp
                                        <a href="{{ $urlCode }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-file-archive text-warning me-2"></i>{{ $b->fileCode->name ?? 'Source Code' }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>{{ date('d/m/Y H:i', strtotime($b->NgayNop)) }}
                                    </small>
                                </td>
                                <td class="px-4">
                                    @php
                                        $statusClass = match($b->TrangThai) {
                                            'Đã duyệt' => 'success',
                                            'Cần sửa' => 'warning',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} px-3 py-2">
                                        {{ $b->TrangThai }}
                                    </span>
                                </td>
                                <td class="px-4">{{ $b->NhanXet ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Chưa có báo cáo chung nào
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
/* Milestone Card Styles */
.milestone-card {
    transition: all 0.3s ease;
    border-left: 4px solid #0d6efd !important;
}

.milestone-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}

.milestone-icon-wrapper {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.milestone-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.milestone-meta {
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

/* Modal Styles */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Button Styles */
.btn {
    border-radius: 8px;
    font-weight: 500;
}

/* Table Styles */
.table-hover tbody tr:hover {
    background-color: #f8f9ff;
}
</style>
@endsection
