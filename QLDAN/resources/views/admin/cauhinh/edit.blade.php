@extends('layouts.admin')

@section('content')

<div class="container">
    <h3 class="mb-4">✏ Sửa cấu hình năm học</h3>

<form action="{{ route('admin.cauhinh.update.time', $config->id) }}" method="POST">


        @csrf

        <div class="mb-3">
            <label>Năm học:</label>
            <select name="MaNamHoc" class="form-control" required>
                @foreach($namhoc as $nh)
                    <option value="{{ $nh->MaNamHoc }}"
                        {{ $config->MaNamHoc == $nh->MaNamHoc ? 'selected' : '' }}>
                        {{ $nh->TenNamHoc }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Thời gian mở đăng ký</label>
            <input type="datetime-local" name="ThoiGianMoDangKy"
                value="{{ date('Y-m-d\TH:i', strtotime($config->ThoiGianMoDangKy)) }}"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Thời gian đóng đăng ký</label>
            <input type="datetime-local" name="ThoiGianDongDangKy"
                value="{{ date('Y-m-d\TH:i', strtotime($config->ThoiGianDongDangKy)) }}"
                class="form-control" required>
        </div>

        <button class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.cauhinh.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>

</div>

@endsection
