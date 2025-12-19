@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3 class="text-warning mb-3">✏️ Cập Nhật Sinh Viên</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sinhvien.update', $sv->MaSV) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- HÀNG 1 --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Mã SV</label>
                <input type="text" value="{{ $sv->MaSV }}" class="form-control" disabled>
            </div>

            <div class="col-md-4 mb-3">
                <label>Tên sinh viên</label>
                <input type="text" name="TenSV" value="{{ $sv->TenSV }}" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Giới tính</label>
                <select name="GioiTinh" class="form-select">
                    <option value="Nam" {{ $sv->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $sv->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>
        </div>

        {{-- HÀNG 2 --}}
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Ngày sinh</label>
                <input type="date" name="NgaySinh"
                       value="{{ $sv->NgaySinh ? $sv->NgaySinh->format('Y-m-d') : '' }}"
                       class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Email</label>
                <input type="email" name="Email" value="{{ $sv->Email }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>SĐT</label>
                <input type="text" name="SDT" value="{{ $sv->SDT }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Trạng thái</label>
                <input type="text" name="TrangThai" value="{{ $sv->TrangThai }}" class="form-control">
            </div>
        </div>

        {{-- HÀNG 3 --}}
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>CCCD</label>
                <input type="text" name="MaCCCD" value="{{ $sv->MaCCCD }}" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Tôn giáo</label>
                <input type="text" name="TonGiao" value="{{ $sv->TonGiao }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Nơi sinh</label>
                <input type="text" name="NoiSinh" value="{{ $sv->NoiSinh }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Hộ khẩu thường trú</label>
                <input type="text" name="HKTT" value="{{ $sv->HKTT }}" class="form-control">
            </div>
        </div>

        {{-- HÀNG 4 --}}
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Dân tộc</label>
                <input type="text" name="DanToc" value="{{ $sv->DanToc }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Bậc đào tạo</label>
                <input type="text" name="BacDaoTao" value="{{ $sv->BacDaoTao }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Khoa</label>
                <select name="MaKhoa" class="form-select">
                    @foreach($khoas as $k)
                        <option value="{{ $k->MaKhoa }}" {{ $sv->MaKhoa == $k->MaKhoa ? 'selected' : '' }}>
                            {{ $k->TenKhoa }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>Ngành</label>
                <select name="MaNganh" class="form-select">
                    @foreach($nganhs as $n)
                        <option value="{{ $n->MaNganh }}" {{ $sv->MaNganh == $n->MaNganh ? 'selected' : '' }}>
                            {{ $n->TenNganh }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- HÀNG 5 --}}
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Lớp</label>
                <select name="MaLop" class="form-select">
                    @foreach($lops as $lop)
                        <option value="{{ $lop->MaLop }}" {{ $sv->MaLop == $lop->MaLop ? 'selected' : '' }}>
                            {{ $lop->TenLop }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>Năm học</label>
                <select name="MaNamHoc" class="form-select">
                    @foreach($namhocs as $nh)
                        <option value="{{ $nh->MaNamHoc }}" {{ $sv->MaNamHoc == $nh->MaNamHoc ? 'selected' : '' }}>
                            {{ $nh->TenNamHoc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>Mã tài khoản</label>
                <input type="text" value="{{ $sv->MaTK }}" class="form-control" disabled>
            </div>
        </div>

        <div class="text-end mt-3">
            <button class="btn btn-warning">Cập nhật</button>
            <a href="{{ route('admin.sinhvien.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection
