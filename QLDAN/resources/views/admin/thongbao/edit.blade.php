@extends('layouts.admin')

@section('content')
<div class="container">

    <h3 class="mb-3">✏ Sửa thông báo</h3>

    <form action="{{ route('admin.thongbao.update', $tb->MaTB) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nội dung</label>
            <textarea name="NoiDung" class="form-control" required>{{ $tb->NoiDung }}</textarea>
        </div>

        <div class="mb-3">
            <label>Người đăng</label>
            <select name="MaCB" class="form-control">
                <option value="">--- Chọn cán bộ ---</option>
                @foreach($cbs as $cb)
                    <option value="{{ $cb->MaCB }}" 
                        {{ $cb->MaCB == $tb->MaCB ? 'selected' : '' }}>
                        {{ $cb->TenCB }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Đối tượng nhận</label>
            <select name="DoiTuongNhan" class="form-control">
                <option value="TatCa" {{ $tb->DoiTuongNhan == 'TatCa' ? 'selected' : '' }}>Tất cả</option>
                <option value="SV" {{ $tb->DoiTuongNhan == 'SV' ? 'selected' : '' }}>Sinh viên</option>
                <option value="GV" {{ $tb->DoiTuongNhan == 'GV' ? 'selected' : '' }}>Giảng viên</option>
            </select>
        </div>

        <!-- ✅ THÊM MỨC ĐỘ -->
        <div class="mb-3">
            <label>Mức độ thông báo</label>
            <select name="MucDo" class="form-control">
                <option value="Khan" {{ $tb->MucDo == 'Khan' ? 'selected' : '' }}>⚠️ Khẩn cấp</option>
                <option value="QuanTrong" {{ $tb->MucDo == 'QuanTrong' ? 'selected' : '' }}>📣 Quan trọng</option>
                <option value="BinhThuong" {{ $tb->MucDo == 'BinhThuong' ? 'selected' : '' }}>ℹ️ Bình thường</option>
            </select>
        </div>

        <div class="mb-3">
            <label>File đính kèm (nếu thay thế)</label>
            <input type="file" name="TenFile" class="form-control">

            @if($tb->TenFile)
                <p class="mt-2">File hiện tại:
                    <a href="{{ asset('storage/uploads/thongbao/' . $tb->TenFile) }}" target="_blank">
                        {{ $tb->TenFile }}
                    </a>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>

</div>
@endsection
