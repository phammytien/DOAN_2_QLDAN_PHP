@extends('layouts.admin')


@section('content')
<div class="container mt-4">
    <h3 class="mb-3 text-info">📑 Đề Tài & Kết Quả - {{ $sv->TenSV }} ({{ $sv->MaSV }})</h3>

    <a href="{{ route('admin.sinhvien.index') }}" class="btn btn-secondary mb-3">⬅ Quay lại</a>

    {{-- Nếu sinh viên có đề tài --}}
    @if($deTai)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Đề tài:</strong> {{ $deTai->TenDeTai }}
            </div>
            <div class="card-body">
                <p><strong>Lĩnh vực:</strong> {{ $deTai->LinhVuc ?? '-' }}</p>
                <p><strong>Năm học:</strong> {{ $deTai->NamHoc }}</p>
                <p><strong>Trạng thái:</strong> 
                    <span class="badge bg-{{ $deTai->TrangThai == 'Hoàn thành' ? 'success' : ($deTai->TrangThai == 'Đang thực hiện' ? 'warning' : 'secondary') }}">
                        {{ $deTai->TrangThai }}
                    </span>
                </p>
                <p><strong>Giảng viên hướng dẫn:</strong> {{ $deTai->giangVien->TenGV ?? 'Chưa có' }}</p>
            </div>
        </div>

        {{-- Tiến độ / Báo cáo --}}
        <h5 class="text-primary mb-2">📄 Báo cáo đã nộp</h5>
        @if($baoCaos->count() > 0)
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tên file</th>
                        <th>Ngày nộp</th>
                        <th>Lần nộp</th>
                        <th>Nhận xét</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($baoCaos as $bc)
                        <tr>
                            <td>
                                @php
                                    // Chuẩn hóa đường dẫn: chuyển \ thành / và loại bỏ dấu / ở đầu
                                    $pathBaoCao = str_replace('\\', '/', $bc->LinkFile);
                                    $pathBaoCao = ltrim($pathBaoCao, '/');
                                    // Sử dụng asset() trực tiếp
                                    $urlBaoCao = asset($pathBaoCao);
                                @endphp
                                <a href="{{ $urlBaoCao }}" target="_blank">{{ $bc->TenFile }}</a>
                            </td>
                            <td>{{ $bc->NgayNop }}</td>
                            <td>{{ $bc->LanNop }}</td>
                            <td>{{ $bc->NhanXet ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Chưa có báo cáo nào.</p>
        @endif

        {{-- Điểm chấm --}}
        <h5 class="text-primary mt-4 mb-2">⭐ Điểm chấm</h5>
        @if($diems->count() > 0)
            @php
                // Tính điểm trung bình tiến độ (các điểm có MaTienDo)
                $diemTienDo = $diems->whereNotNull('MaTienDo');
                $diemTBTienDo = $diemTienDo->avg('Diem');
                
                // Tính điểm trung bình báo cáo cuối (các điểm không có MaTienDo)
                $diemBaoCaoCuoi = $diems->whereNull('MaTienDo');
                $diemTBBaoCao = $diemBaoCaoCuoi->avg('Diem');
                
                // Lấy điểm tổng (DiemCuoi) từ bất kỳ record nào
                $diemCuoi = $diems->first()->DiemCuoi ?? null;
            @endphp
            
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Điểm TB Tiến độ (40%)</th>
                        <th>Điểm TB Báo cáo cuối (60%)</th>
                        <th>Điểm Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong class="text-primary">{{ $diemTBTienDo ? number_format($diemTBTienDo, 2) : '-' }}</strong></td>
                        <td><strong class="text-warning">{{ $diemTBBaoCao ? number_format($diemTBBaoCao, 2) : '-' }}</strong></td>
                        <td><strong class="text-danger">{{ $diemCuoi ? number_format($diemCuoi, 2) : '-' }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p class="text-muted">Chưa có điểm chấm.</p>
        @endif
    @else
        <p class="text-muted">Sinh viên này chưa đăng ký đề tài.</p>
    @endif
</div>
@endsection
