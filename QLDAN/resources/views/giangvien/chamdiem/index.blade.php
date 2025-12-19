{{-- @extends('layouts.giangvien')

@section('content')

<style>
    /* ===== STYLE CHUNG ===== */
    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .report-box {
        background: #f8f9ff;
        border: 1px solid #d9e2ff;
        padding: 12px 15px;
        border-radius: 8px;
    }

    .rating-box {
        border: 1px solid #cce0ff;
        padding: 15px;
        border-radius: 8px;
        background: #ffffff;
    }

    .table thead th {
        background: #0d6efd;
        color: white !important;
        font-size: 14px;
        white-space: nowrap;
        text-align: center;
    }

    .table td {
        vertical-align: middle !important;
        font-size: 14px;
    }

    input[name="Diem"] {
        width: 90px !important;
        text-align: center;
        font-weight: 600;
    }

    .btn-info {
        background: #57c4ff;
        border: none;
    }
    .btn-info:hover {
        background: #1db2ff;
    }

    tbody tr:hover {
        background: #f0f6ff;
    }
</style>

<div class="container">
    <h3 class="mb-4">Chấm điểm sinh viên</h3>

    {{-- Thông báo --}}
    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- =================== DANH SÁCH CHƯA CHẤM =================== --}}
    {{-- @foreach($detaiChuaCham as $dt)
        <div class="card mb-4">
            <div class="card-header bg-warning fw-bold">
                Đề tài: {{ $dt->TenDeTai }}
            </div>

            <div class="card-body">

                @foreach($dt->sinhviens as $sv)
                    @php
                        $r = $latestReports[$dt->MaDeTai][$sv->MaSV] ?? null;
                    @endphp --}}

                    {{-- ==== KHUNG BÁO CÁO (NẰM TRÊN) ==== --}}
                    {{-- <div class="report-box mb-3">
                        <h6 class="fw-bold mb-2">
                            {{ $sv->TenSV }} <span class="text-muted">({{ $sv->MaSV }})</span>
                        </h6>

                        <strong>Báo cáo mới nhất:</strong><br>

                        @if($r)
                            <a href="{{ asset($r->LinkFile) }}" target="_blank" class="btn btn-sm btn-info mt-2">
                                📄 Xem báo cáo
                            </a>
                            <div class="mt-1">
                                <small class="text-muted">{{ $r->TenFile }}</small>
                            </div>
                        @else
                            <span class="text-danger">Chưa nộp báo cáo</span>
                        @endif
                    </div> --}}

                    {{-- ==== KHUNG CHẤM ĐIỂM ==== --}}
                    {{-- <div class="rating-box mb-4">
                        <h6 class="fw-bold mb-3">Chấm điểm sinh viên</h6>

                        <form action="{{ route('giangvien.chamdiem.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="MaDeTai" value="{{ $dt->MaDeTai }}">
                            <input type="hidden" name="MaSV" value="{{ $sv->MaSV }}">

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Điểm</label>
                                    <input type="number" name="Diem" step="0.1" min="0" max="10"
                                           class="form-control" required>
                                </div>

                                <div class="col-md-7">
                                    <label class="form-label fw-semibold">Nhận xét</label>
                                    <input type="text" name="NhanXet" class="form-control">
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100">Lưu điểm</button>
                                </div>
                            </div>
                        </form>
                    </div>

                @endforeach

            </div>
        </div>
    @endforeach --}}



    {{-- =================== DANH SÁCH ĐÃ CHẤM =================== --}}
    {{-- <h4 class="mt-5 mb-3">Danh sách sinh viên đã chấm</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Tên sinh viên</th>
                <th>Đề tài</th>
                <th>Báo cáo</th>
                <th>Điểm TB</th>
                <th>Điểm</th>
                <th>Nhận xét</th>
                <th>Hành động</th>
            </tr>
        </thead> --}} 

        {{-- <tbody>
            @foreach($chamdiem as $cd)
                @php
                    $sv = $cd->sinhvien;
                    $dt = $cd->detai;
                    $r = $latestReports[$dt->MaDeTai][$sv->MaSV] ?? null;
                @endphp

                <tr>
                    <form action="{{ route('giangvien.chamdiem.update', $cd->MaCham) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <td>{{ $sv->MaSV }}</td>
                        <td>{{ $sv->TenSV }}</td>
                        <td>{{ $dt->TenDeTai }}</td>

                        <td>
                            @if($r)
                                <a href="{{ asset($r->LinkFile) }}" target="_blank"
                                   class="btn btn-sm btn-info">📄 Xem</a>
                                <div><small class="text-muted">{{ $r->TenFile }}</small></div>
                            @else
                                <span class="text-danger">Không có</span>
                            @endif
                        </td>

                        <td class="text-center">{{ $diemTrungBinh[$dt->MaDeTai][$sv->MaSV] ?? '-' }}</td>

                        <td>
                            <input type="number" name="Diem" value="{{ $cd->Diem }}"
                                   step="0.1" min="0" max="10" class="form-control text-center">
                        </td>

                        <td>
                            <input type="text" name="NhanXet" value="{{ $cd->NhanXet }}" class="form-control">
                        </td>

                        <td class="text-center">
                            <button class="btn btn-primary btn-sm">Cập nhật</button>
                        </td>
                    </form>
                </tr>

            @endforeach
        </tbody>
    </table>

</div>
@endsection --}} 


