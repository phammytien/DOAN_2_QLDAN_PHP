<!-- Modal Thêm Giảng Viên -->
<div class="modal fade" id="addLecturerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Giảng Viên Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.giangvien.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.dashboard') }}">
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra!</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="TenGV" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="Email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã CCCD <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="MaCCCD" maxlength="12" minlength="12" pattern="[0-9]{12}" title="CCCD phải có đúng 12 số" placeholder="Nhập 12 số" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="SDT" maxlength="10" minlength="10" pattern="[0-9]{10}" title="Số điện thoại phải có đúng 10 số" placeholder="Nhập 10 số" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="NgaySinh" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="GioiTinh">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Khoa <span class="text-danger">*</span></label>
                            <select class="form-select" name="MaKhoa" required>
                                <option value="">-- Chọn Khoa --</option>
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngành</label>
                            <select class="form-select" name="MaNganh">
                                <option value="">-- Chọn Ngành --</option>
                                @foreach($nganhs as $nganh)
                                    <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Học vị</label>
                            <select class="form-select" name="HocVi">
                                <option value="Thạc sĩ">Thạc sĩ</option>
                                <option value="Tiến sĩ">Tiến sĩ</option>
                                <option value="Phó giáo sư">Phó giáo sư</option>
                                <option value="Giáo sư">Giáo sư</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Học hàm</label>
                            <input type="text" class="form-control" name="HocHam">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ (HKTT)</label>
                            <input type="text" class="form-control" name="HKTT">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Thêm Sinh Viên -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Sinh Viên Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.sinhvien.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.dashboard') }}">
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra!</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="TenSV" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="Email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã CCCD <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="MaCCCD" maxlength="12" minlength="12" pattern="[0-9]{12}" title="CCCD phải có đúng 12 số" placeholder="Nhập 12 số" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="SDT" maxlength="10" minlength="10" pattern="[0-9]{10}" title="Số điện thoại phải có đúng 10 số" placeholder="Nhập 10 số" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="NgaySinh" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="GioiTinh">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Năm học <span class="text-danger">*</span></label>
                            <select class="form-select" name="MaNamHoc" required>
                                @foreach($namhocs as $nh)
                                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lớp</label>
                            <select class="form-select" name="MaLop">
                                <option value="">-- Chọn Lớp --</option>
                                @foreach($lops as $lop)
                                    <option value="{{ $lop->MaLop }}">{{ $lop->TenLop }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Khoa <span class="text-danger">*</span></label>
                            <select class="form-select" name="MaKhoa" required>
                                <option value="">-- Chọn Khoa --</option>
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngành</label>
                            <select class="form-select" name="MaNganh">
                                <option value="">-- Chọn Ngành --</option>
                                @foreach($nganhs as $nganh)
                                    <option value="{{ $nganh->MaNganh }}">{{ $nganh->TenNganh }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ (HKTT)</label>
                            <input type="text" class="form-control" name="HKTT">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Thêm Đề Tài -->
<div class="modal fade" id="addTopicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Đề Tài Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.detai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.dashboard') }}">
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra!</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Tên đề tài <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="TenDeTai" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="MoTa" rows="3"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Lĩnh vực <span class="text-danger">*</span></label>
                            <select class="form-select" name="LinhVuc" required>
                                <option value="">-- Chọn Lĩnh vực --</option>
                                @foreach($linhvucs as $lv)
                                    <option value="{{ $lv }}">{{ $lv }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại đề tài <span class="text-danger">*</span></label>
                            <select class="form-select" name="LoaiDeTai" required>
                                <option value="Nghiên cứu">Nghiên cứu</option>
                                <option value="Ứng dụng">Ứng dụng</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Năm học <span class="text-danger">*</span></label>
                            <select class="form-select" name="MaNamHoc" required>
                                @foreach($namhocs as $nh)
                                    <option value="{{ $nh->MaNamHoc }}">{{ $nh->TenNamHoc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giảng viên hướng dẫn</label>
                            <select class="form-select" name="MaGV">
                                <option value="">-- Chọn GVHD --</option>
                                @foreach($gvs as $gv)
                                    <option value="{{ $gv->MaGV }}">{{ $gv->TenGV }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cán bộ quản lý</label>
                            <select class="form-select" name="MaCB">
                                <option value="">-- Chọn Cán bộ --</option>
                                @foreach($cbs as $cb)
                                    <option value="{{ $cb->MaCB }}">{{ $cb->TenCB }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Thêm Thông Báo -->
<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient-primary text-white border-0" style="background: linear-gradient(135deg, #2d33e9ff 0%, #1e51caff 100%);">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bullhorn me-2"></i>Tạo thông báo mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.thongbao.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('admin.dashboard') }}">
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-align-left text-primary me-2"></i>
                            Nội dung thông báo <span class="text-danger">*</span>
                        </label>
                        <textarea name="NoiDung" class="form-control shadow-sm" rows="4" placeholder="Nhập nội dung thông báo..." required>{{ old('NoiDung') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-shield text-primary me-2"></i>
                                Người đăng
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-tie"></i></span>
                                <select name="MaCB" class="form-select shadow-sm">
                                    <option value="">-- Chọn cán bộ --</option>
                                    @foreach($cbs as $cb)
                                        <option value="{{ $cb->MaCB }}">{{ $cb->TenCB }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-friends text-primary me-2"></i>
                                Đối tượng nhận <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                <select name="DoiTuongNhan" class="form-select shadow-sm" required>
                                    <option value="TatCa">🌐 Tất cả</option>
                                    <option value="SV">🎓 Sinh viên</option>
                                    <option value="GV">👨‍🏫 Giảng viên</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-flag text-primary me-2"></i>
                                Mức độ thông báo <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-exclamation-circle"></i></span>
                                <select name="MucDo" class="form-select shadow-sm" required>
                                    <option value="Khan">🚨 Khẩn cấp</option>
                                    <option value="QuanTrong">⚠️ Quan trọng</option>
                                    <option value="BinhThuong">ℹ️ Bình thường</option>
                                </select>
                            </div>
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i>Đăng thông báo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
