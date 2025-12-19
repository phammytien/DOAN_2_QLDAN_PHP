@extends('layouts.sinhvien')

@section('content')
<div class="container">
    <h2>Danh sách đề tài đang mở đăng ký</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
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
                <td>{{ $d->TenGV }}</td>
                <td>
<form method="POST" action="{{ route('sinhvien.detai.dangky', ['id' => $d->MaDeTai]) }}">
    @csrf
    <button type="submit" class="btn btn-primary btn-sm"
        {{ $deTaiDaDangKy ? 'disabled' : '' }}>
        {{ $deTaiDaDangKy ? 'Đã đăng ký' : 'Đăng ký' }}
    </button>
</form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
