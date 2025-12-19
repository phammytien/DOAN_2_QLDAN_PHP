@extends('layouts.admin')

@section('content')
<h3>Thêm tài khoản</h3>

<form action="{{ route('admin.taikhoan.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Mã số</label>
        <input type="text" name="MaSo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Mật khẩu</label>
        <input type="password" name="MatKhau" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Vai trò</label>
        <select name="VaiTro" class="form-control" required>
            <option value="sinhvien">Sinh viên</option>
            <option value="giangvien">Giảng viên</option>
            <option value="canboql">Cán bộ quản lý</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <button class="btn btn-success">Lưu</button>
</form>
@endsection
