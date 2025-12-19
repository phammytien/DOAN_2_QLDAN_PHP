@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark">Chi tiết Tiến độ</h2>
        <a href="{{ route('admin.tiendo.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        {{-- Thông tin chung --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông tin chung
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Đề tài:</div>
                        <div class="col-md-8 fw-bold">{{ $t->deTai->TenDeTai ?? '—' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Sinh viên thực hiện:</div>
                        <div class="col-md-8">
                            @if($t->deTai && $t->deTai->sinhviens->count() > 0)
                                @foreach($t->deTai->sinhviens as $sv)
                                    <span class="badge bg-info text-dark me-1">{{ $sv->TenSV }}</span>
                                @endforeach
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Nội dung báo cáo:</div>
                        <div class="col-md-8">{{ $t->NoiDung ?? 'Chưa có nội dung' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Ghi chú:</div>
                        <div class="col-md-8 text-secondary fst-italic">{{ $t->GhiChu ?? 'Không có ghi chú' }}</div>
                    </div>
                </div>
            </div>

            {{-- File đính kèm --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-success">
                        <i class="fas fa-paperclip me-2"></i>Tài liệu đính kèm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        @if($t->fileBaoCao)
                            @php
                                $pathBaoCao = str_replace('\\', '/', $t->fileBaoCao->path);
                                $urlBaoCao = Str::startsWith($pathBaoCao, 'storage/') ? asset($pathBaoCao) : asset('storage/' . $pathBaoCao);
                            @endphp
                            <a href="{{ $urlBaoCao }}" class="btn btn-outline-primary" target="_blank">
                                <i class="fas fa-file-alt me-2"></i>File Báo cáo
                            </a>
                        @endif
                        
                        @if($t->fileCode)
                            @php
                                $pathCode = str_replace('\\', '/', $t->fileCode->path);
                                $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                            @endphp
                            <a href="{{ $urlCode }}" class="btn btn-outline-success" target="_blank">
                                <i class="fas fa-code me-2"></i>Source Code
                            </a>
                        @endif

                        @if(!$t->fileBaoCao && !$t->fileCode)
                            <span class="text-muted fst-italic">Không có file đính kèm</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Trạng thái & Thời gian --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-warning">
                        <i class="fas fa-clock me-2"></i>Thời gian & Trạng thái
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold">Trạng thái</label>
                        <div class="mt-1">
                            @php
                                $status = $t->TrangThaiTuDong;
                                $badgeClass = match($status) {
                                    'Chưa nộp' => 'bg-secondary',
                                    'Trễ hạn' => 'bg-danger',
                                    'Nộp đúng hạn' => 'bg-success',
                                    default => 'bg-light text-dark border'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6 px-3 py-2 rounded-pill">
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold">Deadline</label>
                        <div class="mt-1 fw-bold text-danger">
                            {{ $t->Deadline ? $t->Deadline->format('d/m/Y H:i') : 'Chưa thiết lập' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small text-uppercase fw-bold">Thời gian nộp</label>
                        <div class="mt-1 fw-bold text-primary">
                            {{ $t->NgayNop ? $t->NgayNop->format('d/m/Y H:i') : '—' }}
                        </div>
                    </div>

                    <hr>

                    <div class="mb-0">
                        <label class="text-muted small text-uppercase fw-bold">Cập nhật lần cuối</label>
                        <div class="mt-1 small text-secondary">
                            {{ $t->ThoiGianCapNhat ? \Carbon\Carbon::parse($t->ThoiGianCapNhat)->diffForHumans() : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
