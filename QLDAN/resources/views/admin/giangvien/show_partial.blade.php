<div style="padding: 1.5rem;">
    <h5 style="color: #1f2937; font-weight: 700; margin-bottom: 1.5rem;">
        Chi tiết Giảng viên: {{ $gv->TenGV }} ({{ $gv->MaGV }})
    </h5>

    <div class="row g-3">
        <div class="col-md-6">
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Mã GV:</strong>
                <span style="color: #1f2937;">{{ $gv->MaGV }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Họ tên:</strong>
                <span style="color: #1f2937;">{{ $gv->TenGV }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Giới tính:</strong>
                <span style="color: #1f2937;">{{ $gv->GioiTinh ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Ngày sinh:</strong>
                <span style="color: #1f2937;">{{ $gv->NgaySinh ? $gv->NgaySinh->format('d/m/Y') : '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">SĐT:</strong>
                <span style="color: #1f2937;">{{ $gv->SDT ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Email:</strong>
                <span style="color: #1f2937;">{{ $gv->Email ?? '-' }}</span>
            </div>
        </div>

        <div class="col-md-6">
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">CCCD:</strong>
                <span style="color: #1f2937;">{{ $gv->MaCCCD ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Khoa:</strong>
                <span style="color: #1f2937;">{{ $gv->khoa->TenKhoa ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Ngành:</strong>
                <span style="color: #1f2937;">{{ $gv->nganh->TenNganh ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Học vị:</strong>
                <span style="color: #1f2937;">{{ $gv->HocVi ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Học hàm:</strong>
                <span style="color: #1f2937;">{{ $gv->HocHam ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Chuyên ngành:</strong>
                <span style="color: #1f2937;">{{ $gv->ChuyenNganh ?? '-' }}</span>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                <strong style="color: #6b7280;">Tài khoản:</strong>
                @if($gv->taiKhoan)
                    <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                        {{ $gv->taiKhoan->MaSo }}
                    </span>
                @else
                    <span style="color: #9ca3af;">-</span>
                @endif
            </div>
        </div>
    </div>

    @if($gv->cccd)
        <div style="margin-top: 1.5rem; padding: 1rem; background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 8px;">
            <h6 style="color: #1e40af; font-weight: 600; margin-bottom: 0.75rem;">
                <i class="fas fa-id-card me-2"></i>CCCD (chi tiết)
            </h6>
            <p style="margin-bottom: 0.5rem;"><strong>Ngày cấp:</strong> {{ $gv->cccd->NgayCap ? $gv->cccd->NgayCap->format('d/m/Y') : '-' }}</p>
            <p style="margin-bottom: 0;"><strong>Nơi cấp:</strong> {{ $gv->cccd->NoiCap ?? '-' }}</p>
        </div>
    @endif

    @if($gv->detais && $gv->detais->count() > 0)
        <div style="margin-top: 1.5rem; padding: 1rem; background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 8px;">
            <h6 style="color: #065f46; font-weight: 600; margin-bottom: 0.75rem;">
                <i class="fas fa-book me-2"></i>Danh sách đề tài ({{ $gv->detais->count() }})
            </h6>
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($gv->detais as $detai)
                    <li style="margin-bottom: 0.5rem;">
                        {{ $detai->TenDeTai }} 
                        <span style="color: #6b7280; font-size: 0.875rem;">({{ $detai->TrangThai ?? '-' }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="modal-footer" style="background: #f9fafb; padding: 1rem 1.5rem;">
    <button type="button" class="btn" style="background: #e5e7eb; color: #4b5563; padding: 0.625rem 1.5rem; border-radius: 8px; border: none; font-weight: 600;" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>Đóng
    </button>
</div>
