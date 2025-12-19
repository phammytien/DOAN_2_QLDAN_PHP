@extends('layouts.admin')

@section('title', 'Chi tiết Giảng viên')

@section('content')
<div class="container mt-3">
    <h3>Chi tiết Giảng viên: {{ $gv->TenGV }} ({{ $gv->MaGV }})</h3>

    <div class="row mt-3">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                    <tr><th>Mã GV</th><td>{{ $gv->MaGV }}</td></tr>
                    <tr><th>Họ tên</th><td>{{ $gv->TenGV }}</td></tr>
                    <tr><th>Giới tính</th><td>{{ $gv->GioiTinh ?? '-' }}</td></tr>
                    <tr><th>Ngày sinh</th><td>{{ $gv->NgaySinh ?? '-' }}</td></tr>
                    <tr><th>Tôn giáo</th><td>{{ $gv->TonGiao ?? '-' }}</td></tr>
                    <tr><th>SĐT</th><td>{{ $gv->SDT ?? '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $gv->Email ?? '-' }}</td></tr>
                    <tr><th>Nơi sinh</th><td>{{ $gv->NoiSinh ?? '-' }}</td></tr>
                    <tr><th>Hộ khẩu thường trú</th><td>{{ $gv->HKTT ?? '-' }}</td></tr>
                    <tr><th>Dân tộc</th><td>{{ $gv->DanToc ?? '-' }}</td></tr>
                    <tr><th>Học vị</th><td>{{ $gv->HocVi ?? '-' }}</td></tr>
                    <tr><th>Học hàm</th><td>{{ $gv->HocHam ?? '-' }}</td></tr>
                    <tr><th>Chuyên ngành</th><td>{{ $gv->ChuyenNganh ?? '-' }}</td></tr>
                    <tr><th>CCCD</th><td>{{ $gv->MaCCCD ?? '-' }}</td></tr>
                    <tr><th>Tài khoản</th><td>{{ $gv->taiKhoan->MaSo ?? '-' }}</td></tr>
                </tbody>
            </table>
        </div>

        {{-- ======================== --}}
        {{--    THÔNG TIN ẨN / HIỆN   --}}
        {{-- ======================== --}}
        <div class="col-md-6">
            <h5 class="fw-bold">Thông tin liên quan</h5>

            {{-- CCCD --}}
            <p>
                <a class="fw-semibold text-primary" data-bs-toggle="collapse" href="#cccdInfo" role="button">
                    ▶ CCCD (chi tiết)
                </a>
            </p>
            <div class="collapse" id="cccdInfo">
                @if($gv->cccd)
                    <p><strong>Ngày cấp:</strong> {{ $gv->cccd->NgayCap ?? '-' }}</p>
                    <p><strong>Nơi cấp:</strong> {{ $gv->cccd->NoiCap ?? '-' }}</p>
                @else
                    <p>-</p>
                @endif
            </div>

            {{-- Danh sách đề tài --}}
            <p class="mt-3">
                <a class="fw-semibold text-primary" data-bs-toggle="collapse" href="#deTaiList" role="button">
                    ▶ Danh sách đề tài
                </a>
            </p>
            <div class="collapse" id="deTaiList">
                @if($gv->detais->count())
                    <ul>
                        @foreach($gv->detais as $detai)
                            <li>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#detaiModal{{ $detai->MaDeTai }}">
                                    {{ $detai->TenDeTai }} ({{ $detai->TrangThai ?? '-' }})
                                </a>
                            </li>

                            {{-- Modal đề tài --}}
                            <div class="modal fade" id="detaiModal{{ $detai->MaDeTai }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chi tiết đề tài</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Mã đề tài:</strong> {{ $detai->MaDeTai }}</p>
                                            <p><strong>Tên đề tài:</strong> {{ $detai->TenDeTai }}</p>
                                            <p><strong>Mô tả:</strong> {{ $detai->MoTa ?? '-' }}</p>
                                            <p><strong>Lĩnh vực:</strong> {{ $detai->LinhVuc ?? '-' }}</p>
                                            <p><strong>Năm học:</strong> {{ $detai->NamHoc ?? '-' }}</p>
                                            <p><strong>Trạng thái:</strong> {{ $detai->TrangThai ?? '-' }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </ul>
                @else
                    <p>-</p>
                @endif
            </div>

            {{-- Danh sách chấm điểm --}}
            <p class="mt-3">
                <a class="fw-semibold text-primary" data-bs-toggle="collapse" href="#chamDiemList" role="button">
                    ▶ Danh sách chấm điểm
                </a>
            </p>
            <div class="collapse" id="chamDiemList">
                @if($gv->chamdiems->count())
                    <ul>
                        @foreach($gv->chamdiems as $cd)
                            <li>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#chamdiemModal{{ $cd->id }}">
                                    Mã báo cáo: {{ $cd->MaBC ?? '-' }}, Điểm: {{ $cd->Diem ?? '-' }}
                                </a>
                            </li>

                            {{-- Modal chấm điểm --}}
                            <div class="modal fade" id="chamdiemModal{{ $cd->id }}" tabindex="-1">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chi tiết chấm điểm</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Mã báo cáo:</strong> {{ $cd->MaBC ?? '-' }}</p>
                                            <p><strong>Điểm:</strong> {{ $cd->Diem ?? '-' }}</p>
                                            <p><strong>Ghi chú:</strong> {{ $cd->GhiChu ?? '-' }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </ul>
                @else
                    <p>-</p>
                @endif
            </div>
        </div>
    </div>

    <a href="{{ route('admin.giangvien.index') }}" class="btn btn-secondary mt-2">Quay lại</a>
    <a href="{{ route('admin.giangvien.edit', $gv->MaGV) }}" class="btn btn-warning mt-2">Sửa</a>

</div>
@endsection
