@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Cập nhật phân công</h2>

    <form action="{{ route('admin.phancong.update', $phancong->MaPC) }}" method="POST">
        @csrf 
        @method('PUT')

        {{-- ĐỀ TÀI --}}
        <div class="form-group mb-3">
            <label><strong>Đề tài</strong></label>
            <select name="MaDeTai" class="form-control" id="selectDeTai">
                @foreach($detais as $dt)
                    <option value="{{ $dt->MaDeTai }}" {{ $phancong->MaDeTai == $dt->MaDeTai ? 'selected' : '' }}>
                        {{ $dt->TenDeTai }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- DANH SÁCH SINH VIÊN THUỘC ĐỀ TÀI --}}
        <div class="form-group mb-3">
            <label><strong>Sinh viên thực hiện</strong></label>
            <ul class="list-group" id="listSinhVien">
                @foreach($phancong->detai->sinhviens as $sv)
                    <li class="list-group-item">
                        <strong>{{ $sv->MaSV }}</strong> - {{ $sv->TenSV }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- GIẢNG VIÊN --}}
        <div class="form-group mb-3">
            <label><strong>Giảng viên</strong></label>
            <select name="MaGV" class="form-control">
                @foreach($giangviens as $gv)
                    <option value="{{ $gv->MaGV }}" {{ $phancong->MaGV == $gv->MaGV ? 'selected' : '' }}>
                        {{ $gv->TenGV }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- CÁN BỘ --}}
        <div class="form-group mb-3">
            <label><strong>Cán bộ phân công</strong></label>
            <select name="MaCB" class="form-control">
                @foreach($canbos as $cb)
                    <option value="{{ $cb->MaCB }}" {{ $phancong->MaCB == $cb->MaCB ? 'selected' : '' }}>
                        {{ $cb->TenCB }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- VAI TRÒ --}}
        <div class="form-group mb-3">
            <label><strong>Vai trò</strong></label>
            <select name="VaiTro" class="form-control">
                <option {{ $phancong->VaiTro == 'Hướng dẫn chính' ? 'selected' : '' }}>Hướng dẫn chính</option>
                <option {{ $phancong->VaiTro == 'Phản biện' ? 'selected' : '' }}>Phản biện</option>
                <option {{ $phancong->VaiTro == 'Phụ' ? 'selected' : '' }}>Phụ</option>
            </select>
        </div>

        {{-- GHI CHÚ --}}
        <div class="form-group mb-3">
            <label><strong>Ghi chú</strong></label>
            <textarea name="GhiChu" class="form-control">{{ $phancong->GhiChu }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.phancong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>

@endsection

