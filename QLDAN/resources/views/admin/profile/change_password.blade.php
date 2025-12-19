@extends('layouts.admin')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h4 class="fw-bold text-primary text-center">Đổi mật khẩu</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.change_password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-medium">Mật khẩu hiện tại</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0" id="current_password" name="current_password" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label fw-medium">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0" id="new_password" name="new_password" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label fw-medium">Xác nhận mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0" id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">Cập nhật mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
