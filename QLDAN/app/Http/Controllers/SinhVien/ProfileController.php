<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SinhVien;
use App\Models\TaiKhoan;

class ProfileController extends Controller
{
public function index()
{
    $maSV = Auth::user()->MaSo; // MaSo là khóa tài khoản/sinh viên
    $sinhvien = SinhVien::with(['khoa','nganh','lop','namhoc','taiKhoan'])->find($maSV);

    if (!$sinhvien) {
        abort(404, 'Không tìm thấy sinh viên');
    }

    return view('sinhvien.profile.index', compact('sinhvien'));
}


    public function update(Request $request)
    {
        $maSV = Auth::user()->MaSo;

        $request->validate([
            'TenSV' => 'required|string|max:100',
            'GioiTinh' => 'required|in:Nam,Nữ',
            'NgaySinh' => 'required|date',
            'MaCCCD' => ['required', 'regex:/^[0-9]{12}$/'],
            'DanToc' => 'nullable|string|max:50',
            'TonGiao' => 'nullable|string|max:50',
            'NoiSinh' => 'nullable|string|max:100',
            'SDT' => ['required', 'regex:/^[0-9]{10}$/'],
            'Email' => 'required|email',
            'HKTT' => 'nullable|string|max:200',
        ], [
            'TenSV.required' => 'Vui lòng nhập họ tên',
            'NgaySinh.required' => 'Vui lòng nhập ngày sinh',
            'MaCCCD.required' => 'Vui lòng nhập CCCD',
            'MaCCCD.regex' => 'CCCD phải gồm 12 chữ số',
            'SDT.required' => 'Vui lòng nhập số điện thoại',
            'SDT.regex' => 'Số điện thoại phải gồm 10 chữ số',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không hợp lệ',
        ]);

        // 🛠️ Xử lý CCCD: Nếu chưa có trong bảng CCCD thì tạo mới
        $maCCCD = $request->input('MaCCCD');
        if ($maCCCD) {
            $exists = \App\Models\CCCD::where('MaCCCD', $maCCCD)->exists();
            if (!$exists) {
                \App\Models\CCCD::create([
                    'MaCCCD' => $maCCCD,
                    'NgayCap' => null, // Hoặc thêm trường nhập nếu cần
                    'NoiCap' => null
                ]);
            }
        }

        SinhVien::where('MaSV', $maSV)->update($request->only([
            'TenSV', 'GioiTinh', 'NgaySinh', 'MaCCCD', 'DanToc', 'TonGiao', 'NoiSinh',
            'SDT', 'Email', 'HKTT'
        ]));

        return back()->with('success','Cập nhật thông tin thành công!');
    }

    // 🔐 View Đổi mật khẩu
    public function changePasswordView()
    {
        return view('sinhvien.profile.change_password');
    }

    // 🔐 Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $taiKhoan = TaiKhoan::where('MaSo', Auth::user()->MaSo)->first();

        if (!$taiKhoan) {
            return back()->withErrors(['error' => 'Không tìm thấy tài khoản']);
        }

        if (!Hash::check($request->current_password, $taiKhoan->MatKhau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $taiKhoan->MatKhau = bcrypt($request->new_password);
        $taiKhoan->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function updateAvatar(Request $request)
{
    $request->validate([
        'avatar' => 'required|image|mimes:jpg,png,jpeg|max:2048'
    ]);

    $maSV = Auth::user()->MaSo;
    $sv = SinhVien::where('MaSV', $maSV)->first();

    if (!$sv) {
        return back()->withErrors(['error' => 'Không tìm thấy sinh viên']);
    }

    if ($request->hasFile('avatar')) {

        // 📌 Thư mục lưu avatar
        $uploadPath = 'img/uploads/images/';

        // 📌 Nếu thư mục chưa tồn tại → tự tạo
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }

        $file = $request->file('avatar');
        $filename = time() . '_' . $file->getClientOriginalName();

        // 📌 Lưu file vào thư mục public/
        $file->move(public_path($uploadPath), $filename);

        // 📌 Xóa ảnh cũ nếu có
        if ($sv->HinhAnh && file_exists(public_path($sv->HinhAnh))) {
            unlink(public_path($sv->HinhAnh));
        }

        // 📌 Lưu vào DB đường dẫn tương đối
        $sv->HinhAnh = $uploadPath . $filename;
        $sv->save();
    }

    return back()->with('success', 'Cập nhật ảnh đại diện thành công!');
}


}