@extends('layouts.giangvien')

@section('content')

<style>
    /* ===== VARIABLES ===== */
    :root {
        --primary-blue: #0d6efd;
        --light-blue: #e7f1ff;
        --dark-blue: #0a58ca;
        --accent-blue: #57c4ff;
        --success-green: #198754;
        --warning-yellow: #ffc107;
        --border-color: #d9e2ff;
    }

    /* ===== GENERAL ===== */
    body {
        background: #f5f7fa;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-header h3 {
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* ===== ALERTS ===== */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        animation: fadeInScale 0.4s ease;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* ===== CARDS ===== */
    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.15);
        transform: translateY(-2px);
    }

    .card-header {
        background: linear-gradient(135deg, var(--warning-yellow) 0%, #ffb800 100%);
        color: #000;
        font-weight: 700;
        padding: 18px 25px;
        border: none;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
    }

    /* ===== REPORT BOX ===== */
    .report-box {
        background: linear-gradient(135deg, #f8f9ff 0%, #e7f1ff 100%);
        border: 2px solid var(--border-color);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .report-box:hover {
        border-color: var(--primary-blue);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
    }

    .report-box h6 {
        color: var(--dark-blue);
        font-size: 16px;
        margin-bottom: 12px;
    }

    /* ===== RATING BOX ===== */
    .rating-box {
        border: 2px solid var(--border-color);
        padding: 25px;
        border-radius: 12px;
        background: #ffffff;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .rating-box:hover {
        border-color: var(--accent-blue);
        box-shadow: 0 4px 12px rgba(87, 196, 255, 0.15);
    }

    .rating-box h6 {
        color: var(--primary-blue);
        font-size: 16px;
        border-bottom: 2px solid var(--light-blue);
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===== FORM CONTROLS ===== */
    .form-label {
        color: var(--dark-blue);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border: 2px solid #e0e7ff;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-size: 15px;
        min-height: 45px;
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        transform: translateY(-1px);
    }

    input[name="Diem"] {
        width: 100% !important;
        text-align: center;
        font-weight: 700;
        font-size: 20px;
        color: var(--primary-blue);
        min-height: 50px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        white-space: nowrap;
    }

    /* ===== BUTTONS ===== */
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        justify-content: center;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn:active {
        transform: translateY(0);
    }

    .btn-info {
        background: linear-gradient(135deg, var(--accent-blue) 0%, #1db2ff 100%);
        color: white;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #1db2ff 0%, #0099e6 100%);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-green) 0%, #146c43 100%);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #146c43 0%, #0d5132 100%);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--dark-blue) 0%, #084298 100%);
    }

    /* ===== TABLE ===== */
    .section-title {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        margin: 40px 0 20px 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 3px 10px rgba(13, 110, 253, 0.2);
    }

    .table-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        color: white !important;
        font-size: 14px;
        white-space: nowrap;
        text-align: center;
        padding: 15px 10px;
        border: none;
        font-weight: 600;
    }

    .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e7f1ff;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #e7f1ff 100%);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
    }

    .table td {
        vertical-align: middle !important;
        font-size: 14px;
        padding: 20px 12px;
        border: none;
    }

    .table td input.form-control {
        min-height: 45px;
        font-size: 15px;
    }

    .table td input[name="Diem"] {
        min-width: 100px;
        font-size: 20px;
        padding: 12px;
    }

    .table td input[name="NhanXet"] {
        min-height: 45px;
    }

    /* ===== BADGES & ICONS ===== */
    .badge-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        font-size: 18px;
    }

    .student-badge {
        background: var(--light-blue);
        color: var(--dark-blue);
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        margin-left: 8px;
    }

    .no-report {
        color: #dc3545;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
        }
        
        .card-body {
            padding: 15px;
        }
        
        .table-container {
            overflow-x: auto;
            padding: 15px;
        }
    }

    /* ===== ANIMATIONS ===== */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .btn-success:hover {
        animation: pulse 0.5s ease;
    }

    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-blue);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--dark-blue);
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h3>
            <span class="badge-icon">📝</span>
            Chấm điểm sinh viên
        </h3>
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- =================== DANH SÁCH CHƯA CHẤM =================== --}}
    @foreach($detaiChuaCham as $dt)
        <div class="card">
            <div class="card-header">
                <span class="badge-icon">📚</span>
                Đề tài: {{ $dt->TenDeTai }}
            </div>

            <div class="card-body">

                @foreach($dt->sinhviens as $sv)
                    @php
                        $r = $latestReports[$dt->MaDeTai][$sv->MaSV] ?? null;
                    @endphp

                    {{-- ==== KHUNG BÁO CÁO (NẰM TRÊN) ==== --}}
                    <div class="report-box">
                        <h6>
                            👤 {{ $sv->TenSV }}
                            <span class="student-badge">{{ $sv->MaSV }}</span>
                        </h6>

                        <strong>📄 Báo cáo mới nhất:</strong><br>

                        @if($r && ($r->fileBaoCao || $r->fileCode))
                            @if($r->fileBaoCao)
                                @php
                                    $pathBaoCao = str_replace('\\', '/', $r->fileBaoCao->path);
                                    $urlBaoCao = Str::startsWith($pathBaoCao, 'storage/') ? asset($pathBaoCao) : asset('storage/' . $pathBaoCao);
                                @endphp
                                <a href="{{ $urlBaoCao }}" target="_blank" class="btn btn-sm btn-info mt-2">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    Xem báo cáo
                                </a>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-paperclip"></i>
                                        {{ $r->fileBaoCao->name }}
                                    </small>
                                </div>
                            @endif
                            @if($r->fileCode)
                                @php
                                    $pathCode = str_replace('\\', '/', $r->fileCode->path);
                                    $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                                @endphp
                                <a href="{{ $urlCode }}" target="_blank" class="btn btn-sm btn-warning mt-2 text-dark">
                                    <i class="bi bi-file-code"></i>
                                    Xem Code
                                </a>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-paperclip"></i>
                                        {{ $r->fileCode->name }}
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="mt-2">
                                <span class="no-report">
                                    <i class="bi bi-x-circle"></i>
                                    Chưa nộp báo cáo
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- ==== KHUNG CHẤM ĐIỂM ==== --}}
                    <div class="rating-box">
                        <h6>
                            <i class="bi bi-star-fill"></i>
                            Chấm điểm sinh viên
                        </h6>

                        <form action="{{ route('giangvien.chamdiem.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="MaDeTai" value="{{ $dt->MaDeTai }}">
                            <input type="hidden" name="MaSV" value="{{ $sv->MaSV }}">

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">
                                        <i class="bi bi-calculator"></i>
                                        Điểm
                                    </label>
                                    <input type="number" name="Diem" step="0.1" min="0" max="10"
                                           class="form-control" required placeholder="0.0">
                                </div>

                                <div class="col-md-7">
                                    <label class="form-label">
                                        <i class="bi bi-chat-left-text"></i>
                                        Nhận xét
                                    </label>
                                    <input type="text" name="NhanXet" class="form-control" 
                                           placeholder="Nhập nhận xét về sinh viên...">
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-save"></i>
                                        Lưu điểm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                @endforeach

            </div>
        </div>
    @endforeach



    {{-- =================== DANH SÁCH ĐÃ CHẤM =================== --}}
    <div class="section-title">
        <span class="badge-icon">✅</span>
        Danh sách sinh viên đã chấm
    </div>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 100px;"><i class="bi bi-hash"></i> Mã SV</th>
                    <th style="width: 180px;"><i class="bi bi-person"></i> Tên sinh viên</th>
                    <th style="width: 200px;"><i class="bi bi-book"></i> Đề tài</th>
                    <th style="width: 110px;"><i class="bi bi-file-text"></i> Báo cáo</th>
                    <th style="width: 100px;"><i class="bi bi-list-check"></i> Điểm TB Tiến độ</th>
                    <th style="width: 120px;"><i class="bi bi-star"></i> Điểm BC Cuối</th>
                    <th style="width: auto;"><i class="bi bi-chat-quote"></i> Nhận xét</th>
                    <th style="width: 130px;"><i class="bi bi-gear"></i> Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach($chamdiem as $cd)
                    @php
                        $sv = $cd->sinhvien;
                        $dt = $cd->detai;
                        $r = $latestReports[$dt->MaDeTai][$sv->MaSV] ?? null;
                    @endphp

                    <tr>
                        <form action="{{ route('giangvien.chamdiem.update', $cd->MaCham) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <td class="fw-bold">{{ $sv->MaSV }}</td>
                            <td>{{ $sv->TenSV }}</td>
                            <td>{{ $dt->TenDeTai }}</td>

                            <td>
                                @if($r && ($r->fileBaoCao || $r->fileCode))
                                    @if($r->fileBaoCao)
                                        @php
                                            $pathBaoCao = str_replace('\\', '/', $r->fileBaoCao->path);
                                            $urlBaoCao = Str::startsWith($pathBaoCao, 'storage/') ? asset($pathBaoCao) : asset('storage/' . $pathBaoCao);
                                        @endphp
                                        <a href="{{ $urlBaoCao }}" target="_blank"
                                           class="btn btn-sm btn-info mb-1" title="{{ $r->fileBaoCao->name }}">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                            Báo cáo
                                        </a>
                                    @endif
                                    @if($r->fileCode)
                                        @php
                                            $pathCode = str_replace('\\', '/', $r->fileCode->path);
                                            $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                                        @endphp
                                        <a href="{{ $urlCode }}" target="_blank"
                                           class="btn btn-sm btn-warning text-dark" title="{{ $r->fileCode->name }}">
                                            <i class="bi bi-file-code"></i>
                                            Code
                                        </a>
                                    @endif
                                @else
                                    <span class="no-report">
                                        <i class="bi bi-x-circle"></i>
                                        Không có
                                    </span>
                                @endif
                            </td>

                            {{-- Điểm TB Tiến độ (chỉ hiển thị, không cho sửa) --}}
                            <td class="text-center">
                                <strong class="text-primary" style="font-size: 18px;">
                                    {{ number_format($diemTrungBinh[$dt->MaDeTai][$sv->MaSV] ?? 0, 1) }}
                                </strong>
                                <div class="small text-muted">40%</div>
                            </td>

                            {{-- Điểm Báo cáo Cuối (cho phép sửa) --}}
                            <td>
                                <input type="number" name="Diem" value="{{ $cd->Diem }}"
                                       step="0.1" min="0" max="10" class="form-control text-center"
                                       {{ $cd->DiemCuoi !== null ? 'disabled' : '' }}>
                                <div class="small text-muted text-center mt-1">60%</div>
                            </td>

                            <td>
                                <input type="text" name="NhanXet" value="{{ $cd->NhanXet }}" 
                                       class="form-control" placeholder="Nhập nhận xét..."
                                       {{ $cd->DiemCuoi !== null ? 'disabled' : '' }}>
                            </td>

                            <td class="text-center">
                                @if($cd->DiemCuoi !== null)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Đã duyệt
                                    </span>
                                @else
                                    <button class="btn btn-primary btn-sm">
                                        <i class="bi bi-arrow-repeat"></i>
                                        Cập nhật
                                    </button>
                                @endif
                            </td>
                        </form>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Bootstrap JS with animations -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Smooth scroll animation
    document.addEventListener('DOMContentLoaded', function() {
        // Fade in cards sequentially
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                
                let x = e.clientX - e.target.offsetLeft;
                let y = e.clientY - e.target.offsetTop;
                
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Highlight focused inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transition = 'all 0.3s ease';
            });
        });

        // Validate score input
        document.querySelectorAll('input[name="Diem"]').forEach(input => {
            input.addEventListener('input', function() {
                let value = parseFloat(this.value);
                if (value > 10) this.value = 10;
                if (value < 0) this.value = 0;
            });
        });

        // Table row animation on hover
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });
        });
    });
</script>

@endsection