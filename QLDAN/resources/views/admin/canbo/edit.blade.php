<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Mã cán bộ</label>
        <input type="text" class="form-control" value="{{ $canbo->MaCB }}" disabled>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Họ & Tên <span class="text-danger">*</span></label>
        <input type="text" name="TenCB" class="form-control" value="{{ old('TenCB', $canbo->TenCB) }}" required minlength="3" maxlength="200">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Giới tính <span class="text-danger">*</span></label>
        <select name="GioiTinh" class="form-select" required>
            <option value="Nam" {{ $canbo->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
            <option value="Nữ" {{ $canbo->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
        <input type="date" name="NgaySinh" class="form-control" value="{{ old('NgaySinh', $canbo->NgaySinh ? $canbo->NgaySinh->format('Y-m-d') : '') }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">CCCD <span class="text-danger">*</span></label>
        <input type="text" name="MaCCCD" class="form-control" value="{{ old('MaCCCD', $canbo->MaCCCD) }}" required pattern="[0-9]{12}" maxlength="12" title="CCCD phải có đúng 12 chữ số">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">SĐT <span class="text-danger">*</span></label>
        <input type="text" name="SDT" class="form-control" value="{{ old('SDT', $canbo->SDT) }}" required pattern="[0-9]{10}" maxlength="10" title="Số điện thoại phải có đúng 10 chữ số">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="Email" class="form-control" value="{{ old('Email', $canbo->Email) }}" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Email chỉ được chứa chữ, số và ký tự @">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Tôn giáo</label>
        <input type="text" name="TonGiao" class="form-control" value="{{ old('TonGiao', $canbo->TonGiao) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Dân tộc</label>
        <input type="text" name="DanToc" class="form-control" value="{{ old('DanToc', $canbo->DanToc) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Học vị</label>
        <input type="text" name="HocVi" class="form-control" value="{{ old('HocVi', $canbo->HocVi) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Học hàm</label>
        <input type="text" name="HocHam" class="form-control" value="{{ old('HocHam', $canbo->HocHam) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Chuyên ngành</label>
        <input type="text" name="ChuyenNganh" class="form-control" value="{{ old('ChuyenNganh', $canbo->ChuyenNganh) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Nơi sinh</label>
        <input type="text" name="NoiSinh" class="form-control" value="{{ old('NoiSinh', $canbo->NoiSinh) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Hộ khẩu thường trú</label>
        <input type="text" name="HKTT" class="form-control" value="{{ old('HKTT', $canbo->HKTT) }}">
    </div>
<!-- 
    {{-- Hidden: Tài khoản liên kết (auto-managed by system)
    <div class="col-md-12 mb-3">
        <label class="form-label">Tài khoản liên kết</label>
        <select name="MaTK" class="form-select">
            <option value="">-- Không liên kết --</option>
            @foreach($taikhoans as $tk)
                <option value="{{ $tk->MaTK }}" {{ $canbo->MaTK == $tk->MaTK ? 'selected' : '' }}>
                    {{ $tk->MaSo }} ({{ $tk->name ?? 'N/A' }})
                </option>
            @endforeach
        </select>
    </div>
    --}} -->
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
    <button class="btn btn-primary">Cập nhật</button>
</div>
