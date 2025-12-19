@extends('layouts.giangvien')
@section('title', 'Hồ sơ')
@section('content')
<style>
:root {
    --primary-blue: #4285f4;
    --text-dark: #202124;
    --text-secondary: #5f6368;
    --border-light: #dadce0;
    --bg-light: #f8f9fa;
}

.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.profile-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 24px;
}

@media (max-width: 992px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

/* Left Column */
.profile-sidebar {
    background: white;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
    text-align: center;
}

.avatar-section {
    margin-bottom: 24px;
}

.avatar-wrapper {
    width: 150px;
    height: 150px;
    margin: 0 auto 16px;
    position: relative;
}

.avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #f0f0f0;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    color: white;
    border: 4px solid #f0f0f0;
}

.profile-name {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    text-transform: uppercase;
}

.profile-id {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0 0 4px 0;
}

.profile-id strong {
    color: var(--text-dark);
}

.profile-gender {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
}

.profile-gender strong {
    color: var(--text-dark);
}

.divider {
    height: 1px;
    background: var(--border-light);
    margin: 24px 0;
}

.social-section h6 {
    font-size: 13px;
    color: var(--text-secondary);
    margin: 0 0 16px 0;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 16px;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 18px;
    transition: all 0.2s;
}

.social-link:hover {
    background: var(--primary-blue);
    color: white;
}

