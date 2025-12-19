@extends('layouts.admin')

@section('title', 'Thêm Cán bộ')

@section('content')
<div class="container mt-3">
    <h3>Thêm Cán bộ mới</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.canbo.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Mã cán bộ <span class="text-danger">*</span></label>
                <input type="text" name="MaCB" class="form-control" value="{{ old('MaCB') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Họ & Tên <span class="text-danger">*</span></label>
                <input type="text" name="TenCB" class="form-control" value="{{ old('TenCB') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Giới tính</label>
                <select name="GioiTinh" class="form-select">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="NgaySinh" class="form-control" value="{{ old('NgaySinh') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">CCCD</label>
                <input type="text" name="MaCCCD" class="form-control" value="{{ old('MaCCCD') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">SĐT</label>
                <input type="text" name="SDT" class="form-control" value="{{ old('SDT') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="Email" class="form-control" value="{{ old('Email') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Tôn giáo</label>
                <input type="text" name="TonGiao" class="form-control" value="{{ old('TonGiao') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Dân tộc</label>
                <input type="text" name="DanToc" class="form-control" value="{{ old('DanToc') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Học vị</label>
                <input type="text" name="HocVi" class="form-control" value="{{ old('HocVi') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Học hàm</label>
                <input type="text" name="HocHam" class="form-control" value="{{ old('HocHam') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Chuyên ngành</label>
                <input type="text" name="ChuyenNganh" class="form-control" value="{{ old('ChuyenNganh') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nơi sinh</label>
                <input type="text" name="NoiSinh" class="form-control" value="{{ old('NoiSinh') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Hộ khẩu thường trú</label>
                <input type="text" name="HKTT" class="form-control" value="{{ old('HKTT') }}">
            </div>

            {{-- Hidden: Tài khoản liên kết (auto-managed by system)
            <div class="col-md-12 mb-3">
                <label class="form-label">Tài khoản liên kết</label>
                <select name="MaTK" class="form-select">
                    <option value="">-- Không liên kết --</option>
                    @foreach($taikhoans as $tk)
                        <option value="{{ $tk->MaTK }}">{{ $tk->MaSo }} ({{ $tk->name ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>
            --}}
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.canbo.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
