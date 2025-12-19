<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label-modern">
            <i class="fas fa-user text-primary me-2"></i>Họ & Tên <span class="text-danger">*</span>
        </label>
        <input type="text" name="TenGV" class="form-control form-control-modern" value="{{ $gv->TenGV }}" required minlength="3" maxlength="200" placeholder="Nhập họ tên...">
    </div>

    <div class="col-md-6">
        <label class="form-label-modern">
            <i class="fas fa-calendar text-warning me-2"></i>Ngày sinh <span class="text-danger">*</span>
        </label>
        <input type="date" name="NgaySinh" class="form-control form-control-modern" value="{{ $gv->NgaySinh ? $gv->NgaySinh->format('Y-m-d') : '' }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">
            <i class="fas fa-id-card text-info me-2"></i>CCCD <span class="text-danger">*</span>
        </label>
        <input type="text" name="MaCCCD" class="form-control form-control-modern" value="{{ $gv->MaCCCD }}" required pattern="[0-9]{12}" maxlength="12" title="CCCD phải có đúng 12 chữ số" placeholder="Nhập 12 số...">
    </div>
    <div class="col-md-4">
        <label class="form-label-modern">Ngày cấp</label>
        <input type="date" name="NgayCap" class="form-control form-control-modern" value="{{ $gv->cccd && $gv->cccd->NgayCap ? $gv->cccd->NgayCap->format('Y-m-d') : '' }}">
    </div>
    <div class="col-md-4">
        <label class="form-label-modern">Nơi cấp</label>
        <input type="text" name="NoiCap" class="form-control form-control-modern" value="{{ $gv->cccd->NoiCap ?? '' }}" placeholder="Nơi cấp...">
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">
            <i class="fas fa-phone text-success me-2"></i>SĐT <span class="text-danger">*</span>
        </label>
        <input type="text" name="SDT" class="form-control form-control-modern" value="{{ $gv->SDT }}" required pattern="[0-9]{10}" maxlength="10" title="Số điện thoại phải có đúng 10 chữ số" placeholder="Nhập 10 số...">
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">
            <i class="fas fa-envelope text-danger me-2"></i>Email <span class="text-danger">*</span>
        </label>
        <input type="email" name="Email" class="form-control form-control-modern" value="{{ $gv->Email }}" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Email chỉ được chứa chữ, số và ký tự @" placeholder="email@example.com">
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">
            <i class="fas fa-venus-mars text-secondary me-2"></i>Giới tính <span class="text-danger">*</span>
        </label>
        <select name="GioiTinh" class="form-select form-control-modern" required>
            <option value="Nam" {{ $gv->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
            <option value="Nữ" {{ $gv->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">Khoa <span class="text-danger">*</span></label>
        <input list="listKhoa" name="MaKhoa" class="form-control form-control-modern" value="{{ $gv->khoa->TenKhoa ?? $gv->MaKhoa }}" required placeholder="Chọn hoặc nhập khoa mới">
        <datalist id="listKhoa">
            @foreach($khoas as $khoa)
                <option value="{{ $khoa->TenKhoa }}">
            @endforeach
        </datalist>
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">Ngành <span class="text-danger">*</span></label>
        <input list="listNganh" name="MaNganh" class="form-control form-control-modern" value="{{ $gv->nganh->TenNganh ?? $gv->MaNganh }}" required placeholder="Chọn hoặc nhập ngành mới">
        <datalist id="listNganh">
            @foreach($nganhs as $nganh)
                <option value="{{ $nganh->TenNganh }}">
            @endforeach
        </datalist>
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">Năm học</label>
        <select name="MaNamHoc" class="form-select form-control-modern">
            <option value="">-- Chọn năm học --</option>
            @foreach($namhocs as $nh)
                <option value="{{ $nh->MaNamHoc }}" {{ $gv->MaNamHoc == $nh->MaNamHoc ? 'selected' : '' }}>{{ $nh->TenNamHoc }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label-modern">Nơi sinh</label>
        <input type="text" name="NoiSinh" class="form-control form-control-modern" value="{{ $gv->NoiSinh }}" placeholder="Nơi sinh...">
    </div>

    <div class="col-md-6">
        <label class="form-label-modern">Hộ khẩu thường trú</label>
        <input type="text" name="HKTT" class="form-control form-control-modern" value="{{ $gv->HKTT }}" placeholder="Hộ khẩu thường trú...">
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">Dân tộc</label>
        <input type="text" name="DanToc" class="form-control form-control-modern" value="{{ $gv->DanToc }}" placeholder="Dân tộc...">
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">Tôn giáo</label>
        <input type="text" name="TonGiao" class="form-control form-control-modern" value="{{ $gv->TonGiao }}" placeholder="Tôn giáo...">
    </div>

    <div class="col-md-4">
        <label class="form-label-modern">Học vị</label>
        <input type="text" name="HocVi" class="form-control form-control-modern" value="{{ $gv->HocVi }}" placeholder="Học vị...">
    </div>

    <div class="col-md-6">
        <label class="form-label-modern">Học hàm</label>
        <input type="text" name="HocHam" class="form-control form-control-modern" value="{{ $gv->HocHam }}" placeholder="Học hàm...">
    </div>

    <div class="col-md-6">
        <label class="form-label-modern">Chuyên ngành</label>
        <input type="text" name="ChuyenNganh" class="form-control form-control-modern" value="{{ $gv->ChuyenNganh }}" placeholder="Chuyên ngành...">
    </div>
</div>

<div class="modal-footer" style="background: var(--gray-50);">
    <button type="button" class="btn btn-modern" style="background: var(--gray-200); color: var(--gray-600);" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>Hủy
    </button>
    <button type="submit" class="btn btn-modern btn-primary-modern">
        <i class="fas fa-save me-2"></i>Cập nhật
    </button>
</div>

<style>
    :root {
        --primary-blue: #4f46e5;
        --light-blue: #e0e7ff;
        --dark-blue: #3730a3;
        --hover-blue: #6366f1;
        --gray-50: #f9fafb;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control-modern {
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        padding: 0.625rem;
    }

    .form-control-modern:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .btn-modern {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-primary-modern {
        background: var(--primary-blue);
        color: white;
    }

    .btn-primary-modern:hover {
        background: var(--dark-blue);
        transform: translateY(-1px);
    }
</style>
