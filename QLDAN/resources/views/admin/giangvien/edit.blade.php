@extends('layouts.admin')

@section('title', 'Sửa Giảng viên')

@section('content')
<div class="container mt-3">
    <h3>Chỉnh sửa Giảng viên: {{ $gv->MaGV }}</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.giangvien.update', $gv->MaGV) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Họ & Tên</label>
            <input type="text" name="TenGV" class="form-control" value="{{ old('TenGV', $gv->TenGV) }}" required>
        </div>
<div class="mb-3">
    <label class="form-label">Ngày sinh</label>
    <input type="date" name="NgaySinh" class="form-control" 
           value="{{ old('NgaySinh', $gv->NgaySinh ? $gv->NgaySinh->format('Y-m-d') : '') }}">
</div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">CCCD</label>
                <input type="text" name="MaCCCD" class="form-control" value="{{ old('MaCCCD', $gv->MaCCCD) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">SĐT</label>
                <input type="text" name="SDT" class="form-control" value="{{ old('SDT', $gv->SDT) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="Email" class="form-control" value="{{ old('Email', $gv->Email) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Khoa</label>
                <select name="MaKhoa" class="form-select">
                    <option value="">-- Chọn Khoa --</option>
                    @foreach($khoas as $khoa)
                        <option value="{{ $khoa->MaKhoa }}" {{ old('MaKhoa', $gv->MaKhoa) == $khoa->MaKhoa ? 'selected' : '' }}>
                            {{ $khoa->TenKhoa }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Chuyên ngành</label>
                <select name="MaNganh" class="form-select">
                    <option value="">-- Chọn Ngành --</option>
                    @foreach($nganhs as $nganh)
                        <option value="{{ $nganh->MaNganh }}" {{ old('MaNganh', $gv->MaNganh) == $nganh->MaNganh ? 'selected' : '' }}>
                            {{ $nganh->TenNganh }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Năm học</label>
                <select name="MaNamHoc" class="form-select">
                    <option value="">-- Chọn Năm học --</option>
                    @foreach($namhocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}" {{ old('MaNamHoc', $gv->MaNamHoc) == $nh->MaNamHoc ? 'selected' : '' }}>
                            {{ $nh->TenNamHoc }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Học vị</label>
            <input type="text" name="HocVi" class="form-control" value="{{ old('HocVi', $gv->HocVi) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Học hàm</label>
            <input type="text" name="HocHam" class="form-control" value="{{ old('HocHam', $gv->HocHam) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Chuyên ngành (chi tiết)</label>
            <input type="text" name="ChuyenNganh" class="form-control" value="{{ old('ChuyenNganh', $gv->ChuyenNganh) }}">
        </div>

        <!-- {{-- Hidden: Tài khoản liên kết (auto-managed by system)
        <div class="mb-3">
            <label class="form-label">Mã Tài Khoản liên kết</label>
            <select name="MaTK" class="form-select">
                <option value="">-- Không liên kết --</option>
                @foreach($taikhoans as $tk)
                    <option value="{{ $tk->MaTK }}" {{ old('MaTK', $gv->MaTK) == $tk->MaTK ? 'selected' : '' }}>
                        {{ $tk->MaTK }} / {{ $tk->MaSo }}
                    </option>
                @endforeach
            </select>
        </div>
        --}} -->

        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.giangvien.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
