@extends('layouts.giangvien')

@section('title', 'Quản lý Báo cáo')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Danh sách nộp báo cáo</h2>
            <p class="text-muted mb-0">Quản lý và duyệt báo cáo của sinh viên.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3">#</th>
                            <th class="py-3">Đề tài</th>
                            <th class="py-3">Sinh viên</th>
                            <th class="py-3">Tên file</th>
                            <th class="py-3">Ngày nộp</th>
                            <th class="py-3">Lần nộp</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="py-3">Nhận xét</th>
                            <th class="text-end pe-4 py-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($baocaos as $bc)
                        <tr>
                            <td class="ps-4">{{ $bc->MaBC }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $bc->deTai->TenDeTai ?? 'N/A' }}">
                                    {{ $bc->deTai->TenDeTai ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $bc->sinhVien->TenSV ?? 'N/A' }}</td>
                            <td>
                                @if($bc->fileBaoCao)
                                    <div class="mb-1">
                                        <a href="{{ asset(Str::startsWith($bc->fileBaoCao->path, 'storage/') ? $bc->fileBaoCao->path : 'storage/' . ltrim($bc->fileBaoCao->path, '/')) }}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-file-earmark-pdf text-danger me-1"></i>{{ $bc->fileBaoCao->name }}
                                        </a>
                                    </div>
                                @endif
                                @if($bc->fileCode)
                                    <div>
                                        <a href="{{ asset(Str::startsWith($bc->fileCode->path, 'storage/') ? $bc->fileCode->path : 'storage/' . ltrim($bc->fileCode->path, '/')) }}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-file-code text-warning me-1"></i>{{ $bc->fileCode->name }}
                                        </a>
                                    </div>
                                @endif
                                @if(!$bc->fileBaoCao && !$bc->fileCode)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $bc->NgayNop ? \Carbon\Carbon::parse($bc->NgayNop)->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td class="text-center fw-bold">{{ $bc->LanNop }}</td>
                            <td>
                                @php
                                    $statusClass = match($bc->TrangThai) {
                                        'Đã duyệt' => 'bg-success',
                                        'Chờ duyệt' => 'bg-secondary',
                                        'Xin nộp bổ sung' => 'bg-warning text-dark',
                                        'Được nộp bổ sung' => 'bg-info text-dark',
                                        'Từ chối nộp bù' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} px-3 py-2">{{ $bc->TrangThai }}</span>
                            </td>
                            <td>{{ $bc->NhanXet ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group-vertical" role="group">
                                    {{-- Nút Duyệt báo cáo thường --}}
                                    @if($bc->TrangThai === 'Chờ duyệt' && ($bc->fileBaoCao || $bc->fileCode))
                                        <button type="button" class="btn btn-sm btn-success mb-1" data-bs-toggle="modal" data-bs-target="#approveModal{{ $bc->MaBC }}" title="Duyệt">
                                            <i class="bi bi-check-circle me-1"></i>Duyệt
                                        </button>
                                    @endif

                                    {{-- Nút Duyệt/Từ chối yêu cầu nộp bổ sung --}}
                                    @if($bc->TrangThai === 'Xin nộp bổ sung')
                                        <button type="button" class="btn btn-sm btn-success mb-1 w-100" data-bs-toggle="modal" data-bs-target="#approveLateModal{{ $bc->MaBC }}" title="Duyệt nộp bổ sung">
                                            <i class="bi bi-check-circle me-1"></i>Duyệt nộp bổ sung
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger mb-1 w-100" data-bs-toggle="modal" data-bs-target="#rejectLateModal{{ $bc->MaBC }}" title="Từ chối">
                                            <i class="bi bi-x-circle me-1"></i>Từ chối
                                        </button>
                                    @endif

                                    {{-- Nút Nhận xét --}}
                                    <button type="button" class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#commentModal{{ $bc->MaBC }}" title="Nhận xét">
                                        <i class="bi bi-chat-left-text me-1"></i>Nhận xét
                                    </button>

                                    {{-- Nút Xóa --}}
                                    <form action="{{ route('giangvien.baocao.destroy', $bc->MaBC) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Bạn có chắc muốn xóa báo cáo này?')" title="Xóa">
                                            <i class="bi bi-trash me-1"></i>Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Duyệt --}}
                        <div class="modal fade" id="approveModal{{ $bc->MaBC }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Duyệt báo cáo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('giangvien.baocao.approve', $bc->MaBC) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Đề tài:</strong> {{ $bc->deTai->TenDeTai ?? 'N/A' }}</p>
                                            <p><strong>Sinh viên:</strong> {{ $bc->sinhVien->TenSV ?? 'N/A' }}</p>
                                            <p><strong>File:</strong> 
                                                @if($bc->fileBaoCao) {{ $bc->fileBaoCao->name }} @endif
                                                @if($bc->fileCode) <br>Code: {{ $bc->fileCode->name }} @endif
                                            </p>
                                            <div class="mb-3">
                                                <label class="form-label">Nhận xét (tùy chọn)</label>
                                                <textarea name="NhanXet" class="form-control" rows="3" placeholder="Nhập nhận xét..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-success">Duyệt báo cáo</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Duyệt nộp bổ sung --}}
                        <div class="modal fade" id="approveLateModal{{ $bc->MaBC }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                    <div class="modal-header border-0 pb-2">
                                        <!-- <h6 class="modal-title fw-semibold">127.0.0.1:8000 cho biết</h6> -->
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                        <div class="mb-3">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 60px; height: 60px;">
                                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                        <p class="mb-2 fw-semibold">Quyết yêu cầu nộp bổ sung?</p>
                                        <p class="text-muted small mb-0">Sinh viên: {{ $bc->sinhVien->TenSV ?? 'N/A' }}</p>
                                    </div>
                                    <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Không</button>
                                        <form action="{{ route('giangvien.baocao.approveLate', $bc->MaBC) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success px-4">
                                                <i class="bi bi-check-circle me-1"></i>Có, duyệt
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Từ chối nộp bổ sung --}}
                        <div class="modal fade" id="rejectLateModal{{ $bc->MaBC }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                    <div class="modal-header border-0 pb-2">
                                        <!-- <h6 class="modal-title fw-semibold">127.0.0.1:8000 cho biết</h6> -->
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                        <div class="mb-3">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width: 60px; height: 60px;">
                                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                        <p class="mb-2 fw-semibold">Từ chối yêu cầu nộp bổ sung?</p>
                                        <p class="text-muted small mb-2">Sinh viên: {{ $bc->sinhVien->TenSV ?? 'N/A' }}</p>
                                        <div class="alert alert-warning border-0 mx-4 mb-0" style="font-size: 0.875rem;">
                                            <i class="bi bi-info-circle me-1"></i>Sinh viên sẽ không thể nộp lại báo cáo này!
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 justify-content-center pt-0 pb-4">
                                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Không</button>
                                        <form action="{{ route('giangvien.baocao.rejectLate', $bc->MaBC) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger px-4">
                                                <i class="bi bi-x-circle me-1"></i>Có, từ chối
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Nhận xét --}}
                        <div class="modal fade" id="commentModal{{ $bc->MaBC }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Thêm nhận xét</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('giangvien.baocao.comment', $bc->MaBC) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Đề tài:</strong> {{ $bc->deTai->TenDeTai ?? 'N/A' }}</p>
                                            <p><strong>Sinh viên:</strong> {{ $bc->sinhVien->TenSV ?? 'N/A' }}</p>
                                            <div class="mb-3">
                                                <label class="form-label">Nhận xét <span class="text-danger">*</span></label>
                                                <textarea name="NhanXet" class="form-control" rows="4" placeholder="Nhập nhận xét..." required>{{ $bc->NhanXet }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-warning">Lưu nhận xét</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                Không có báo cáo nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($baocaos->hasPages())
        <div class="d-flex justify-content-end mt-3">
            {{ $baocaos->links() }}
        </div>
    @endif
</div>
@endsection