.action-section h6 {
    font-size: 13px;
    color: var(--text-secondary);
    margin: 0 0 12px 0;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.action-btn {
    background: var(--bg-light);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-dark);
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.action-btn:hover {
    background: #e8f0fe;
    border-color: var(--primary-blue);
    color: var(--primary-blue);
}

.action-btn i {
    font-size: 16px;
}

/* Right Column */
.profile-main {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 28px;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
}

.info-card h5 {
    font-size: 18px;
    font-weight: 500;
    color: var(--primary-blue);
    margin: 0 0 24px 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-label {
    font-size: 13px;
    color: var(--text-secondary);
}

.info-value {
    font-size: 15px;
    font-weight: 500;
    color: var(--text-dark);
}

.update-btn-container {
    display: flex;
    justify-content: flex-end;
}

.btn-update {
    background: var(--primary-blue);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-update:hover {
    background: #3367d6;
    color: white;
}
</style>

<div class="profile-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="profile-grid">
        <!-- Left Sidebar -->
        <div class="profile-sidebar">
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    @if(!empty($hoso->HinhAnh))
                        <img src="{{ asset($hoso->HinhAnh) }}" class="avatar-img" alt="Avatar">
                    @else
                        <div class="avatar-placeholder">👤</div>
                    @endif
                </div>
                <h3 class="profile-name">{{ $hoso->TenGV }}</h3>
                <p class="profile-id">Mã giảng viên: <strong>{{ $hoso->MaGV }}</strong></p>
                <p class="profile-gender">Giới tính: <strong>{{ $hoso->GioiTinh }}</strong></p>
            </div>

            <div class="divider"></div>

            <div class="social-section">
                <h6>Liên kết mạng xã hội/trang web cá nhân</h6>
                <div class="social-links">
                    <a href="#" class="social-link" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-link" title="Twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="social-link" title="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>

            <div class="divider"></div>

            <div class="action-section">
                <h6>Nút hành động nhanh</h6>
                <div class="action-buttons">
                    <a href="mailto:{{ $hoso->Email }}" class="action-btn">
                        <i class="bi bi-envelope"></i>
                        <span>Gửi email</span>
                    </a>
                    <a href="tel:{{ $hoso->SDT }}" class="action-btn">
                        <i class="bi bi-telephone"></i>
                        <span>Gọi điện</span>
                    </a>
                    <a href="#" class="action-btn">
                        <i class="bi bi-calendar-check"></i>
                        <span>Xem lịch làm việc</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Main Content -->
        <div class="profile-main">
            <!-- Work Information -->
            <div class="info-card">
                <h5>Thông tin công tác</h5>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Khoa</div>
                        <div class="info-value">{{ $hoso->khoa->TenKhoa ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Ngành</div>
                        <div class="info-value">{{ $hoso->nganh->TenNganh ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Học vị</div>
                        <div class="info-value">{{ $hoso->HocVi }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Học hàm</div>
                        <div class="info-value">{{ $hoso->HocHam }}</div>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <div class="info-label">Chuyên ngành</div>
                        <div class="info-value">{{ $hoso->ChuyenNganh }}</div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="info-card">
                <h5>Thông tin cá nhân</h5>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Ngày sinh</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($hoso->NgaySinh)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">CCCD</div>
                        @php
                            $maCCCD = 'Chưa cập nhật';
                            if (!empty($hoso->CCCD)) {
                                $decoded = json_decode($hoso->CCCD, true);
                                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['MaCCCD'])) {
                                    $maCCCD = $decoded['MaCCCD'];
                                } elseif (is_numeric($hoso->CCCD) && strlen($hoso->CCCD) == 12) {
                                    $maCCCD = $hoso->CCCD;
                                }
                            }
                        @endphp
                        <div class="info-value">{{ $maCCCD }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value">{{ $hoso->SDT }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $hoso->Email }}</div>
                    </div>
                </div>
            </div>

            <!-- Update Button -->
            <div class="update-btn-container">
                <button type="button" class="btn-update" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                    <i class="bi bi-pencil-square"></i>
                    Cập nhật hồ sơ
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update Profile Modal -->
<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 pt-0">
                <h4 class="fw-bold mb-2">Chỉnh Sửa Hồ Sơ Cán Bộ</h4>
                <p class="text-muted mb-4">Cập nhật thông tin chi tiết của bạn dưới đây.</p>

                <form action="{{ route('giangvien.hoso.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4 text-center">
                        <div class="position-relative d-inline-block">
                            @if(!empty($hoso->HinhAnh) && file_exists(public_path($hoso->HinhAnh)))
                                <img id="avatarPreview" 
                                     src="{{ asset($hoso->HinhAnh) }}?v={{ time() }}" 
                                     class="rounded-circle object-fit-cover"
                                     style="width: 120px; height: 120px; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            @else
                                <div id="avatarPreview" 
                                     class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    <i class="bi bi-person-fill text-secondary" style="font-size: 60px;"></i>
                                </div>
                            @endif

                            <label for="avatarUpload" 
                                   class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 cursor-pointer"
                                   style="cursor: pointer; transform: translate(10%, 10%);">
                                <i class="bi bi-camera-fill text-primary"></i>
                            </label>

                            <input type="file" id="avatarUpload" name="HinhAnh" class="d-none" accept="image/*">
                        </div>
                        <div class="small text-muted mt-3">Nhấn vào biểu tượng máy ảnh để thay đổi ảnh đại diện</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">Họ tên</label>
                            <input type="text" name="TenGV" class="form-control bg-light" value="{{ $hoso->TenGV }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">Email</label>
                            <input type="email" name="Email" class="form-control bg-light" value="{{ $hoso->Email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">CCCD <span class="text-danger">*</span></label>
                            @php
                                $maCCCDInput = '';
                                if (!empty($hoso->CCCD)) {
                                    $decoded = json_decode($hoso->CCCD, true);
                                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['MaCCCD'])) {
                                        $maCCCDInput = $decoded['MaCCCD'];
                                    } elseif (is_numeric($hoso->CCCD) && strlen($hoso->CCCD) == 12) {
                                        $maCCCDInput = $hoso->CCCD;
                                    }
                                }
                            @endphp
                            <input type="text" name="CCCD" id="cccdInput" class="form-control bg-light" 
                                   value="{{ $maCCCDInput }}" 
                                   pattern="[0-9]{12}" 
                                   maxlength="12"
                                   title="CCCD phải có đúng 12 chữ số"
                                   required>
                            <small class="text-muted">Nhập đúng 12 chữ số</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">Điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="SDT" id="sdtInput" class="form-control bg-light" 
                                   value="{{ $hoso->SDT }}" 
                                   pattern="[0-9]{10}" 
                                   maxlength="10"
                                   title="Số điện thoại phải có đúng 10 chữ số"
                                   required>
                            <small class="text-muted">Nhập đúng 10 chữ số</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">Chuyên ngành</label>
                            <input type="text" name="ChuyenNganh" class="form-control bg-light" value="{{ $hoso->ChuyenNganh }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">Học vị</label>
                            <input type="text" name="HocVi" class="form-control bg-light" value="{{ $hoso->HocVi }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">Học hàm</label>
                            <input type="text" name="HocHam" class="form-control bg-light" value="{{ $hoso->HocHam }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="bi bi-floppy me-2"></i> Cập nhật hồ sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('updateProfileModal');
    if(modal) {
        modal.addEventListener('shown.bs.modal', function () {
            const fileInput = document.getElementById('avatarUpload');
            const preview = document.getElementById('avatarPreview');

            if(fileInput) {
                fileInput.onchange = function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        if (preview.tagName.toLowerCase() === 'img') {
                            preview.src = event.target.result;
                        } else {
                            const img = document.createElement('img');
                            img.id = "avatarPreview";
                            img.src = event.target.result;
                            img.className = "rounded-circle object-fit-cover";
                            img.style.width = "120px";
                            img.style.height = "120px";
                            img.style.border = "3px solid #fff";
                            img.style.boxShadow = "0 2px 5px rgba(0,0,0,0.1)";
                            preview.replaceWith(img);
                        }
                    };
                    reader.readAsDataURL(file);
                };
            }
        });
    }

    // Validation for CCCD - only numbers
    const cccdInput = document.getElementById('cccdInput');
    if(cccdInput) {
        cccdInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if(this.value.length > 12) {
                this.value = this.value.slice(0, 12);
            }
        });
    }

    // Validation for SDT - only numbers
    const sdtInput = document.getElementById('sdtInput');
    if(sdtInput) {
        sdtInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if(this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    // Validation for Email - no special characters except @ and .
    const emailInput = document.querySelector('input[name="Email"]');
    if(emailInput) {
        emailInput.addEventListener('input', function(e) {
            // Allow only alphanumeric, @, ., -, and _
            this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g, '');
        });
    }
});
</script>

@endsection
