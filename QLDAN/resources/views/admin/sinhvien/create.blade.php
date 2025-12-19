@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3 class="text-success mb-3">➕ Thêm Sinh Viên</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi:</strong><br>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sinhvien.store') }}" method="POST">
        @csrf

        <div class="row">
            {{-- ❌ Bỏ nhập mã SV, vì hệ thống sẽ tự tạo --}}
            <div class="col-md-4 mb-3">
                <label>Tên sinh viên</label>
                <input type="text" name="TenSV" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Giới tính</label>
                <select name="GioiTinh" class="form-select">
                    <option value="">--Chọn--</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Ngày sinh</label>
                <input type="date" name="NgaySinh" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label>SĐT</label>
                <input type="text" name="SDT" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Email</label>
                <input type="email" name="Email" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Dân tộc</label>
                <input type="text" name="DanToc" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Tôn giáo</label>
                <input type="text" name="TonGiao" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Khoa</label>
                <select name="MaKhoa" class="form-select">
                    <option value="">--Chọn khoa--</option>
                    @foreach($khoas as $khoa)
                        <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>Ngành</label>
                <select name="MaNganh" class="form-select">
                    <option value="">--Chọn ngành--</option>
                    @foreach($nganhs as $nganh)
                        <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>Lớp</label>
                <select name="MaLop" class="form-select">
                    <option value="">--Chọn lớp--</option>
                    @foreach($lops as $lop)
                        <option value="{{ $lop->MaLop }}">{{ $lop->TenLop }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>Năm học</label>
                <select name="MaNamHoc" class="form-select" required>
                    <option value="">--Chọn năm học--</option>
                    @foreach($namhocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 🔰 Thêm TRẠNG THÁI --}}
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <option value="Đang học">Đang học</option>
                    <option value="Bảo lưu">Bảo lưu</option>
                    <option value="Tốt nghiệp">Tốt nghiệp</option>
                    <option value="Thôi học">Thôi học</option>
                </select>
            </div>
        </div>

        <div class="text-end mt-3">
            <button class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.sinhvien.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
