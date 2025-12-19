{{-- @extends('layouts.sinhvien')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary">📘 Danh sách đề tài đang mở đăng ký</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr class="bg-light">
                <th>Tên đề tài</th>
                <th>Lĩnh vực</th>
                <th>Giảng viên hướng dẫn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detais as $d)
                <tr>
                    <td>{{ $d->TenDeTai }}</td>
                    <td>{{ $d->LinhVuc }}</td>
                    <td>{{ $d->giangVien->TenGV ?? 'Chưa có' }}</td>
                    <td>
                        @if($deTaiDaDangKy && $deTaiDaDangKy->MaDeTai == $d->MaDeTai)
                            <form method="POST" action="{{ route('sinhvien.detai.huy', $d->MaDeTai) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hủy đăng ký</button>
                            </form>
                        @elseif($deTaiDaDangKy)
                            <button class="btn btn-secondary btn-sm" disabled>Đã đăng ký đề tài khác</button>
                        @else
                            <form method="POST" action="{{ route('sinhvien.detai.dangky', $d->MaDeTai) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Đăng ký</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection --}}

@extends('layouts.sinhvien')
@section('content')
<style>
    .content-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 40px;
        animation: fadeInUp 0.6s ease-out;
        margin-top: 20px;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .page-title {
        color: #2563eb;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .page-title i {
        font-size: 2.5rem;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .custom-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .custom-table thead {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
    }
    
    .custom-table thead th {
        border: none;
        padding: 18px 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .custom-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .custom-table tbody tr:hover {
        background: linear-gradient(to right, #eff6ff 0%, #dbeafe 100%);
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(37,99,235,0.1);
    }
    
    .custom-table tbody td {
        padding: 20px;
        vertical-align: middle;
        font-size: 0.95rem;
    }
    
    .topic-name {
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .topic-name i {
        color: #2563eb;
        font-size: 1.2rem;
    }
    
    .field-badge {
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .field-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59,130,246,0.4);
    }
    
    .instructor-name {
        color: #475569;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }
    
    .instructor-name i {
        color: #2563eb;
    }
    
    .btn-register {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(37,99,235,0.3);
    }
    
    .btn-register:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(37,99,235,0.4);
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
    }
    
    .btn-cancel {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(239,68,68,0.3);
    }
    
    .btn-cancel:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(239,68,68,0.4);
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }
    
    .btn-disabled {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        cursor: not-allowed;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .alert-custom {
        border-radius: 15px;
        border: none;
        padding: 20px;
        margin-bottom: 30px;
        animation: slideInDown 0.5s ease-out;
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 500;
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(16,185,129,0.3);
    }
    
    .alert-danger-custom {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(239,68,68,0.3);
    }
    
    .alert-custom i {
        font-size: 1.5rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }
    
    .empty-state i {
        font-size: 5rem;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
        .content-card {
            padding: 20px;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .custom-table {
            font-size: 0.85rem;
        }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mt-4">
    <div class="content-card">
            <h3 class="page-title">
                <i class="fas fa-book-open"></i>
                Danh sách đề tài đang mở đăng ký
            </h3>
            
            @if(session('success'))
                <div class="alert alert-success-custom alert-custom">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger-custom alert-custom">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            {{-- Bộ lọc tìm kiếm --}}
            <form method="GET" action="{{ route('sinhvien.detai.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group" style="border-radius: 15px; overflow: hidden; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                            <span class="input-group-text bg-primary text-white border-0" style="padding: 12px 20px;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   name="giangvien" 
                                   value="{{ request('giangvien') }}"
                                   class="form-control border-0" 
                                   placeholder="Tìm kiếm theo tên giảng viên..."
                                   style="padding: 12px 20px; font-size: 0.95rem;">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary" style="border-radius: 15px; padding: 12px 30px; font-weight: 600;">
                            <i class="fas fa-filter me-2"></i>Lọc
                        </button>
                        <a href="{{ route('sinhvien.detai.index') }}" class="btn btn-secondary" style="border-radius: 15px; padding: 12px 30px; font-weight: 600;">
                            <i class="fas fa-redo me-2"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>
            
            
            @if($detaisByGiangVien->count() > 0)
                <div class="accordion" id="accordionGiangVien">
                    @foreach($detaisByGiangVien as $maGV => $detais)
                        @php
                            $giangVien = $detais->first()->giangVien;
                            $tenGV = $giangVien ? $giangVien->TenGV : 'Chưa có giảng viên';
                            $accordionId = 'collapse_' . ($maGV == 'chua_co' ? 'none' : $maGV);
                        @endphp
                        
                        <div class="accordion-item mb-3" style="border: none; border-radius: 15px; overflow: hidden; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $accordionId }}" aria-expanded="false" aria-controls="{{ $accordionId }}"
                                        style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; font-weight: 600; padding: 20px 25px; border-radius: 15px;">
                                    <i class="fas fa-chalkboard-teacher me-3" style="font-size: 1.3rem;"></i>
                                    <span style="font-size: 1.1rem;">{{ $tenGV }}</span>
                                    <span class="badge bg-white text-primary ms-3" style="font-size: 0.9rem;">{{ $detais->count() }} đề tài</span>
                                </button>
                            </h2>
                            <div id="{{ $accordionId }}" class="accordion-collapse collapse" data-bs-parent="#accordionGiangVien">
                                <div class="accordion-body" style="padding: 0;">
                                    <table class="table mb-0">
                                        <thead style="background-color: #f8f9fa;">
                                            <tr>
                                                <th style="padding: 15px 20px;"><i class="fas fa-file-alt me-2"></i>Tên đề tài</th>
                                                <th style="padding: 15px 20px;"><i class="fas fa-info-circle me-2"></i>Mô tả</th>
                                                <th style="padding: 15px 20px;"><i class="fas fa-layer-group me-2"></i>Lĩnh vực</th>
                                                <th style="padding: 15px 20px;"><i class="fas fa-users me-2"></i>Thành viên</th>
                                                <th style="padding: 15px 20px;"><i class="fas fa-calendar-alt me-2"></i>Hạn nộp</th>
                                                <th style="padding: 15px 20px;"><i class="fas fa-cogs me-2"></i>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detais as $d)
                                                <tr style="transition: all 0.3s ease;">
                                                    <td style="padding: 18px 20px;">
                                                        <div class="topic-name">
                                                            <i class="fas fa-bookmark"></i>
                                                            <span>{{ $d->TenDeTai }}</span>
                                                        </div>
                                                    </td>
                                                    <td style="padding: 18px 20px;">
                                                        <span class="text-muted">{{ Str::limit($d->MoTa, 100) }}</span>
                                                    </td>
                                                    <td style="padding: 18px 20px;">
                                                        <span class="field-badge">
                                                            <i class="fas fa-tag"></i>
                                                            {{ $d->LinhVuc }}
                                                        </span>
                                                    </td>
                                                    <td style="padding: 18px 20px;">
                                                        @php
                                                            $maxMembers = $d->SoLuongThanhVien ?? 1;
                                                            $currentMembers = $d->sinh_viens_count ?? 0;
                                                            $isFull = $currentMembers > $maxMembers; // Đã đủ khi current > max
                                                        @endphp
                                                        <span class="badge {{ $isFull ? 'bg-danger' : 'bg-primary' }}" style="font-size: 0.9rem; padding: 8px 15px; border-radius: 20px;">
                                                            <i class="fas fa-user-friends me-1"></i>
                                                            {{ $currentMembers }}/{{ $maxMembers }}
                                                            @if($maxMembers == 1)
                                                                <i class="fas fa-user ms-1" title="Cá nhân"></i>
                                                            @else
                                                                <i class="fas fa-users ms-1" title="Nhóm"></i>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td style="padding: 18px 20px;">
                                                        @if($d->DeadlineBaoCao)
                                                            <span class="text-danger fw-bold">
                                                                <i class="far fa-clock me-1"></i>
                                                                {{ date('d/m/Y H:i', strtotime($d->DeadlineBaoCao)) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">Chưa có</span>
                                                        @endif
                                                    </td>
                                                    <td style="padding: 18px 20px;">
                                                        {{-- Nút Xem chi tiết --}}
                                                        <button class="btn btn-info btn-sm me-2" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#detailModal"
                                                                data-id="{{ $d->MaDeTai }}"
                                                                data-ten="{{ $d->TenDeTai }}"
                                                                data-mota="{{ $d->MoTa }}"
                                                                data-linhvuc="{{ $d->LinhVuc }}"
                                                                data-loai="{{ $d->LoaiDeTai }}"
                                                                data-gv="{{ $d->giangVien->TenGV ?? 'Chưa có' }}"
                                                                data-khoa="{{ $d->giangVien->khoa->TenKhoa ?? 'Chưa có' }}"
                                                                data-deadline="{{ $d->DeadlineBaoCao ? date('d/m/Y H:i', strtotime($d->DeadlineBaoCao)) : 'Chưa có' }}"
                                                                style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none; border-radius: 20px; padding: 8px 20px; color: white; font-weight: 600;">
                                                            <i class="fas fa-eye me-1"></i>
                                                            Xem chi tiết
                                                        </button>
                                                        
                                                        @php
                                                            $maxMembers = $d->SoLuongThanhVien ?? 1;
                                                            $currentMembers = $d->sinh_viens_count ?? 0;
                                                            $isFull = $currentMembers >= $maxMembers; // Đã đủ khi current >= max
                                                        @endphp
                                                        
                                                        @if($deTaiDaDangKy && $deTaiDaDangKy->MaDeTai == $d->MaDeTai)
                                                            {{-- Sinh viên này đã đăng ký đề tài này --}}
                                                            <form method="POST" action="{{ route('sinhvien.detai.huy', $d->MaDeTai) }}" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-cancel">
                                                                    <i class="fas fa-times-circle"></i>
                                                                    Hủy đăng ký
                                                                </button>
                                                            </form>
                                                        @elseif($isFull)
                                                            {{-- Đề tài đã đủ số lượng --}}
                                                            <button class="btn btn-disabled" disabled>
                                                                <i class="fas fa-user-check"></i>
                                                                Đã đủ thành viên
                                                            </button>
                                                        @elseif($deTaiDaDangKy)
                                                            {{-- Sinh viên đã đăng ký đề tài khác --}}
                                                            <button class="btn btn-disabled" disabled>
                                                                <i class="fas fa-lock"></i>
                                                                Đã đăng ký đề tài khác
                                                            </button>
                                                        @else
                                                            {{-- Có thể đăng ký --}}
                                                            @if(isset($config) && $now->lt($config->ThoiGianMoDangKy))
                                                                {{-- Chưa đến thời gian mở đăng ký, không hiển thị nút đăng ký --}}
                                                            @else
                                                                <form method="POST" action="{{ route('sinhvien.detai.dangky', $d->MaDeTai) }}" style="display: inline;">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-register">
                                                                        <i class="fas fa-check-circle"></i>
                                                                        Đăng ký
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>Không có đề tài nào đang mở đăng ký</h4>
                    <p>Vui lòng quay lại sau để xem các đề tài mới</p>
                </div>
            @endif
        </div>
    </div>
</div>


{{-- Modal Xem Chi Tiết Đề Tài --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); border-radius: 20px 20px 0 0; padding: 25px 30px;">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-info-circle me-2"></i>
                    Chi tiết đề tài
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="detail-label">
                                <i class="fas fa-bookmark text-primary me-2"></i>
                                Tên đề tài
                            </label>
                            <div class="detail-value" id="detailTenDeTai"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">
                                <i class="fas fa-tag text-primary me-2"></i>
                                Lĩnh vực
                            </label>
                            <div class="detail-value" id="detailLinhVuc"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">
                                <i class="fas fa-layer-group text-primary me-2"></i>
                                Loại đề tài
                            </label>
                            <div class="detail-value" id="detailLoaiDeTai"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">
                                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                                Giảng viên hướng dẫn
                            </label>
                            <div class="detail-value" id="detailGiangVien"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">
                                <i class="fas fa-building text-primary me-2"></i>
                                Khoa
                            </label>
                            <div class="detail-value" id="detailKhoa"></div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="detail-label">
                                <i class="fas fa-calendar-alt text-danger me-2"></i>
                                Hạn nộp báo cáo
                            </label>
                            <div class="detail-value text-danger fw-bold" id="detailDeadline"></div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="detail-label">
                                <i class="fas fa-align-left text-primary me-2"></i>
                                Mô tả chi tiết
                            </label>
                            <div class="detail-value" id="detailMoTa" style="white-space: pre-wrap;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 12px;">
                    <i class="fas fa-times me-2"></i>Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.detail-item {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 12px;
    border-left: 4px solid #2563eb;
}

.detail-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: block;
}

.detail-value {
    font-size: 1rem;
    color: #1e293b;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle detail modal
    const detailModal = document.getElementById('detailModal');
    detailModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        
        document.getElementById('detailTenDeTai').textContent = button.getAttribute('data-ten');
        document.getElementById('detailMoTa').textContent = button.getAttribute('data-mota') || 'Chưa có mô tả';
        document.getElementById('detailLinhVuc').textContent = button.getAttribute('data-linhvuc');
        document.getElementById('detailLoaiDeTai').textContent = button.getAttribute('data-loai') || 'Chưa xác định';
        document.getElementById('detailGiangVien').textContent = button.getAttribute('data-gv');
        document.getElementById('detailKhoa').textContent = button.getAttribute('data-khoa');
        document.getElementById('detailDeadline').textContent = button.getAttribute('data-deadline');
    });
});
</script>

{{-- Confirmation Modal for Registration --}}
<div class="modal fade" id="confirmRegisterModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="question-icon" style="width: 100px; height: 100px; margin: 0 auto; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">
                        <i class="fas fa-question-circle" style="font-size: 50px; color: #2563eb;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" style="color: #1f2937;">Bạn có muốn đăng ký đề tài này?</h4>
                <p class="text-muted mb-2" id="registerTopicName" style="font-size: 1.1rem;"></p>
                <div class="alert alert-info border-0 mt-3 mb-4" style="background: #dbeafe; color: #1e40af; border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <small><strong>Lưu ý:</strong> Bạn chỉ được đăng ký một đề tài duy nhất!</small>
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-light px-5 py-2 fw-semibold" data-bs-dismiss="modal" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                        <i class="fas fa-times me-2"></i>Không
                    </button>
                    <button type="button" class="btn btn-primary px-5 py-2 fw-semibold" id="confirmRegisterBtn" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3); background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);">
                        <i class="fas fa-check-circle me-2"></i>Có, đăng ký
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Confirmation Modal for Cancellation --}}
<div class="modal fade" id="confirmCancelModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="warning-icon" style="width: 100px; height: 100px; margin: 0 auto; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 50px; color: #dc2626;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" style="color: #1f2937;">Bạn có chắc chắn muốn hủy?</h4>
                <p class="text-muted mb-2" id="cancelTopicName" style="font-size: 1.1rem;"></p>
                <div class="alert alert-warning border-0 mt-3 mb-4" style="background: #fef3c7; color: #92400e; border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <small><strong>Lưu ý:</strong> Bạn sẽ phải đăng ký lại nếu muốn chọn đề tài này!</small>
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-light px-5 py-2 fw-semibold" data-bs-dismiss="modal" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                        <i class="fas fa-times me-2"></i>Không
                    </button>
                    <button type="button" class="btn btn-danger px-5 py-2 fw-semibold" id="confirmCancelBtn" style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);">
                        <i class="fas fa-trash-alt me-2"></i>Có, hủy đăng ký
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Success Notification Modal --}}
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="success-icon" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: scaleIn 0.5s ease-out;">
                        <i class="fas fa-check" style="font-size: 40px; color: #28a745;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" id="successTitle">Thành công!</h4>
                <p class="text-muted mb-4" id="successMessage"></p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" onclick="window.location.reload()" style="border-radius: 10px;">
                        Quay lại danh sách
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.9;
    }
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>

