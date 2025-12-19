@extends('sinhvien.layout')
@section('title','Chi tiết thông báo')
@section('content')
    <h1>Thông báo</h1>
    <p>{{ $tb->TGDang }} - {{ $tb->NoiDung }}</p>
    @if($tb->TenFile)
        <p>File đính kèm: <a href="{{ $tb->TenFile }}">{{ $tb->TenFile }}</a></p>
    @endif
@endsection
