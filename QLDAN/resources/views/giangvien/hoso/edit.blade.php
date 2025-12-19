@extends('layouts.giangvien')
@section('title', 'Cập nhật hồ sơ')
@section('content')
<form method="POST" action="{{ route('giangvien.hoso.update') }}">
    @csrf
    <div class="mb-3"><label>Họ tên</label><input name="TenGV" value="{{ $hoso->TenGV }}" class="form-control"></div>
    <div class="mb-3"><label>Email</label><input name="Email" value="{{ $hoso->Email }}" class="form-control"></div>
    <div class="mb-3"><label>Điện thoại</label><input name="SDT" value="{{ $hoso->SDT }}" class="form-control"></div>
    <div class="mb-3"><label>Học vị</label><input name="HocVi" value="{{ $hoso->HocVi }}" class="form-control"></div>
    <div class="mb-3"><label>Học hàm</label><input name="HocHam" value="{{ $hoso->HocHam }}" class="form-control"></div>
    <div class="mb-3"><label>Chuyên ngành</label><input name="ChuyenNganh" value="{{ $hoso->ChuyenNganh }}" class="form-control"></div>
    <button class="btn btn-success">Lưu thay đổi</button>
</form>
@endsection
