<div class="modal-body p-4">
    <div class="mb-3">
        <label class="form-label fw-semibold">
            <i class="fas fa-calendar-alt text-primary me-2"></i>Năm học
        </label>
        <select name="MaNamHoc" class="form-select shadow-sm" required>
            <option value="">-- Chọn năm học --</option>
            @foreach($namhoc as $nh)
                <option value="{{ $nh->MaNamHoc }}" {{ $config->MaNamHoc == $nh->MaNamHoc ? 'selected' : '' }}>
                    {{ $nh->TenNamHoc }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">
            <i class="fas fa-clock text-success me-2"></i>Thời gian mở đăng ký
        </label>
        <input type="datetime-local" name="ThoiGianMoDangKy" 
               class="form-control shadow-sm" 
               value="{{ date('Y-m-d\TH:i', strtotime($config->ThoiGianMoDangKy)) }}" 
               required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">
            <i class="fas fa-clock text-danger me-2"></i>Thời gian đóng đăng ký
        </label>
        <input type="datetime-local" name="ThoiGianDongDangKy" 
               class="form-control shadow-sm" 
               value="{{ date('Y-m-d\TH:i', strtotime($config->ThoiGianDongDangKy)) }}" 
               required>
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
