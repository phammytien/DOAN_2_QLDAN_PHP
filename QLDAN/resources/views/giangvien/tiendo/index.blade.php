@extends('layouts.giangvien')

@section('content')
<style>
    body {
        background-color: #f5f7fa;
    }
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        border-left: 4px solid #4f46e5;
    }
    .page-header h4 {
        margin: 0;
        color: #1f2937;
        font-size: 1.25rem;
        font-weight: 600;
    }
    .progress-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .table thead th {
        background-color: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }
    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #374151;
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    .btn-add-progress {
        background: #10b981;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-add-progress:hover {
        background: #059669;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-view-progress {
        background: #06b6d4;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-view-progress:hover {
        background: #0891b2;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }
    .student-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .student-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
    }
    .student-badge {
        background: #eff6ff;
        color: #1e40af;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .progress-count {
        background: #f3f4f6;
        color: #374151;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-no-progress {
        background-color: #fef3c7;
        color: #92400e;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <h4>
            <i class="bi bi-list-check me-2"></i>
            Quản lý tiến độ đề tài
        </h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalThem">
            <i class="bi bi-plus-lg me-2"></i>Thêm mốc tiến độ
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="progress-table">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th>Mã đề tài</th>
                        <th>Tên đề tài</th>
                        <th>Sinh viên đăng ký</th>
                        <th style="width: 120px;" class="text-center">Tiến độ</th>
                        <th style="width: 150px;" class="text-center">Trạng thái nộp</th>
                        <th style="width: 180px;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detais as $index => $detai)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                        
                        {{-- Mã đề tài --}}
                        <td>
                            <span class="badge bg-primary">{{ $detai->MaDeTai }}</span>
                        </td>
                        
                        {{-- Tên đề tài --}}
                        <td>
                            <div class="fw-semibold text-dark" style="max-width: 300px;">
                                {{ Str::limit($detai->TenDeTai, 80) }}
                            </div>
                        </td>

                        {{-- Sinh viên đăng ký --}}
                        <td>
                            <div class="student-list">
                                @foreach($detai->sinhViens as $sv)
                                    <div class="student-item">
                                        <i class="bi bi-person-circle text-primary"></i>
                                        <span>{{ $sv->HoTen ?? $sv->TenSV }}</span>
                                        @if($sv->lop)
                                            <span class="student-badge">{{ $sv->lop->TenLop }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- Số lượng tiến độ --}}
                        <td class="text-center">
                            @php
                                $progressCount = $detai->tiendos->count();
                            @endphp
                            @if($progressCount > 0)
                                <span class="progress-count">
                                    <i class="bi bi-check-circle text-success"></i>
                                    {{ $progressCount }} tiến độ
                                </span>
                            @else
                                <span class="status-no-progress">
                                    Chưa có
                                </span>
                            @endif
                        </td>

                        {{-- Trạng thái nộp --}}
                        <td class="text-center">
                            @php
                                // Đếm số tiến độ đã nộp và chưa nộp
                                $submitted = $detai->tiendos->where('NgayNop', '!=', null)->count();
                                $notSubmitted = $detai->tiendos->where('NgayNop', null)->count();
                                $total = $detai->tiendos->count();
                            @endphp
                            
                            @if($total == 0)
                                <span class="badge bg-secondary">Chưa có tiến độ</span>
                            @elseif($notSubmitted == 0)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill"></i> Đã nộp hết
                                </span>
                            @elseif($submitted == 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock-fill"></i> Chưa nộp
                                </span>
                            @else
                                <span class="badge bg-info">
                                    <i class="bi bi-hourglass-split"></i> {{ $submitted }}/{{ $total }} đã nộp
                                </span>
                            @endif
                        </td>

                        {{-- HÀNH ĐỘNG --}}
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- Nút thêm tiến độ --}}
                                <button class="btn-add-progress" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalThem"
                                        data-detai="{{ $detai->MaDeTai }}"
                                        title="Thêm tiến độ">
                                    <i class="bi bi-plus"></i>
                                    Thêm
                                </button>
                                
                                {{-- Nút xem chi tiết tiến độ nếu có --}}
                                @if($progressCount > 0)
                                    <button class="btn-view-progress" 
                                            data-bs-toggle="collapse"
                                            data-bs-target="#detail{{ $detai->MaDeTai }}"
                                            title="Xem chi tiết tiến độ">
                                        <i class="bi bi-eye"></i>
                                        Chi tiết
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Chi tiết tiến độ (collapse) --}}
                    @if($progressCount > 0)
                    <tr class="collapse" id="detail{{ $detai->MaDeTai }}">
                        <td colspan="7" class="p-0">
                            <div class="bg-light p-3">
                                <h6 class="mb-3"><i class="bi bi-list-check"></i> Danh sách tiến độ của đề tài: {{ $detai->TenDeTai }}</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered bg-white">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th style="width: 50px;">STT</th>
                                                <th>Nội dung</th>
                                                <th style="width: 120px;">Deadline</th>
                                                <th style="width: 150px;">Ngày nộp</th>
                                                <th style="width: 120px;">File đính kèm</th>
                                                <th style="width: 100px;">Trạng thái</th>
                                                <th style="width: 150px;">Sinh viên</th>
                                                <th style="width: 80px;">Điểm</th>
                                                <th style="width: 150px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detai->tiendos as $idx => $td)
                                                @php
                                                    $studentCount = $detai->sinhViens->count();
                                                @endphp
                                                
                                                @if($studentCount > 1)
                                                    {{-- Nếu có nhiều sinh viên, hiển thị từng sinh viên --}}
                                                    @foreach($detai->sinhViens as $svIdx => $sv)
                                                    <tr>
                                                        @if($svIdx == 0)
                                                            {{-- Chỉ hiển thị thông tin tiến độ ở dòng đầu tiên --}}
                                                            <td rowspan="{{ $studentCount }}">{{ $idx + 1 }}</td>
                                                            <td rowspan="{{ $studentCount }}">{{ $td->NoiDung }}</td>
                                                            <td rowspan="{{ $studentCount }}">
                                                                @if($td->Deadline)
                                                                    {{ \Carbon\Carbon::parse($td->Deadline)->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td rowspan="{{ $studentCount }}">
                                                                @if($td->NgayNop)
                                                                    {{ \Carbon\Carbon::parse($td->NgayNop)->format('d/m/Y H:i') }}
                                                                @else
                                                                    <span class="text-muted">Chưa nộp</span>
                                                                @endif
                                                            </td>
                                                            <td rowspan="{{ $studentCount }}">
                                                                @if($td->fileCode)
                                                                    @php
                                                                        $pathCode = str_replace('\\', '/', $td->fileCode->path);
                                                                        $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                                                                    @endphp
                                                                    <a href="{{ $urlCode }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                        <i class="bi bi-file-earmark-arrow-down"></i> Xem file
                                                                    </a>
                                                                @elseif($td->LinkFile)
                                                                    <a href="{{ asset($td->LinkFile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                        <i class="bi bi-file-earmark-arrow-down"></i> Xem file
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted small">Không có</span>
                                                                @endif
                                                            </td>
                                                            <td rowspan="{{ $studentCount }}">
                                                                @if(!$td->NgayNop)
                                                                    <span class="badge bg-secondary">Chưa nộp</span>
                                                                @elseif($td->TrangThai == 'Xin nộp bổ sung')
                                                                    <span class="badge bg-warning text-dark">Xin nộp bổ sung</span>
                                                                @elseif($td->Deadline && \Carbon\Carbon::parse($td->NgayNop)->gt(\Carbon\Carbon::parse($td->Deadline)))
                                                                    <span class="badge bg-danger">Trễ hạn</span>
                                                                @else
                                                                    <span class="badge bg-success">Đúng hạn</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        
                                                        {{-- Thông tin sinh viên --}}
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="bi bi-person-circle text-primary"></i>
                                                                <span>{{ $sv->HoTen ?? $sv->TenSV }}</span>
                                                            </div>
                                                        </td>
                                                        
                                                        {{-- Điểm của sinh viên này --}}
                                                        <td class="text-center">
                                                            @php
                                                                // Tìm điểm của sinh viên này cho tiến độ này
                                                                $chamDiem = \App\Models\ChamDiem::where('MaDeTai', $detai->MaDeTai)
                                                                    ->where('MaSV', $sv->MaSV)
                                                                    ->where('MaTienDo', $td->MaTienDo)
                                                                    ->first();
                                                            @endphp
                                                            @if($chamDiem && $chamDiem->Diem)
                                                                <strong class="text-success">{{ number_format($chamDiem->Diem, 1) }}</strong>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        
                                                        {{-- Nút chấm điểm cho sinh viên này --}}
                                                        <td class="text-center">
                                                            @if($td->TrangThai == 'Xin nộp bổ sung')
                                                                <form action="{{ route('giangvien.tiendo.approveLate', $td->MaTienDo) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-info text-white" title="Duyệt nộp bổ sung">
                                                                        <i class="bi bi-check-circle"></i> Duyệt
                                                                    </button>
                                                                </form>
                                                            @elseif($td->NgayNop && !in_array($detai->TrangThai, ['Hoàn thành', 'Đã hoàn thành']))
                                                                <a href="{{ route('giangvien.tiendo.chamDiem', $td->MaTienDo) }}?sv={{ $sv->MaSV }}" 
                                                                   class="btn btn-sm {{ $chamDiem && $chamDiem->Diem ? 'btn-success' : 'btn-primary' }}" 
                                                                   title="{{ $chamDiem && $chamDiem->Diem ? 'Đã chấm điểm' : 'Chấm điểm' }}">
                                                                    <i class="bi bi-{{ $chamDiem && $chamDiem->Diem ? 'check-circle-fill' : 'clipboard-check' }}"></i>
                                                                    {{ $chamDiem && $chamDiem->Diem ? 'Sửa' : 'Chấm' }}
                                                                </a>
                                                            @elseif(in_array($detai->TrangThai, ['Hoàn thành', 'Đã hoàn thành']))
                                                                <span class="badge bg-secondary">Hoàn thành</span>
                                                            @else
                                                                <span class="text-muted small">Chờ nộp</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    {{-- Nếu chỉ có 1 sinh viên, hiển thị bình thường --}}
                                                    <tr>
                                                        <td>{{ $idx + 1 }}</td>
                                                        <td>{{ $td->NoiDung }}</td>
                                                        <td>
                                                            @if($td->Deadline)
                                                                {{ \Carbon\Carbon::parse($td->Deadline)->format('d/m/Y') }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($td->NgayNop)
                                                                {{ \Carbon\Carbon::parse($td->NgayNop)->format('d/m/Y H:i') }}
                                                            @else
                                                                <span class="text-muted">Chưa nộp</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($td->fileCode)
                                                                @php
                                                                    $pathCode = str_replace('\\', '/', $td->fileCode->path);
                                                                    $urlCode = Str::startsWith($pathCode, 'storage/') ? asset($pathCode) : asset('storage/' . $pathCode);
                                                                @endphp
                                                                <a href="{{ $urlCode }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-file-earmark-arrow-down"></i> Xem file
                                                                </a>
                                                            @elseif($td->LinkFile)
                                                                <a href="{{ asset($td->LinkFile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-file-earmark-arrow-down"></i> Xem file
                                                                </a>
                                                            @else
                                                                <span class="text-muted small">Không có</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!$td->NgayNop && $td->TrangThai != 'Xin nộp bổ sung')
                                                                <span class="badge bg-secondary">Chưa nộp</span>
                                                            @elseif($td->TrangThai == 'Xin nộp bổ sung')
                                                                <span class="badge bg-warning text-dark">Xin nộp bổ sung</span>
                                                            @elseif($td->Deadline && \Carbon\Carbon::parse($td->NgayNop)->gt(\Carbon\Carbon::parse($td->Deadline)))
                                                                <span class="badge bg-danger">Trễ hạn</span>
                                                            @else
                                                                <span class="badge bg-success">Đúng hạn</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($detai->sinhViens->first())
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <i class="bi bi-person-circle text-primary"></i>
                                                                    <span>{{ $detai->sinhViens->first()->HoTen ?? $detai->sinhViens->first()->TenSV }}</span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($td->Diem)
                                                                <strong class="text-success">{{ number_format($td->Diem, 1) }}</strong>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($td->TrangThai == 'Xin nộp bổ sung')
                                                                <form action="{{ route('giangvien.tiendo.approveLate', $td->MaTienDo) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-info text-white" title="Duyệt nộp bổ sung">
                                                                        <i class="bi bi-check-circle"></i> Duyệt
                                                                    </button>
                                                                </form>
                                                            @elseif($td->NgayNop && !in_array($detai->TrangThai, ['Hoàn thành', 'Đã hoàn thành']))
                                                                <a href="{{ route('giangvien.tiendo.chamDiem', $td->MaTienDo) }}" 
                                                                   class="btn btn-sm {{ $td->Diem ? 'btn-success' : 'btn-primary' }}" 
                                                                   title="{{ $td->Diem ? 'Đã chấm điểm' : 'Chấm điểm' }}">
                                                                    <i class="bi bi-{{ $td->Diem ? 'check-circle-fill' : 'clipboard-check' }}"></i>
                                                                    {{ $td->Diem ? 'Sửa điểm' : 'Chấm điểm' }}
                                                                </a>
                                                            @elseif(in_array($detai->TrangThai, ['Hoàn thành', 'Đã hoàn thành']))
                                                                <span class="badge bg-secondary">Đã hoàn thành</span>
                                                            @else
                                                                <span class="text-muted small">Chờ nộp</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">Chưa có đề tài nào có sinh viên đăng ký</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Mới -->
<div class="modal fade" id="modalThem" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Thêm mốc tiến độ mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('giangvien.tiendo.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn đề tài</label>
                        <select name="MaDeTai" id="selectDeTai" class="form-select" required>
                            @foreach($detais as $dt)
                                <option value="{{ $dt->MaDeTai }}">{{ $dt->TenDeTai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung công việc</label>
                        <textarea name="NoiDung" class="form-control" rows="3" required placeholder="Ví dụ: Nộp chương 1, Viết báo cáo tuần..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hạn chót (Deadline)</label>
                        <input type="date" name="Deadline" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Khi click nút "Thêm" từ bảng, tự động chọn đề tài đó
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-add-progress[data-detai]').forEach(btn => {
        btn.addEventListener('click', function() {
            const maDeTai = this.dataset.detai;
            document.getElementById('selectDeTai').value = maDeTai;
        });
    });
});
</script>
@endsection
