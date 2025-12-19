@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-blue: #2563eb;
        --light-blue: #dbeafe;
        --dark-blue: #1e40af;
        --hover-blue: #3b82f6;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .modern-container {
        background: linear-gradient(135deg, var(--light-blue) 0%, #ffffff 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    .page-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
        border-left: 4px solid var(--primary-blue);
    }

    .page-header h3 {
        color: var(--primary-blue);
        font-weight: 700;
        font-size: 1.75rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .modern-select {
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        min-width: 250px;
    }

    .modern-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px var(--light-blue);
        outline: none;
    }

    .btn-modern {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-modern {
        background: var(--primary-blue);
        color: white;
    }

    .btn-primary-modern:hover {
        background: var(--dark-blue);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--gray-200);
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--hover-blue) 100%);
        color: white;
    }

    .modern-table thead th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid var(--gray-100);
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--gray-50);
    }

    .modern-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: var(--gray-600);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--gray-200);
        margin-bottom: 1rem;
    }
</style>

<div class="modern-container">
    <div class="page-header">
        <h3><i class="fas fa-users"></i> Danh sách sinh viên đăng ký đề tài</h3>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <label class="fw-bold text-muted mb-0"><i class="fas fa-filter me-2"></i>Lọc theo lớp:</label>
                <select class="modern-select form-select" id="classFilter" onchange="filterByClass()">
                    <option value="">-- Tất cả lớp --</option>
                    @php
                        $lops = \App\Models\Lop::withCount(['sinhViens' => function($q) {
                            $q->whereHas('detais');
                        }])->with('nganh')->get();
                    @endphp
                    @foreach($lops as $lop)
                        @if($lop->sinh_viens_count > 0)
                            <option value="{{ $lop->MaLop }}">{{ $lop->TenLop }} ({{ $lop->sinh_viens_count }} SV)</option>
                        @endif
                    @endforeach
                </select>
            </div>
            
            <a href="{{ route('admin.detai.dangky.export') }}" class="btn btn-modern btn-primary-modern" id="exportBtn">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <table class="table modern-table align-middle mb-0">
            <thead>
                <tr>
                    <th width="50px">#</th>
                    <th>Mã đề tài</th>
                    <th>Tên đề tài</th>
                    <th>Giảng viên hướng dẫn</th>
                    <th>Sinh viên 1</th>
                    <th>Lớp</th>
                    <th>Sinh viên 2</th>
                    <th>Lớp</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                @php
                    $counter = 1;
                    $detais = \App\Models\DeTai::with(['giangVien', 'sinhViens.lop'])->get();
                @endphp
                @forelse($detais as $detai)
                    @php
                        $sinhviens = $detai->sinhViens;
                        if ($sinhviens->isEmpty()) continue;
                        
                        $sv1 = $sinhviens->get(0);
                        $sv2 = $sinhviens->get(1);
                        
                        // Lấy mã lớp để filter
                        $maLop = $sv1->MaLop ?? ($sv2->MaLop ?? '');
                    @endphp
                    <tr data-class="{{ $maLop }}">
                        <td><strong>{{ $counter++ }}</strong></td>
                        <td><span class="badge bg-primary">{{ $detai->MaDeTai }}</span></td>
                        <td><strong>{{ $detai->TenDeTai }}</strong></td>
                        <td>{{ $detai->giangVien->TenGV ?? 'Chưa gán' }}</td>
                        
                        {{-- Sinh viên 1 --}}
                        <td>
                            @if($sv1)
                                <div><strong>{{ $sv1->HoTen ?? $sv1->TenSV ?? 'N/A' }}</strong></div>
                                <small class="text-muted">{{ $sv1->MaSV }}</small>
                            @else
                                <span class="text-muted"> Chưa đăng ký</span>
                            @endif
                        </td>
                        <td>
                            @if($sv1)
                                <span class="badge bg-secondary">{{ $sv1->lop->TenLop ?? 'N/A' }}</span>
                            @else
                                <span class="text-muted"> Chưa đăng ký</span>
                            @endif
                        </td>
                        
                        {{-- Sinh viên 2 --}}
                        <td>
                            @if($sv2)
                                <div><strong>{{ $sv2->HoTen ?? $sv2->TenSV ?? 'N/A' }}</strong></div>
                                <small class="text-muted">{{ $sv2->MaSV }}</small>
                            @else
                                <span class="text-muted"> Chưa đăng ký</span>
                            @endif
                        </td>
                        <td>
                            @if($sv2)
                                <span class="badge bg-secondary">{{ $sv2->lop->TenLop ?? 'N/A' }}</span>
                            @else
                                <span class="text-muted"> Chưa đăng ký</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p class="mb-0">Chưa có sinh viên nào đăng ký đề tài</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterByClass() {
    const selectedClass = document.getElementById('classFilter').value;
    const rows = document.querySelectorAll('#studentTableBody tr[data-class]');
    
    // Filter rows
    rows.forEach(row => {
        if (selectedClass === '' || row.dataset.class === selectedClass) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Renumber visible rows
    let counter = 1;
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            row.querySelector('td:first-child strong').textContent = counter++;
        }
    });
    
    // Update export button URL
    const exportBtn = document.getElementById('exportBtn');
    const baseUrl = '{{ route('admin.detai.dangky.export') }}';
    if (selectedClass) {
        exportBtn.href = baseUrl + '?lop=' + selectedClass;
    } else {
        exportBtn.href = baseUrl;
    }
}
</script>
@endsection
