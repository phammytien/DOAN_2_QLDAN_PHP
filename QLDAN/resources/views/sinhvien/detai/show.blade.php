@extends('layouts.sinhvien')
@section('title','Chi tiết đề tài')
@section('content')
    <h1>{{ $detai->TenDeTai }}</h1>
    <p><strong>Trạng thái:</strong> {{ $detai->TrangThai }}</p>
    <p><strong>Mô tả:</strong> {{ $detai->MoTa }}</p>

    <h3>Thành viên</h3>
    <ul>
        @foreach($detai->sinhviens as $sv)
            <li>{{ $sv->TenSV }} - {{ $sv->pivot->VaiTro }}</li>
        @endforeach
    </ul>

<h3>Báo cáo</h3>
<ul>
    @foreach($detai->baocaos as $bc)
        <li><a href="{{ $bc->LinkFile }}">{{ $bc->TenFile }}</a> ({{ $bc->NgayNop }})</li>
    @endforeach
</ul>

{{-- ⭐ Thêm deadline ở đây --}}
@if($detai->DeadlineBaoCao)
    <p><strong>⏰ Deadline báo cáo:</strong>
        <span class="text-danger fw-bold">
            {{ date('d/m/Y H:i', strtotime($detai->DeadlineBaoCao)) }}
        </span>
    </p>
@else
    <p><strong>⏰ Deadline báo cáo:</strong> <span class="text-muted">Chưa được đặt</span></p>
@endif

@endsection
