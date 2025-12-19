<form action="{{ route('admin.detai.update', $detai->MaDeTai) }}" method="POST">
    @csrf 
    @method('PUT')

    <div class="modal-body">
        {{-- Tên đề tài --}}
        <div class="mb-4">
            <label class="form-label form-label-modern">
                <i class="fas fa-book text-primary me-2"></i>Tên đề tài
            </label>
            <input type="text" name="TenDeTai" class="form-control form-control-modern" 
                   value="{{ old('TenDeTai', $detai->TenDeTai) }}" required
                   placeholder="Nhập tên đề tài...">
        </div>

        {{-- Mô tả --}}
        <div class="mb-4">
            <label class="form-label form-label-modern">
                <i class="fas fa-align-left text-info me-2"></i>Mô tả
            </label>
            <textarea name="MoTa" class="form-control form-control-modern" rows="4"
                      placeholder="Nhập mô tả chi tiết về đề tài...">{{ old('MoTa', $detai->MoTa) }}</textarea>
        </div>

        {{-- Row 1: Lĩnh vực, Năm học, Loại đề tài --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label form-label-modern">
                    <i class="fas fa-graduation-cap text-success me-2"></i>Lĩnh vực (Ngành)
                </label>
                <select name="LinhVuc" class="form-select form-control-modern" required>
                    <option value="">-- Chọn lĩnh vực --</option>
                    @foreach($nganhs as $nganh)
                        <option value="{{ $nganh->TenNganh }}" 
                            {{ old('LinhVuc', $detai->LinhVuc) == $nganh->TenNganh ? 'selected' : '' }}>
                            {{ $nganh->TenNganh }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label form-label-modern">
                    <i class="fas fa-calendar-alt text-warning me-2"></i>Năm học
                </label>
                <select name="MaNamHoc" class="form-select form-control-modern" required>
                    <option value="">-- Chọn năm học --</option>
                    @foreach($namhocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}" {{ $detai->MaNamHoc == $nh->MaNamHoc ? 'selected' : '' }}>
                            {{ $nh->TenNamHoc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label form-label-modern">
                    <i class="fas fa-users text-danger me-2"></i>Loại đề tài
                </label>
                <select name="LoaiDeTai" class="form-select form-control-modern" id="editLoaiDeTai" onchange="toggleEditMemberCount()" required>
                    <option value="Cá nhân" {{ $detai->LoaiDeTai == 'Cá nhân' ? 'selected' : '' }}>Cá nhân</option>
                    <option value="Nhóm" {{ $detai->LoaiDeTai == 'Nhóm' ? 'selected' : '' }}>Nhóm</option>
                </select>
                
                <div id="editMemberCountGroup" class="mt-2" style="{{ $detai->LoaiDeTai == 'Nhóm' ? '' : 'display: none;' }}">
                    <label class="form-label form-label-modern" style="font-size: 0.75rem;">Số lượng thành viên</label>
                    <input type="number" name="SoLuongThanhVien" class="form-control form-control-modern" 
                           value="{{ old('SoLuongThanhVien', $detai->SoLuongThanhVien ?? 1) }}" min="1" max="10">
                </div>
            </div>
        </div>
        
        
        {{-- Row 2: Giảng viên & Cán bộ --}}
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label form-label-modern">
                    <i class="fas fa-chalkboard-teacher text-primary me-2"></i>Giảng viên hướng dẫn
                </label>
                <select name="MaGV" class="form-select form-control-modern">
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($gvs as $gv)
                        <option value="{{ $gv->MaGV }}" {{ $detai->MaGV == $gv->MaGV ? 'selected' : '' }}>
                            {{ $gv->TenGV }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label form-label-modern">
                    <i class="fas fa-user-tie text-secondary me-2"></i>Cán bộ quản lý
                </label>
                <select name="MaCB" class="form-select form-control-modern">
                    <option value="">-- Chọn cán bộ --</option>
                    @foreach($cbs as $cb)
                        <option value="{{ $cb->MaCB }}" {{ $detai->MaCB == $cb->MaCB ? 'selected' : '' }}>
                            {{ $cb->TenCB }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="modal-footer" style="background: var(--gray-50); padding: 1.25rem 2rem;">
        <button type="button" class="btn btn-modern" style="background: var(--gray-200); color: var(--gray-600); padding: 0.625rem 1.5rem;" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Đóng
        </button>
        <button type="submit" class="btn btn-modern" style="background: var(--warning-orange); color: white; padding: 0.625rem 1.5rem;">
            <i class="fas fa-save me-2"></i>Cập nhật
        </button>
    </div>
</form>
