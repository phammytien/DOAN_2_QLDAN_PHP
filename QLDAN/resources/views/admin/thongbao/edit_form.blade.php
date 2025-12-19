<div class="modal-body p-4">
    <div class="mb-4">
        <label class="form-label fw-semibold">
            <i class="fas fa-align-left text-primary me-2"></i>
            Nội dung thông báo <span class="text-danger">*</span>
        </label>
        <textarea name="NoiDung" 
                  class="form-control shadow-sm" 
                  rows="4" 
                  placeholder="Nhập nội dung thông báo..."
                  required>{{ old('NoiDung', $tb->NoiDung) }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="fas fa-user-shield text-primary me-2"></i>
                Người đăng
            </label>
            <select name="MaCB" class="form-select shadow-sm">
                <option value="">-- Chọn cán bộ --</option>
                @foreach($cbs as $cb)
                    <option value="{{ $cb->MaCB }}" {{ old('MaCB', $tb->MaCB) == $cb->MaCB ? 'selected' : '' }}>
                        {{ $cb->TenCB }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="fas fa-user-friends text-primary me-2"></i>
                Đối tượng nhận <span class="text-danger">*</span>
            </label>
            <select name="DoiTuongNhan" class="form-select shadow-sm" required>
                <option value="TatCa" {{ old('DoiTuongNhan', $tb->DoiTuongNhan) == 'TatCa' ? 'selected' : '' }}>🌐 Tất cả</option>
                <option value="SV" {{ old('DoiTuongNhan', $tb->DoiTuongNhan) == 'SV' ? 'selected' : '' }}>🎓 Sinh viên</option>
                <option value="GV" {{ old('DoiTuongNhan', $tb->DoiTuongNhan) == 'GV' ? 'selected' : '' }}>👨‍🏫 Giảng viên</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="fas fa-flag text-primary me-2"></i>
                Mức độ thông báo <span class="text-danger">*</span>
            </label>
            <select name="MucDo" class="form-select shadow-sm" required>
                <option value="Khan" {{ old('MucDo', $tb->MucDo) == 'Khan' ? 'selected' : '' }}>🚨 Khẩn cấp</option>
                <option value="QuanTrong" {{ old('MucDo', $tb->MucDo) == 'QuanTrong' ? 'selected' : '' }}>⚠️ Quan trọng</option>
                <option value="BinhThuong" {{ old('MucDo', $tb->MucDo) == 'BinhThuong' ? 'selected' : '' }}>ℹ️ Bình thường</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="fas fa-cloud-upload-alt text-primary me-2"></i>
                File đính kèm
            </label>
            <input type="file" name="TenFile" class="form-control shadow-sm">
            <small class="text-muted d-block mt-1">
                <i class="fas fa-info-circle me-1"></i>Tối đa 5MB
            </small>
            @if($tb->TenFile)
                <div class="mt-2 text-success">
                    <i class="fas fa-check-circle me-1"></i>
                    Đang có file: <strong>{{ $tb->TenFile }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer border-0 bg-light">
    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>Hủy
    </button>
    <button type="submit" class="btn btn-warning px-4 shadow-sm text-white">
        <i class="fas fa-save me-2"></i>Cập nhật
    </button>
</div>
