@extends('layouts.sinhvien')
@section('content')
<div class="container">
    <h2>Chỉnh sửa Profile</h2>
    <form action="{{ route('sinhvien.profile.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="TenSV" value="{{ old('TenSV', $sinhvien->TenSV ?? '') }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>SDT</label>
            <input type="text" name="SDT" value="{{ old('SDT', $sinhvien->SDT ?? '') }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="Email" value="{{ old('Email', $sinhvien->Email ?? '') }}" class="form-control">
        </div>
        <button class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection
