@extends('layouts.giangvien')

@section('content')
<div class="container mt-4">
    <h3 class="text-primary mb-4">👁️ Chi tiết đề tài</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <p><strong>Tên đề tài:</strong> {{ $detai->TenDeTai }}</p>
            <p><strong>Lĩnh vực:</strong> {{ $detai->LinhVuc }}</p>
            <p><strong>Loại đề tài:</strong> {{ $detai->LoaiDeTai }}</p>
            <p><strong>Năm học:</strong> {{ $detai->NamHoc }}</p>
            <p><strong>Trạng thái:</strong> 
                <span class="badge 
                    @if($detai->TrangThai == 'Đã duyệt') bg-success 
                    @elseif($detai->TrangThai == 'Chưa duyệt') bg-warning 
                    @else bg-secondary @endif">
                    {{ $detai->TrangThai }}
                </span>
            </p>
            <p><strong>Mô tả:</strong></p>
            <p>{{ $detai->MoTa ?? 'Không có mô tả' }}</p>

            <a href="{{ route('giangvien.detai.index') }}" class="btn btn-secondary mt-3">← Quay lại</a>
        </div>
    </div>
</div>
@endsection
