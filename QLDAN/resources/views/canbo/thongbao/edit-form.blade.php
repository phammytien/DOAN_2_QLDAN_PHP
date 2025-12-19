<div class="modal-body p-4">
    <div class="mb-4">
        <label class="form-label fw-semibold">
            <i class="fas fa-align-left text-warning me-2"></i>
            Nội dung thông báo <span class="text-danger">*</span>
        </label>
        <textarea name="NoiDung" 
                  class="form-control shadow-sm" 
                  rows="4" 
                  placeholder="Nhập nội dung thông báo..."
                  required>{{ $tb->NoiDung }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="fas fa-user-friends text-warning me-2"></i>
                Đối tượng nhận <span class="text-danger">*</span>
            </label>
            <select name="DoiTuongNhan" class="form-select shadow-sm" required>
                <option value="TatCa" {{ $tb->DoiTuongNhan == 'TatCa' ? 'selected' : '' }}>🌐 Tất cả</option>
                <option value="SinhVien" {{ $tb->DoiTuongNhan == 'SinhVien' ? 'selected' : '' }}>🎓 Sinh viên</option>
                <option value="GiangVien" {{ $tb->DoiTuongNhan == 'GiangVien' ? 'selected' : '' }}>👨‍🏫 Giảng viên</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="fas fa-flag text-warning me-2"></i>
                Mức độ thông báo <span class="text-danger">*</span>
            </label>
            <select name="MucDo" class="form-select shadow-sm" required>
                <option value="Khẩn" {{ $tb->MucDo == 'Khẩn' ? 'selected' : '' }}>🚨 Khẩn cấp</option>
                <option value="Quan trọng" {{ $tb->MucDo == 'Quan trọng' ? 'selected' : '' }}>⚠️ Quan trọng</option>
                <option value="Bình thường" {{ $tb->MucDo == 'Bình thường' ? 'selected' : '' }}>ℹ️ Bình thường</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">
            <i class="fas fa-cloud-upload-alt text-warning me-2"></i>
            File đính kèm
        </label>
        @if($tb->TenFile)
            <div class="alert alert-info mb-2">
                <i class="fas fa-file me-2"></i>File hiện tại: <strong>{{ $tb->TenFile }}</strong>
            </div>
        @endif
        <input type="file" name="TenFile" class="form-control shadow-sm">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>Tối đa 5MB. Để trống nếu không muốn thay đổi file.
        </small>
    </div>
</div>

<div class="modal-footer border-0 bg-light">
    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>Hủy
    </button>
    <button type="submit" class="btn btn-warning px-4 shadow-sm">
        <i class="fas fa-save me-2"></i>Cập nhật
    </button>
</div>
