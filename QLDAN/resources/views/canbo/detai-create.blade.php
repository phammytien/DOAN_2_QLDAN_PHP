@extends('layouts.canbo')

@section('title', 'Tạo đề tài mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('canbo.detai.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        <h2 class="fw-bold text-dark mb-0">Tạo đề tài mới</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('canbo.detai.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Tên đề tài <span class="text-danger">*</span></label>
                        <input type="text" name="TenDeTai" class="form-control" required placeholder="Nhập tên đề tài...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lĩnh vực <span class="text-danger">*</span></label>
                        <select name="LinhVuc" class="form-select" required>
                            <option value="">-- Chọn lĩnh vực --</option>
                            @foreach($linhvucs as $lv)
                                <option value="{{ $lv }}">{{ $lv }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Năm học <span class="text-danger">*</span></label>
                        <select name="MaNamHoc" class="form-select" required>
                            <option value="">-- Chọn năm học --</option>
                            @foreach($namhocs as $nh)
                                <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Giảng viên hướng dẫn</label>
                        <select name="MaGV" class="form-select">
                            <option value="">-- Chọn giảng viên (nếu có) --</option>
                            @foreach($giangviens as $gv)
                                <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Số lượng sinh viên tối đa <span class="text-danger">*</span></label>
                        <input type="number" name="SoLuongSV" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hạn nộp báo cáo</label>
                        <input type="date" name="DeadlineBaoCao" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="MoTa" class="form-control" rows="3" placeholder="Mô tả chi tiết về đề tài..."></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Yêu cầu</label>
                        <textarea name="YeuCau" class="form-control" rows="3" placeholder="Yêu cầu kiến thức, kỹ năng..."></textarea>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Lưu đề tài
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
