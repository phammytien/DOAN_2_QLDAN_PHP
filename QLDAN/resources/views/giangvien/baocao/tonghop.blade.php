@extends('layouts.giangvien')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">
            <i class="bi bi-calculator me-2"></i>Tổng hợp điểm
        </h3>
        <a href="{{ route('giangvien.baocao.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Thông tin đề tài -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin đề tài</h5>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th width="20%">Đề tài:</th>
                    <td>{{ $deTai->TenDeTai }}</td>
                </tr>
                <tr>
                    <th>Sinh viên:</th>
                    <td>{{ $deTai->sinhViens->first()->TenSV ?? 'N/A' }} ({{ $maSV }})</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Bảng điểm tiến độ -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Chi tiết điểm tiến độ</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Đợt</th>
                            <th class="py-3">Nội dung</th>
                            <th class="py-3">Deadline</th>
                            <th class="py-3">Ngày nộp</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="py-3 text-end">Điểm gốc</th>
                            <th class="py-3 text-end pe-4">Điểm sau trừ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tiendos as $index => $td)
                            <tr>
                                <td class="ps-4">Đợt {{ $index + 1 }}</td>
                                <td>{{ $td->NoiDung }}</td>
                                <td>{{ $td->Deadline ? \Carbon\Carbon::parse($td->Deadline)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $td->NgayNop ? \Carbon\Carbon::parse($td->NgayNop)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($td->Deadline && $td->NgayNop && \Carbon\Carbon::parse($td->NgayNop)->gt(\Carbon\Carbon::parse($td->Deadline)))
                                        <span class="badge bg-danger">Nộp trễ</span>
                                    @else
                                        <span class="badge bg-success">Đúng hạn</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold">{{ number_format($td->Diem, 2) }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="fw-bold {{ $td->DiemSauTruTre < $td->Diem ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($td->DiemSauTruTre, 2) }}
                                    </span>
                                    @if($td->DiemSauTruTre < $td->Diem)
                                        <small class="text-muted">(-10%)</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Chưa có điểm tiến độ nào được chấm
                                </td>
                            </tr>
                        @endforelse
                        
                        @if($tiendos->isNotEmpty())
                            <tr class="table-light fw-bold">
                                <td colspan="6" class="ps-4 py-3">Trung bình điểm tiến độ:</td>
                                <td class="text-end pe-4 py-3">
                                    <span class="text-primary fs-5">{{ number_format($tiendos->avg('DiemSauTruTre'), 2) }}</span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form tính điểm tổng -->
    @if($tiendos->isNotEmpty())
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calculator-fill me-2"></i>Tính điểm tổng</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('giangvien.baocao.tinhDiemTong') }}" method="POST">
                    @csrf
                    <input type="hidden" name="MaDeTai" value="{{ $deTai->MaDeTai }}">
                    <input type="hidden" name="MaSV" value="{{ $maSV }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Điểm tiến độ (40%)</label>
                                <input type="text" class="form-control form-control-lg bg-light" 
                                       value="{{ number_format($tiendos->avg('DiemSauTruTre') * 0.4, 2) }}" 
                                       readonly>
                                <small class="text-muted">Trung bình: {{ number_format($tiendos->avg('DiemSauTruTre'), 2) }} × 40%</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Điểm báo cáo cuối (0-10)</label>
                                <input type="number" name="DiemBaoCaoCuoi" id="diemBaoCaoCuoi" 
                                       class="form-control form-control-lg" 
                                       min="0" max="10" step="0.1" 
                                       value="{{ old('DiemBaoCaoCuoi', $baocao->DiemBaoCaoCuoi ?? '') }}" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h5 class="fw-bold mb-3">Công thức tính điểm:</h5>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded">
                                    <div class="text-muted small">Điểm tiến độ (40%)</div>
                                    <div class="fs-4 fw-bold text-primary" id="displayDiemTienDo">
                                        {{ number_format($tiendos->avg('DiemSauTruTre') * 0.4, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-center justify-content-center">
                                <i class="bi bi-plus-lg fs-3"></i>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded">
                                    <div class="text-muted small">Điểm BC cuối (60%)</div>
                                    <div class="fs-4 fw-bold text-success" id="displayDiemBaoCao">0.00</div>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-center justify-content-center">
                                <i class="bi bi-arrow-right fs-3"></i>
                            </div>
                            <div class="col-md-2">
                                <div class="p-3 bg-white rounded">
                                    <div class="text-muted small">Tổng điểm</div>
                                    <div class="fs-3 fw-bold text-danger" id="displayDiemTong">0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-send me-2"></i>Gửi Admin duyệt điểm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const diemTienDo = {{ $tiendos->avg('DiemSauTruTre') * 0.4 }};
    const diemBaoCaoInput = document.getElementById('diemBaoCaoCuoi');
    
    function updateTongDiem() {
        const diemBaoCaoGoc = parseFloat(diemBaoCaoInput.value) || 0;
        const diemBaoCao = diemBaoCaoGoc * 0.6;
        const tongDiem = diemTienDo + diemBaoCao;
        
        document.getElementById('displayDiemBaoCao').textContent = diemBaoCao.toFixed(2);
        document.getElementById('displayDiemTong').textContent = tongDiem.toFixed(2);
    }
    
    diemBaoCaoInput.addEventListener('input', updateTongDiem);
    
    // Initial calculation if there's a value
    if (diemBaoCaoInput.value) {
        updateTongDiem();
    }
});
</script>
@endsection
