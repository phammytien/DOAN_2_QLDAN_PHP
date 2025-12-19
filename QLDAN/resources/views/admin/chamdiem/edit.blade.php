@extends('layouts.admin')
@section('title','Edit chấm điểm')

@section('content')
<div class="container mt-4">
    <h2>✏️ Chỉnh sửa chấm điểm</h2>

    <form action="{{ route('admin.chamdiem.update', $cd->MaCham) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Đề tài</label>
            <input type="text" class="form-control" value="{{ $cd->detai->TenDeTai }}" disabled>
        </div>

        <div class="mb-3">
            <label>Sinh viên</label>
            <input type="text" class="form-control" value="{{ $cd->sinhvien->TenSV }}" disabled>
        </div>

        <div class="mb-3 row">
            <div class="col-md-6">
                <label>Điểm GVHD</label>
                <input type="number" step="0.01" name="DiemGVHD" class="form-control" value="{{ $gvhd->Diem ?? '' }}">
            </div>
            <div class="col-md-6">
                <label>Điểm GVPB</label>
                <input type="number" step="0.01" name="DiemGVPB" class="form-control" value="{{ $gvpb->Diem ?? '' }}">
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-6">
                <label>Nhận xét GVHD</label>
                <textarea name="NhanXetGVHD" class="form-control">{{ $gvhd->NhanXet ?? '' }}</textarea>
            </div>
            <div class="col-md-6">
                <label>Nhận xét GVPB</label>
                <textarea name="NhanXetGVPB" class="form-control">{{ $gvpb->NhanXet ?? '' }}</textarea>
            </div>
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Chưa xác nhận" {{ $cd->TrangThai=='Chưa xác nhận' ? 'selected' : '' }}>⏳ Chưa xác nhận</option>
                <option value="Chờ duyệt" {{ $cd->TrangThai=='Chờ duyệt' ? 'selected' : '' }}>🟡 Chờ duyệt</option>
                <option value="Đã duyệt" {{ $cd->TrangThai=='Đã duyệt' ? 'selected' : '' }}>✅ Đã duyệt</option>
                <option value="Từ chối" {{ $cd->TrangThai=='Từ chối' ? 'selected' : '' }}>❌ Từ chối</option>
            </select>
        </div>

        <button class="btn btn-success">💾 Lưu thay đổi</button>
        <a href="{{ route('admin.chamdiem.index') }}" class="btn btn-secondary">↩ Quay lại</a>
    </form>
</div>
@endsection
