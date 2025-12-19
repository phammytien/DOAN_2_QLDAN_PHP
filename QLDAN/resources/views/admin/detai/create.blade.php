@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4 class="text-success mb-3">➕ Thêm đề tài mới</h4>

    <form action="{{ route('admin.detai.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên đề tài</label>
            <input type="text" name="TenDeTai" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3"></textarea>
        </div>

        <div class="row">
            {{-- Lĩnh vực (Lấy từ bảng NGÀNH) --}}
            <div class="col-md-4 mb-3">
                <label>Lĩnh vực (Ngành)</label>
                <select name="LinhVuc" class="form-select" required>
                    <option value="">-- Chọn lĩnh vực --</option>
                    @foreach($nganhs as $nganh)
                        <option value="{{ $nganh->TenNganh }}">{{ $nganh->TenNganh }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Năm học --}}
            <div class="col-md-4 mb-3">
                <label>Năm học</label>
                <select name="MaNamHoc" class="form-select" required>
                    <option value="">-- Chọn năm học --</option>
                    @foreach($namHocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Loại đề tài --}}
            <div class="col-md-4 mb-3">
                <label>Loại đề tài</label>
                <select name="LoaiDeTai" class="form-select">
                    <option value="Cá nhân">Cá nhân</option>
                    <option value="Nhóm">Nhóm</option>
                </select>
            </div>
        </div>

        <div class="row">
            {{-- Giảng viên hướng dẫn --}}
            <div class="col-md-6 mb-3">
                <label>Giảng viên hướng dẫn</label>
                <select name="MaGV" class="form-select">
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($gvs as $gv)
                        <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Cán bộ quản lý --}}
            <div class="col-md-6 mb-3">
                <label>Cán bộ quản lý</label>
                <select name="MaCB" class="form-select">
                    <option value="">-- Chọn cán bộ --</option>
                    @foreach($cbs as $cb)
                        <option value="{{ $cb->MaCB }}">{{ $cb->TenCB }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('admin.detai.index') }}" class="btn btn-secondary">Quay lại</a>
            <button class="btn btn-primary">Lưu</button>
        </div>
    </form>
</div>
@endsection
