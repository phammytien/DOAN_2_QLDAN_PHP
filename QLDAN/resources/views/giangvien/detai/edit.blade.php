@extends('layouts.giangvien')

@section('content')
<div class="container mt-4">
    <h3 class="text-primary mb-4">✏️ Cập nhật đề tài</h3>

    {{-- Hiển thị thông báo thành công hoặc lỗi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu nhập.<br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form cập nhật đề tài --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('giangvien.detai.update', $detai->MaDeTai) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Tên đề tài</label>
                    <input type="text" name="TenDeTai" class="form-control"
                        value="{{ old('TenDeTai', $detai->TenDeTai) }}" required>
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
                    <label class="form-label fw-bold">Năm học</label>
                    <select name="MaNamHoc" class="form-select" required>
                        <option value="">-- Chọn năm học --</option>
                        @foreach($namHocs as $nh)
                            <option value="{{ $nh->MaNamHoc }}"
                                {{ old('MaNamHoc', $detai->MaNamHoc) == $nh->MaNamHoc ? 'selected' : '' }}>
                                {{ $nh->TenNamHoc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="MoTa" class="form-control" rows="4">{{ old('MoTa', $detai->MoTa) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('giangvien.detai.index') }}" class="btn btn-secondary">
                        ← Quay lại danh sách
                    </a>
                    <button type="submit" class="btn btn-primary">
                        💾 Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
