@extends('layouts.sinhvien')

@section('content')

<style>
    .profile-card {
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    .profile-section-title {
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 15px;
        border-left: 4px solid #007bff;
        padding-left: 10px;
        color: #003f7f;
    }
    .profile-table td {
        padding: 6px 4px;
    }
    .profile-label {
        font-weight: 600;
        color: #333;
        width: 200px;
    }
    .avatar-box {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #ccc;
        margin: 0 auto 15px auto;
    }
    .avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="container" style="max-width: 1100px;">

    <h3 class="mb-4">📄 Hồ sơ sinh viên</h3>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert-overlay" id="successAlert">
            <div class="custom-alert-box">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3 fw-bold text-success">Thành công!</h4>
                <p class="mb-4 text-muted fs-5">{{ session('success') }}</p>
                <button type="button" class="btn btn-success px-5 rounded-pill shadow-sm" onclick="document.getElementById('successAlert').remove()">OK</button>
            </div>
        </div>
        <style>
            .alert-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.4);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(3px);
            }
            .custom-alert-box {
                background: white;
                padding: 40px 30px;
                border-radius: 20px;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                max-width: 400px;
                width: 90%;
                animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
            @keyframes popIn {
                0% { transform: scale(0.8); opacity: 0; }
                100% { transform: scale(1); opacity: 1; }
            }
        </style>
        <script>
            // Tự động tắt sau 3 giây
            setTimeout(function() {
                const alert = document.getElementById('successAlert');
                if(alert) alert.remove();
            }, 3000);
        </script>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    {{-- ====================== THÔNG TIN HỌC VẤN + AVATAR ====================== --}}
    <div class="profile-card">
        <div class="profile-section-title">Thông tin học vấn</div>

        <div class="row">
            {{-- AVATAR --}}
            <div class="col-md-3 text-center">
                <div class="position-relative d-inline-block mb-3">
                    @if(!empty($sinhvien->HinhAnh) && file_exists(public_path($sinhvien->HinhAnh)))
                        <img id="avatarPreview" 
                             src="{{ asset($sinhvien->HinhAnh) }}?v={{ time() }}" 
                             class="rounded-circle object-fit-cover"
                             style="width: 150px; height: 150px; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    @else
                        <div id="avatarPreview" 
                             class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                             style="width: 150px; height: 150px; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <i class="bi bi-person-fill text-secondary" style="font-size: 80px;"></i>
                        </div>
                    @endif

                    <!-- Icon đổi ảnh -->
                    <label for="avatarUpload" 
                           class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 cursor-pointer"
                           style="cursor: pointer; transform: translate(10%, 10%);">
                        <i class="bi bi-camera-fill text-primary"></i>
                    </label>
                </div>

                {{-- FORM UPLOAD AVATAR (Hidden) --}}
                <form id="avatarForm" method="POST" action="{{ route('sinhvien.profile.updateAvatar') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="avatarUpload" name="avatar" class="d-none" accept="image/*">
                </form>
                
                <div class="small text-muted">Nhấn vào biểu tượng máy ảnh để thay đổi</div>
            </div>

            {{-- BẢNG THÔNG TIN --}}
            <div class="col-md-9">
                <table class="profile-table w-100">
                    <tr>
                        <td class="profile-label">MSSV:</td>
                        <td>{{ $sinhvien->MaSV }}</td>
                        <td class="profile-label">Mã hồ sơ:</td>
                        <td>{{ $sinhvien->MaSV }}</td>
                    </tr>
                    <tr>
                        <td class="profile-label">Trạng thái:</td>
                        <td>{{ $sinhvien->TrangThai }}</td>
                        <td class="profile-label">Ngày vào trường:</td>
                        <td>{{ $sinhvien->NgayVaoTruong ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="profile-label">Lớp học:</td>
                        <td>{{ $sinhvien->lop->TenLop ?? '' }}</td>
                        <td class="profile-label">Loại hình đào tạo:</td>
                        <td>Chính quy</td>
                    </tr>
                    <tr>
                        <td class="profile-label">Khoa:</td>
                        <td>{{ $sinhvien->khoa->TenKhoa ?? '' }}</td>
                        <td class="profile-label">Ngành:</td>
                        <td>{{ $sinhvien->nganh->TenNganh ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="profile-label">Niên khóa:</td>
                        <td>{{ $sinhvien->namhoc->TenNamHoc ?? '' }}</td>
                        <td class="profile-label">Bậc đào tạo:</td>
                        <td>{{ $sinhvien->BacDaoTao }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- ====================== THÔNG TIN CÁ NHÂN ====================== --}}
    {{-- ====================== THÔNG TIN CÁ NHÂN ====================== --}}
    <div class="profile-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="profile-section-title mb-0">Thông tin cá nhân</div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                <i class="bi bi-pencil-square me-1"></i> Cập nhật thông tin
            </button>
        </div>

        <table class="profile-table w-100">
            <tr>
                <td class="profile-label">Họ tên:</td>
                <td>{{ $sinhvien->TenSV }}</td>
                <td class="profile-label">Giới tính:</td>
                <td>{{ $sinhvien->GioiTinh }}</td>
            </tr>
            <tr>
                <td class="profile-label">Ngày sinh:</td>
                <td>{{ \Carbon\Carbon::parse($sinhvien->NgaySinh)->format('d/m/Y') }}</td>
                <td class="profile-label">Dân tộc:</td>
                <td>{{ $sinhvien->DanToc }}</td>
            </tr>
            <tr>
                <td class="profile-label">CCCD:</td>
                <td>{{ $sinhvien->MaCCCD }}</td>
                <td class="profile-label">Tôn giáo:</td>
                <td>{{ $sinhvien->TonGiao }}</td>
            </tr>
            <tr>
                <td class="profile-label">Số điện thoại:</td>
                <td>{{ $sinhvien->SDT }}</td>
                <td class="profile-label">Email:</td>
                <td>{{ $sinhvien->Email }}</td>
            </tr>
            <tr>
                <td class="profile-label">Nơi sinh:</td>
                <td>{{ $sinhvien->NoiSinh }}</td>
                <td class="profile-label">Hộ khẩu thường trú:</td>
                <td>{{ $sinhvien->HKTT }}</td>
            </tr>
        </table>
    </div>

    {{-- MODAL CẬP NHẬT THÔNG TIN --}}
    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Cập nhật thông tin cá nhân</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sinhvien.profile.update') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            {{-- READ ONLY FIELDS --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">MSSV (Không thể sửa)</label>
                                <input type="text" class="form-control bg-light" value="{{ $sinhvien->MaSV }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="TenSV" class="form-control" value="{{ $sinhvien->TenSV }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Giới tính</label>
                                <select name="GioiTinh" class="form-select">
                                    <option value="Nam" {{ $sinhvien->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $sinhvien->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Ngày sinh <span class="text-danger">*</span></label>
                                <input type="date" name="NgaySinh" class="form-control" value="{{ $sinhvien->NgaySinh ? $sinhvien->NgaySinh->format('Y-m-d') : '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">CCCD <span class="text-danger">*</span></label>
                                <input type="text" name="MaCCCD" class="form-control" value="{{ $sinhvien->MaCCCD }}" 
                                       required pattern="\d{12}" maxlength="12"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                                <div class="invalid-feedback">CCCD phải gồm 12 chữ số.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Dân tộc</label>
                                <input type="text" name="DanToc" class="form-control" value="{{ $sinhvien->DanToc }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tôn giáo</label>
                                <input type="text" name="TonGiao" class="form-control" value="{{ $sinhvien->TonGiao }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nơi sinh</label>
                                <input type="text" name="NoiSinh" class="form-control" value="{{ $sinhvien->NoiSinh }}">
                            </div>

                            {{-- CONTACT FIELDS --}}
                            <div class="col-md-12"><hr class="my-2"></div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="SDT" class="form-control" value="{{ $sinhvien->SDT }}" 
                                       required pattern="\d{10}" maxlength="10"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                                <div class="invalid-feedback">SĐT phải là 10 chữ số.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Email <span class="text-danger">*</span></label>
                                <input type="email" name="Email" class="form-control" value="{{ $sinhvien->Email }}" required>
                                <div class="invalid-feedback">Email không hợp lệ.</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Hộ khẩu thường trú</label>
                                <input type="text" name="HKTT" class="form-control" value="{{ $sinhvien->HKTT }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary fw-bold">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>

@endsection

@section('scripts')
<script>
    document.getElementById('avatarUpload').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            document.getElementById('avatarForm').submit();
        }
    });

    // Bootstrap Validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
@endsection
