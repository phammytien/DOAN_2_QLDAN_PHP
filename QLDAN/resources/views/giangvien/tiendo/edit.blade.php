@extends('layouts.giangvien')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Cập nhật tiến độ</h3>

    <form action="{{ route('giangvien.tiendo.update', $tiendo->MaTienDo) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <input type="text" name="NoiDung" value="{{ $tiendo->NoiDung }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="Deadline" value="{{ $tiendo->Deadline }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="GhiChu" class="form-control">{{ $tiendo->GhiChu }}</textarea>
        </div>

        <button class="btn btn-success">Cập nhật</button>
    </form>

</div>
@endsection
