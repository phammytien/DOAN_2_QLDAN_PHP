@extends('layouts.giangvien')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">
            <i class="bi bi-clipboard-check me-2"></i>Chấm điểm tiến độ
        </h3>
        <a href="{{ route('giangvien.tiendo.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Thông tin tiến độ -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin tiến độ</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Đề tài:</th>
                            <td>{{ $tiendo->deTai->TenDeTai }}</td>
                        </tr>
                        <tr>
                            <th>Nội dung:</th>
                            <td>{{ $tiendo->NoiDung }}</td>
                        </tr>
                        <tr>
                            <th>Deadline:</th>
                            <td>
                                @if($tiendo->Deadline)
                                    {{ \Carbon\Carbon::parse($tiendo->Deadline)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày nộp:</th>
                            <td>
                                @if($tiendo->NgayNop)
                                    {{ \Carbon\Carbon::parse($tiendo->NgayNop)->format('d/m/Y H:i') }}
                                    @if($tiendo->Deadline && \Carbon\Carbon::parse($tiendo->NgayNop)->gt(\Carbon\Carbon::parse($tiendo->Deadline)))
                                        <span class="badge bg-danger ms-2">Nộp trễ</span>
                                    @else
                                        <span class="badge bg-success ms-2">Đúng hạn</span>
                                    @endif
                                @else
                                    <span class="text-muted">Chưa nộp</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>File đính kèm:</th>
                            <td>
                                @if($tiendo->fileCode)
                                    @php
                                        $pathCode = str_replace('\\', '/', $tiendo->fileCode->path);
                                        $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                                    @endphp
                                    <a href="{{ $urlCode }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Xem file báo cáo
                                    </a>
                                @elseif($tiendo->LinkFile)
                                    <a href="{{ asset($tiendo->LinkFile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Xem file báo cáo
                                    </a>
                                @else
                                    <span class="text-muted">Không có file</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form chấm điểm -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Chấm điểm
                        @if($sinhVien ?? false)
                            - {{ $sinhVien->HoTen ?? $sinhVien->TenSV }}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('giangvien.tiendo.luuDiem', $tiendo->MaTienDo) }}{{ isset($sinhVien) ? '?sv=' . $sinhVien->MaSV : '' }}" method="POST">
                        @csrf
                        
                        @if(isset($sinhVien))
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Đang chấm điểm cho sinh viên: <strong>{{ $sinhVien->HoTen ?? $sinhVien->TenSV }}</strong>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Điểm (0-10)</label>
                            <input type="number" name="Diem" class="form-control form-control-lg" 
                                   min="0" max="10" step="0.1" 
                                   value="{{ old('Diem', $chamDiem->Diem ?? $tiendo->Diem) }}" 
                                   required>
                        </div>

                        @if($tiendo->Deadline && $tiendo->NgayNop && \Carbon\Carbon::parse($tiendo->NgayNop)->gt(\Carbon\Carbon::parse($tiendo->Deadline)))
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Nộp trễ!</strong> Điểm sẽ tự động trừ 10%
                                <div class="mt-2" id="diemSauTru" style="display:none;">
                                    <strong>Điểm sau trừ:</strong> <span id="diemTruValue" class="text-danger fw-bold"></span>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nhận xét</label>
                            <textarea name="NhanXet" class="form-control" rows="5" 
                                      placeholder="Nhập nhận xét về tiến độ...">{{ old('NhanXet', $chamDiem->NhanXet ?? $tiendo->NhanXet) }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Lưu điểm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const diemInput = document.querySelector('input[name="Diem"]');
    const isLate = {{ $tiendo->Deadline && $tiendo->NgayNop && \Carbon\Carbon::parse($tiendo->NgayNop)->gt(\Carbon\Carbon::parse($tiendo->Deadline)) ? 'true' : 'false' }};
    
    if (isLate && diemInput) {
        diemInput.addEventListener('input', function() {
            const diem = parseFloat(this.value);
            if (!isNaN(diem)) {
                const diemSauTru = diem * 0.9;
                document.getElementById('diemSauTru').style.display = 'block';
                document.getElementById('diemTruValue').textContent = diemSauTru.toFixed(2);
            } else {
                document.getElementById('diemSauTru').style.display = 'none';
            }
        });
        
        // Trigger on page load if there's already a value
        if (diemInput.value) {
            diemInput.dispatchEvent(new Event('input'));
        }
    }
});
</script>
@endsection
