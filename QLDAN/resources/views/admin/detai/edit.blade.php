@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4 class="text-warning mb-3">✏️ Sửa đề tài</h4>

    <form action="{{ route('admin.detai.update', $detai->MaDeTai) }}" method="POST">
        @csrf 
        @method('PUT')

        {{-- Tên và mô tả --}}
        <div class="mb-3">
        <label class="form-label">
            <i class="fas fa-book text-primary me-2"></i>Tên đề tài <span class="text-danger">*</span>
        </label>
        <input type="text" name="TenDeTai" class="form-control" value="{{ old('TenDeTai', $detai->TenDeTai) }}" 
               required minlength="10" maxlength="500" placeholder="Nhập tên đề tài...">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa', $detai->MoTa) }}</textarea>
        </div>

        {{-- Các thông tin phụ --}}
        <div class="row">
<div class="col-md-4 mb-3">
    <label class="fw-bold">Lĩnh vực (Ngành)</label>
    <select name="LinhVuc" class="form-select" required>
        <option value="">-- Chọn lĩnh vực --</option>
        @foreach($nganhs as $nganh)
            <option value="{{ $nganh->TenNganh }}" 
                {{ old('LinhVuc', $detai->LinhVuc) == $nganh->TenNganh ? 'selected' : '' }}>
                {{ $nganh->TenNganh }}
            </option>
        @endforeach
    </select>
</div>

            <div class="col-md-4 mb-3">
                <label class="fw-bold">Năm học</label>
                <select name="MaNamHoc" class="form-select">
                    <option value="">-- Chọn năm học --</option>
                    @foreach($namhocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}" {{ $detai->MaNamHoc == $nh->MaNamHoc ? 'selected' : '' }}>
                            {{ $nh->TenNamHoc }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="fw-bold">Loại đề tài</label>
                <select name="LoaiDeTai" class="form-select">
                    <option value="Cá nhân" {{ $detai->LoaiDeTai == 'Cá nhân' ? 'selected' : '' }}>Cá nhân</option>
                    <option value="Nhóm" {{ $detai->LoaiDeTai == 'Nhóm' ? 'selected' : '' }}>Nhóm</option>
                </select>
            </div>
        </div>

        {{-- Giảng viên & Cán bộ --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="fw-bold">Giảng viên hướng dẫn</label>
                <select name="MaGV" class="form-select">
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($gvs as $gv)
                        <option value="{{ $gv->MaGV }}" {{ $detai->MaGV == $gv->MaGV ? 'selected' : '' }}>
                            {{ $gv->TenGV }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Cán bộ quản lý</label>
                <select name="MaCB" class="form-select">
                    <option value="">-- Chọn cán bộ --</option>
                    @foreach($cbs as $cb)
                        <option value="{{ $cb->MaCB }}" {{ $detai->MaCB == $cb->MaCB ? 'selected' : '' }}>
                            {{ $cb->TenCB }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Nút hành động --}}
        <div class="text-end mt-3">
            <a href="{{ route('admin.detai.index') }}" class="btn btn-secondary">⬅️ Quay lại</a>
            <button class="btn btn-warning">💾 Cập nhật</button>
        </div>
    </form>
</div>
@endsection
