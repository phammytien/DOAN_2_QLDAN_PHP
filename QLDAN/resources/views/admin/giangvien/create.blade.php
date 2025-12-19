@extends('layouts.admin')

@section('title', 'Thêm Giảng viên')

@section('content')
<div class="container mt-3">
    <h3>Thêm Giảng viên</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.giangvien.store') }}" method="POST">
        @csrf

        {{-- Họ tên --}}
        <div class="mb-3">
            <label class="form-label">Họ & Tên <span class="text-danger">*</span></label>
            <input type="text" name="TenGV" class="form-control" value="{{ old('TenGV') }}" required>
        </div>

        {{-- Ngày sinh --}}
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="NgaySinh" class="form-control" value="{{ old('NgaySinh') }}">
        </div>

        {{-- CCCD --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">CCCD</label>
                <input type="text" name="MaCCCD" class="form-control" value="{{ old('MaCCCD') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Ngày cấp CCCD</label>
                <input type="date" name="NgayCap" class="form-control" value="{{ old('NgayCap') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nơi cấp CCCD</label>
                <input type="text" name="NoiCap" class="form-control" value="{{ old('NoiCap') }}">
            </div>
        </div>

        {{-- Thông tin liên hệ --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">SĐT</label>
                <input type="text" name="SDT" class="form-control" value="{{ old('SDT') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="Email" class="form-control" value="{{ old('Email') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giới tính</label>
                <select name="GioiTinh" class="form-select">
                    <option value="Nam" {{ old('GioiTinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('GioiTinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>
        </div>

        {{-- Khoa - Ngành - Năm học --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Khoa</label>
                <input list="listKhoa" name="MaKhoa" class="form-control" placeholder="Chọn hoặc nhập khoa mới" value="{{ old('MaKhoa') }}">
                <datalist id="listKhoa">
                    @foreach($khoas as $khoa)
                        <option value="{{ $khoa->TenKhoa }}">
                    @endforeach
                </datalist>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Ngành</label>
                <input list="listNganh" name="MaNganh" class="form-control" placeholder="Chọn hoặc nhập ngành mới" value="{{ old('MaNganh') }}">
                <datalist id="listNganh">
                    @foreach($nganhs as $nganh)
                        <option value="{{ $nganh->TenNganh }}">
                    @endforeach
                </datalist>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Năm học</label>
                <select name="MaNamHoc" class="form-select">
                    <option value="">-- Chọn năm học --</option>
                    @foreach($namhocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}" {{ old('MaNamHoc') == $nh->MaNamHoc ? 'selected' : '' }}>
                            {{ $nh->TenNamHoc }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Chuyên ngành trong hồ sơ giảng viên --}}
        <div class="mb-3">
            <label class="form-label">Chuyên ngành hồ sơ</label>
            <input type="text" name="ChuyenNganh" class="form-control" value="{{ old('ChuyenNganh') }}">
        </div>

        {{-- Mã tài khoản liên kết --}}
        {{-- Hidden: Tài khoản liên kết (auto-managed by system)
        <div class="mb-3">
            <label class="form-label">Mã Tài Khoản liên kết</label>
            <select name="MaTK" class="form-select">
                <option value="">-- Không liên kết --</option>
                @foreach($taikhoans as $tk)
                    <option value="{{ $tk->MaTK }}" {{ old('MaTK') == $tk->MaTK ? 'selected' : '' }}>
                        {{ $tk->MaTK }} / {{ $tk->MaSo }}
                    </option>
                @endforeach
            </select>
        </div>
        --}}

        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.giangvien.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
