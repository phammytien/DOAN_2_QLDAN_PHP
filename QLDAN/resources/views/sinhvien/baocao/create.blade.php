@extends('layouts.sinhvien')
@section('title','Nộp báo cáo')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Nộp báo cáo</h4>
                </div>
                <div class="card-body">
                    @if($deTai)
                        <form action="{{ route('sinhvien.baocao.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Đề tài</label>
                                <input type="text" class="form-control" value="{{ $deTai->TenDeTai }}" readonly>
                                <input type="hidden" name="MaDeTai" value="{{ $deTai->MaDeTai }}">
                            </div>

                            @if($tienDo)
                                <div class="mb-3">
                                    <label class="form-label">Tiến độ</label>
                                    <input type="text" class="form-control" value="{{ $tienDo->TenTienDo }}" readonly>
                                    <input type="hidden" name="MaTienDo" value="{{ $tienDo->MaTienDo }}">
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">File báo cáo (PDF, DOC, DOCX)</label>
                                <input type="file" class="form-control" name="FileBC" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Tối đa 10MB</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">File code (ZIP, RAR, 7Z)</label>
                                <input type="file" class="form-control" name="FileCode" accept=".zip,.rar,.7z,.tar,.gz">
                                <small class="text-muted">Tối đa 20MB</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload me-2"></i>Nộp báo cáo
                                </button>
                                <a href="{{ route('sinhvien.baocao.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Bạn chưa được phân công đề tài.
                        </div>
                        <a href="{{ route('sinhvien.dashboard') }}" class="btn btn-primary">
                            Về trang chủ
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