<script>
    // Animation cho các hàng khi load trang
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.custom-table tbody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, index * 100);
        });
        
        const confirmRegisterModal = new bootstrap.Modal('#confirmRegisterModal');
        const confirmCancelModal = new bootstrap.Modal('#confirmCancelModal');
        const successModal = new bootstrap.Modal('#successModal');
        
        let currentForm = null;
        
        // Helper function to show notification
        function showNotification(isSuccess, title, message) {
            const modalTitle = document.getElementById('successTitle');
            const modalMessage = document.getElementById('successMessage');
            const modalIcon = document.querySelector('#successModal .success-icon');
            const modalIconI = document.querySelector('#successModal .success-icon i');
            const modalBtn = document.querySelector('#successModal .btn');
            
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            
            if (isSuccess) {
                modalIcon.style.background = 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)';
                modalIconI.className = 'fas fa-check';
                modalIconI.style.color = '#28a745';
                modalBtn.className = 'btn btn-success btn-lg';
                modalBtn.onclick = function() { window.location.reload(); };
            } else {
                modalIcon.style.background = 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)';
                modalIconI.className = 'fas fa-times';
                modalIconI.style.color = '#dc3545';
                modalBtn.className = 'btn btn-danger btn-lg';
                modalBtn.textContent = 'Đóng';
                modalBtn.onclick = function() { successModal.hide(); };
            }
            
            successModal.show();
            
            if (isSuccess) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }
        
        // Xác nhận trước khi đăng ký
        const registerForms = document.querySelectorAll('form[action*="dangky"]');
        registerForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                currentForm = this;
                
                // Get topic name from the row
                const row = this.closest('tr');
                const topicName = row.querySelector('.topic-name span').textContent;
                document.getElementById('registerTopicName').textContent = `Đăng ký đề tài: "${topicName}"`;
                
                confirmRegisterModal.show();
            });
        });
        
        // Confirm register button
        document.getElementById('confirmRegisterBtn').addEventListener('click', function() {
            if (currentForm) {
                confirmRegisterModal.hide();
                
                const formData = new FormData(currentForm);
                fetch(currentForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(true, 'Thành công!', data.message || 'Đăng ký đề tài thành công!');
                    } else {
                        showNotification(false, 'Thất bại!', data.message || 'Có lỗi xảy ra khi đăng ký.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification(false, 'Lỗi hệ thống!', 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.');
                });
            }
        });
        
        // Xác nhận trước khi hủy đăng ký
        const cancelForms = document.querySelectorAll('form[action*="huy"]');
        cancelForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                currentForm = this;
                
                // Get topic name from the row
                const row = this.closest('tr');
                const topicName = row.querySelector('.topic-name span').textContent;
                document.getElementById('cancelTopicName').textContent = `Hủy đăng ký đề tài: "${topicName}"`;
                
                confirmCancelModal.show();
            });
        });
        
        // Confirm cancel button
        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            if (currentForm) {
                confirmCancelModal.hide();
                
                const formData = new FormData(currentForm);
                fetch(currentForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(true, 'Thành công!', data.message || 'Hủy đăng ký thành công!');
                    } else {
                        showNotification(false, 'Thất bại!', data.message || 'Có lỗi xảy ra khi hủy đăng ký.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification(false, 'Lỗi hệ thống!', 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.');
                });
            }
        });
        
        // Tự động ẩn thông báo sau 5 giây
        const alerts = document.querySelectorAll('.alert-custom');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });
</script>
@endsection