@extends('layouts.admin')

@section('content')
<h3>Sửa tài khoản</h3>

<form action="{{ route('admin.taikhoan.update', $taikhoan->MaTK) }}" method="POST">
    @csrf @method('PUT')

    <div class="mb-3">
        <label>Mã số</label>
        <input type="text" name="MaSo" class="form-control" value="{{ $taikhoan->MaSo }}" required>
    </div>

    <div class="mb-3">
        <label>Mật khẩu mới (không bắt buộc)</label>
        <input type="password" name="MatKhau" class="form-control">
    </div>

    <div class="mb-3">
        <label>Vai trò</label>
        <select name="VaiTro" class="form-control" required>
            <option value="sinhvien" {{ $taikhoan->VaiTro == 'sinhvien' ? 'selected' : '' }}>Sinh viên</option>
            <option value="giangvien" {{ $taikhoan->VaiTro == 'giangvien' ? 'selected' : '' }}>Giảng viên</option>
            <option value="canboql" {{ $taikhoan->VaiTro == 'canboql' ? 'selected' : '' }}>Cán bộ quản lý</option>
            <option value="admin" {{ $taikhoan->VaiTro == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>

    <button class="btn btn-success">Cập nhật</button>
</form>
@endsection
