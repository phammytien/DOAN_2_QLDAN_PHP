@extends('layouts.canbo')

@section('title', 'Thông tin cá nhân')

@section('content')
<style>
    .profile-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        padding: 30px;
    }
    .profile-header {
        color: #1e3a8a; /* Dark blue */
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 10px;
    }
    .avatar-circle {
        width: 120px;
        height: 120px;
        background-color: #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .avatar-circle i {
        font-size: 60px;
        color: #94a3b8;
    }
    .info-label {
        font-weight: 600;
        color: #64748b;
        margin-bottom: 5px;
    }
    .info-value {
        color: #1e293b;
        font-weight: 600;
    }
    .info-row {
        margin-bottom: 15px;
    }
</style>

<div class="profile-card">
    <div class="row">
        <!-- Left Column: Avatar & Basic Info -->
        <div class="col-md-4 text-center border-end">
            <div class="avatar-circle">
                <i class="fas fa-user"></i>
            </div>
            <h5 class="fw-bold text-primary mb-1">Thông tin cán bộ</h5>
            
            <div class="text-start mt-4 px-3">
                <div class="mb-3">
                    <span class="d-block text-muted small">MSCB:</span>
                    <span class="fw-bold text-dark">{{ $canbo->MaCB }}</span>
                </div>
                <div class="mb-3">
                    <span class="d-block text-muted small">Họ và tên:</span>
                    <span class="fw-bold text-primary">{{ $canbo->TenCB }}</span>
                </div>
                <div class="mb-3">
                    <span class="d-block text-muted small">Học vị:</span>
                    <span class="fw-bold text-dark">{{ $canbo->HocVi ?? 'Thạc sĩ' }}</span>
                </div>
                <div class="mb-3">
                    <span class="d-block text-muted small">Học hàm:</span>
                    <span class="fw-bold text-dark">{{ $canbo->HocHam ?? 'Không' }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Detailed Info -->
        <div class="col-md-8 ps-md-5">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="d-block text-muted small">Ngày vào trường:</span>
                        <span class="fw-bold text-dark">01-09-2015</span> <!-- Placeholder -->
                    </div>
                    <div class="mb-3">
                        <span class="d-block text-muted small">Giới tính:</span>
                        <span class="fw-bold text-dark">{{ $canbo->GioiTinh }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="d-block text-muted small">Khoa:</span>
                        <span class="fw-bold text-primary">{{ $canbo->khoa->TenKhoa ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="d-block text-muted small">Chuyên Ngành:</span>
                        <span class="fw-bold text-primary">{{ $canbo->ChuyenNganh ?? 'Công nghệ thông tin' }}</span>
                    </div>
                </div>
            </div>

            <h5 class="profile-header">Thông tin cá nhân</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label">Ngày sinh:</span>
                        <span class="info-value ms-2">{{ $canbo->NgaySinh }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số CCCD:</span>
                        <span class="info-value ms-2">{{ $canbo->MaCCCD ?? '087203015525' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Đối tượng:</span>
                        <span class="info-value ms-2">Viên chức</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày vào đoàn:</span>
                        <span class="info-value ms-2">26-03-2006</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Điện thoại:</span>
                        <span class="info-value ms-2">{{ $canbo->SDT }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Địa chỉ liên hệ:</span>
                        <span class="info-value ms-2">{{ $canbo->HKTT ?? 'Cao Lãnh, Đồng Tháp' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label">Dân tộc:</span>
                        <span class="info-value ms-2">{{ $canbo->DanToc ?? 'Kinh' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày cấp:</span>
                        <span class="info-value ms-2">23-04-2020</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Diện chính sách:</span>
                        <span class="info-value ms-2">Không</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày vào đảng:</span>
                        <span class="info-value ms-2">--</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value ms-2 text-primary">{{ $canbo->Email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tôn giáo:</span>
                        <span class="info-value ms-2">{{ $canbo->TonGiao ?? 'Không' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateInfoModal">
                    <i class="fas fa-edit"></i> Cập nhật thông tin
                </button>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Info Modal -->
<div class="modal fade" id="updateInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật thông tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('canbo.profile.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="TenCB" class="form-control" value="{{ $canbo->TenCB }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="GioiTinh" class="form-select" required>
                                <option value="Nam" {{ $canbo->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ $canbo->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="NgaySinh" class="form-control" value="{{ $canbo->NgaySinh ? $canbo->NgaySinh->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số CCCD</label>
                            <input type="text" name="MaCCCD" class="form-control" value="{{ $canbo->MaCCCD }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dân tộc</label>
                            <input type="text" name="DanToc" class="form-control" value="{{ $canbo->DanToc }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tôn giáo</label>
                            <input type="text" name="TonGiao" class="form-control" value="{{ $canbo->TonGiao }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="SDT" class="form-control" value="{{ $canbo->SDT }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="Email" class="form-control" value="{{ $canbo->Email }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nơi sinh</label>
                            <input type="text" name="NoiSinh" class="form-control" value="{{ $canbo->NoiSinh }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hộ khẩu thường trú</label>
                            <input type="text" name="HKTT" class="form-control" value="{{ $canbo->HKTT }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Học vị</label>
                            <input type="text" name="HocVi" class="form-control" value="{{ $canbo->HocVi }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Học hàm</label>
                            <input type="text" name="HocHam" class="form-control" value="{{ $canbo->HocHam }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Chuyên ngành</label>
                            <input type="text" name="ChuyenNganh" class="form-control" value="{{ $canbo->ChuyenNganh }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Khoa</label>
                            <select name="MaKhoa" class="form-select">
                                <option value="">-- Chọn khoa --</option>
                                @foreach($khoas as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}" {{ $canbo->MaKhoa == $khoa->MaKhoa ? 'selected' : '' }}>
                                        {{ $khoa->TenKhoa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('canbo.profile.change-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu cũ</label>
                        <div class="input-group">
                            <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="old_password">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Password Visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Auto-open modal if there are errors
        @if($errors->has('old_password') || $errors->has('new_password'))
            var changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            changePasswordModal.show();
        @endif
    });
</script>

@if(session('success'))
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle fa-5x"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Thành công!</h3>
                    <p class="fs-5">{{ session('success') }}</p>
                    <button type="button" class="btn btn-success btn-lg mt-3 px-5" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
@endif

@if($errors->any())
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif
@endsection
