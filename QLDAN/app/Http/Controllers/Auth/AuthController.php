<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==============================
    // HIỂN THỊ TRANG ĐĂNG NHẬP
    // ==============================
public function loginView()
{
    // 1️ Lấy thông báo quan trọng (chỉ "TấtCa", không theo vai trò)
    $thongbao_quantrong = ThongBao::where('MucDo', 'danger')
        ->whereNull('MaNguoiNhan') // Loại bỏ thông báo riêng tư (reset password)
        ->where(function($q) {
            $q->whereNull('DoiTuongNhan')
              ->orWhere('DoiTuongNhan', 'TấtCa'); // Database lưu không dấu cách
        })
        ->orderByDesc('TGDang')
        ->first();

    // 2️ Lấy thông báo thường (chỉ "TấtCa", không theo vai trò)
    $thongbao = ThongBao::whereNotNull('MucDo')
        ->where('MucDo', '!=', 'danger')
        ->whereNull('MaNguoiNhan') // Loại bỏ thông báo riêng tư
        ->where(function($q) {
            $q->whereNull('DoiTuongNhan')
              ->orWhere('DoiTuongNhan', 'TấtCa'); // Database lưu không dấu cách
        })
        ->orderByDesc('TGDang')
        ->take(10)
        ->get();

    return view('auth.login', compact('thongbao', 'thongbao_quantrong'));
}


    // ==============================
    // HIỂN THỊ TRANG ĐĂNG KÝ
    // ==============================
    public function registerView()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // ==============================
    // XỬ LÝ ĐĂNG NHẬP
    // ==============================
public function login(Request $request)
{
    $request->validate([
        'ma_so' => 'required|string',
        'mat_khau' => 'required|string',
    ], [
        'ma_so.required' => 'Vui lòng nhập mã số.',
        'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
    ]);

    // Lấy tài khoản
    $taiKhoan = TaiKhoan::where('MaSo', $request->ma_so)->first();

    if (!$taiKhoan) {
        return back()->withErrors([
            'ma_so' => 'Tài khoản không tồn tại.'
        ])->withInput();
    }

    // ⚠ CHẶN TÀI KHOẢN BỊ KHÓA HOẶC ĐANG YÊU CẦU RESET
    if ($taiKhoan->TrangThai === 'locked') {
        return back()->withErrors([
            'ma_so' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
        ]);
    }
    if ($taiKhoan->TrangThai === 'request_reset') {
        return back()->withErrors([
            'ma_so' => 'Tài khoản đang chờ cấp lại mật khẩu. Vui lòng đợi Admin phê duyệt.'
        ]);
    }

    // Kiểm tra mật khẩu
    $matKhauDB = $taiKhoan->MatKhau;
    $isBcrypt = str_starts_with($matKhauDB, '$2y$') || str_starts_with($matKhauDB, '$2a$');

    if ($isBcrypt) {
        if (!Hash::check($request->mat_khau, $matKhauDB)) {
            return back()->withErrors(['mat_khau' => 'Mật khẩu không chính xác.'])->withInput();
        }
    } else {
        // Mật khẩu dạng plaintext cũ (nếu có)
        if ($request->mat_khau !== $matKhauDB) {
            return back()->withErrors(['mat_khau' => 'Mật khẩu không chính xác.'])->withInput();
        }
    }

    // Đăng nhập
    Auth::login($taiKhoan);
    $request->session()->regenerate();

    return redirect()->route('dashboard');
}

    // ==============================
    // XỬ LÝ YÊU CẦU CẤP LẠI MẬT KHẨU
    // ==============================
    public function requestPasswordReset(Request $request)
    {
        $request->validate([
            'ma_so' => 'required|string',
        ]);

        $taiKhoan = TaiKhoan::where('MaSo', $request->ma_so)->first();

        if (!$taiKhoan) {
            return response()->json(['success' => false, 'message' => 'Mã số không tồn tại trong hệ thống.']);
        }

        if ($taiKhoan->TrangThai === 'request_reset') {
            return response()->json(['success' => false, 'message' => 'Bạn đã gửi yêu cầu rồi. Vui lòng chờ Admin xử lý.']);
        }

        if ($taiKhoan->TrangThai === 'locked') {
            return response()->json(['success' => false, 'message' => 'Tài khoản đang bị khóa. Không thể gửi yêu cầu.']);
        }

        // Cập nhật trạng thái
        $taiKhoan->TrangThai = 'request_reset';
        $taiKhoan->save();

        return response()->json(['success' => true, 'message' => 'Gửi yêu cầu thành công! Vui lòng chờ Admin phê duyệt.']);
    }

    // ==============================
    // XỬ LÝ ĐĂNG KÝ
    // ==============================
    public function register(Request $request)
    {
        $request->validate([
            'MaSo' => 'required|string|max:50|unique:TaiKhoan,MaSo',
            'MatKhau' => 'required|string|min:8|confirmed',
            'VaiTro' => 'required|string|in:SinhVien,GiangVien,CanBo,Admin',
        ]);

        $data = $request->only('MaSo', 'VaiTro');
        $data['MatKhau'] = bcrypt($request->MatKhau);

        TaiKhoan::create($data);

        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }

    // ==============================
    // ĐĂNG XUẤT
    // ==============================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }

    


}