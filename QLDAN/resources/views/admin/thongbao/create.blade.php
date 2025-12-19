@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-3">📢 Tạo thông báo mới</h3>

    <form action="{{ route('admin.thongbao.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nội dung</label>
            <textarea name="NoiDung" class="form-control" required>{{ old('NoiDung') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Người đăng</label>
            <select name="MaCB" class="form-control">
                <option value="">-- Chọn cán bộ --</option>
                @foreach($cbs as $cb)
                    <option value="{{ $cb->MaCB }}">{{ $cb->TenCB }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Đối tượng nhận</label>
            <select name="DoiTuongNhan" class="form-control">
                <option value="TatCa">Tất cả</option>
                <option value="SV">Sinh viên</option>
                <option value="GV">Giảng viên</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Mức độ thông báo</label>
            <select name="MucDo" class="form-control">
                <option value="Khan">⚠️ Khẩn cấp</option>
                <option value="QuanTrong">📣 Quan trọng</option>
                <option value="BinhThuong">ℹ️ Bình thường</option>
            </select>
        </div>

        <div class="mb-3">
            <label>File đính kèm</label>
            <input type="file" name="TenFile" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Đăng thông báo</button>
    </form>

</div>
@endsection
