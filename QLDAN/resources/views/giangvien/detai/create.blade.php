@extends('layouts.giangvien')

@section('content')
<div class="container mt-4">
    <h3 class="text-primary mb-3">📝 Thêm đề tài mới</h3>

    <form action="{{ route('giangvien.detai.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên đề tài</label>
            <input type="text" name="TenDeTai" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lĩnh vực</label>
            <select name="LinhVuc" class="form-select" required>
                <option value="">-- Chọn lĩnh vực --</option>
                @foreach($linhVucs as $lv)
                    <option value="{{ $lv }}">{{ $lv }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Loại đề tài</label>
            <select name="LoaiDeTai" class="form-select" required>
                <option value="">-- Chọn loại đề tài --</option>
                @foreach($loaiDeTais as $loai)
                    <option value="{{ $loai }}">{{ $loai }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Năm học</label>
            <select name="MaNamHoc" class="form-select" required>
                <option value="">-- Chọn năm học --</option>
                @foreach($namHocs as $nh)
                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3"></textarea>
        </div>

        <button class="btn btn-success">💾 Lưu đề tài</button>
        <a href="{{ route('giangvien.detai.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
    </form>
</div>
@endsection
