@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Thêm phân công</h2>

    <form action="{{ route('admin.phancong.store') }}" method="POST">
        @csrf

        {{-- Đề tài --}}
        <div class="form-group mb-3">
            <label>Đề tài</label>
            <select name="MaDeTai" class="form-control" required>
                <option value="">-- Chọn đề tài --</option>
                @foreach($detais as $dt)
                    <option value="{{ $dt->MaDeTai }}">{{ $dt->TenDeTai }}</option>
                @endforeach
            </select>
        </div>

        {{-- Giảng viên --}}
        <div class="form-group mb-3">
            <label>Giảng viên</label>
            <select name="MaGV" class="form-control" required>
                <option value="">-- Chọn giảng viên --</option>
                @foreach($giangviens as $gv)
                    <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }} ({{ $gv->HocHam ?? '' }})</option>
                @endforeach
            </select>
        </div>

        {{-- Cán bộ phân công --}}
        <div class="form-group mb-3">
            <label>Cán bộ phân công</label>
            <select name="MaCB" class="form-control" required>
                <option value="">-- Chọn cán bộ --</option>
                @foreach($canbos as $cb)
                    <option value="{{ $cb->MaCB }}">{{ $cb->TenCB }}</option>
                @endforeach
            </select>
        </div>

        {{-- Vai trò (lấy từ cơ sở dữ liệu nếu có bảng VaiTroGV) --}}
        <div class="form-group mb-3">
            <label>Vai trò</label>
            <select name="VaiTro" class="form-control" required>
                <option value="">-- Chọn vai trò --</option>
                @foreach($vaiTros as $vaiTro)
                    <option value="{{ $vaiTro }}">{{ $vaiTro }}</option>
                @endforeach
            </select>
        </div>

        {{-- Ghi chú --}}
        <div class="form-group mb-3">
            <label>Ghi chú</label>
            <textarea name="GhiChu" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.phancong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
