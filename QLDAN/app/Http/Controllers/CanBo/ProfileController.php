<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Khoa;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $canbo = $user->canBoQL;
        $khoas = Khoa::orderBy('TenKhoa')->get();

        return view('canbo.profile', compact('canbo', 'khoas'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $canbo = $user->canBoQL;

        $request->validate([
            'TenCB' => 'required|string|max:255',
            'GioiTinh' => 'required|string',
            'NgaySinh' => 'required|date',
            'MaCCCD' => 'nullable|string',
            'TonGiao' => 'nullable|string',
            'DanToc' => 'nullable|string',
            'NoiSinh' => 'nullable|string',
            'HKTT' => 'nullable|string',
            'SDT' => 'required|numeric',
            'Email' => 'required|email',
            'HocVi' => 'nullable|string',
            'HocHam' => 'nullable|string',
            'ChuyenNganh' => 'nullable|string',
            'MaKhoa' => 'nullable|exists:Khoa,MaKhoa',
        ]);

        $canbo->update([
            'TenCB' => $request->TenCB,
            'GioiTinh' => $request->GioiTinh,
            'NgaySinh' => $request->NgaySinh,
            'MaCCCD' => $request->MaCCCD,
            'TonGiao' => $request->TonGiao,
            'DanToc' => $request->DanToc,
            'NoiSinh' => $request->NoiSinh,
            'HKTT' => $request->HKTT,
            'SDT' => $request->SDT,
            'Email' => $request->Email,
            'HocVi' => $request->HocVi,
            'HocHam' => $request->HocHam,
            'ChuyenNganh' => $request->ChuyenNganh,
            'MaKhoa' => $request->MaKhoa,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

public function changePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    /** @var TaiKhoan $user */
    $user = TaiKhoan::where('MaTK', Auth::user()->MaTK)->firstOrFail();

    if (!Hash::check($request->old_password, $user->MatKhau)) {
        return back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác']);
    }

    $user->MatKhau = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Đổi mật khẩu thành công!');
}
}