@extends('layouts.sinhvien')

@section('content')
<div class="container mt-5" style="max-width: 500px">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="text-center text-primary mb-4">Đổi mật khẩu</h4>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('sinhvien.changePassword.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Mật khẩu mới</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>

                <div class="mb-3">
                    <label>Nhập lại mật khẩu</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                </div>

                <button class="btn btn-primary w-100">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>
@endsection
