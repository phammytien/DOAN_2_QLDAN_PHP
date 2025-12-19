@extends('layouts.admin')
@section('title','Chi tiết chấm điểm')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">📋 Chi tiết chấm điểm</h2>

    <div class="card mb-4">
        <div class="card-body">

            <div class="mb-3">
                <label class="fw-bold">Đề tài:</label>
                <input type="text" class="form-control" value="{{ $cd->detai->TenDeTai }}" disabled>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Sinh viên:</label>
                <input type="text" class="form-control" value="{{ $cd->sinhvien->TenSV }}" disabled>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            Chi tiết điểm từ giảng viên
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th width="15%">Vai trò</th>
                        <th width="25%">Giảng viên</th>
                        <th width="10%">Điểm</th>
                        <th>Nhận xét</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- LIỆT KÊ TẤT CẢ GIẢNG VIÊN CHẤM --}}
                    @foreach($listGV as $gv)
                    <tr>
                        <td class="fw-bold">{{ $gv->VaiTroDisplay ?? 'N/A' }}</td>
                        <td>{{ $gv->giangVien->TenGV }}</td>
                        <td>{{ number_format($gv->Diem,2) }}</td>
                        <td>{{ $gv->NhanXet }}</td>
                    </tr>
                    @endforeach

                    {{-- Điểm trung bình --}}
                    <tr class="table-info fw-bold">
                        <td colspan="2" class="text-end">Điểm trung bình:</td>
                        <td colspan="2">
                            {{ $diemTB !== null ? number_format($diemTB,2) : '-' }}
                        </td>
                    </tr>

                    {{-- Điểm cuối --}}
                    <tr class="table-primary fw-bold">
                        <td colspan="2" class="text-end">Điểm cuối (sau duyệt):</td>
                        <td colspan="2">
                            {{ $cd->DiemCuoi !== null ? number_format($cd->DiemCuoi,2) : '-' }}
                        </td>
                    </tr>

                    {{-- Trạng thái --}}
                    <tr class="table-warning fw-bold">
                        <td colspan="2" class="text-end">Trạng thái:</td>
                        <td colspan="2">{{ $cd->TrangThai }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('admin.chamdiem.index') }}" class="btn btn-secondary mt-3">
        ↩ Quay lại
    </a>

</div>
@endsection
