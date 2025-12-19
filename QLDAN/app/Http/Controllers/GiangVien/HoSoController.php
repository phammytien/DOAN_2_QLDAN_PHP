<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\GiangVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\TaiKhoan;

class HoSoController extends Controller
{
    public function index()
    {
        $maGV = Auth::user()->MaSo;
        $hoso = GiangVien::with(['detais'])->where('MaGV', $maGV)->first();
        return view('giangvien.hoso.index', compact('hoso'));
    }

    public function edit()
    {
        $maGV = Auth::user()->MaSo;
        $hoso = GiangVien::where('MaGV', $maGV)->first();
        return view('giangvien.hoso.edit', compact('hoso'));
    }

    public function password()
    {
        return view('giangvien.hoso.password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\TaiKhoan $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->MatKhau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->MatKhau = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('giangvien.hoso.index')->with('success', 'Đổi mật khẩu thành công!');
    }

    public function update(Request $request)
    {
        // Validation rules
        $request->validate([
            'TenGV' => 'required|string|max:255',
            'CCCD' => 'required|digits:12',
            'SDT' => 'required|digits:10',
            'Email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'HocVi' => 'nullable|string|max:100',
            'HocHam' => 'nullable|string|max:100',
            'ChuyenNganh' => 'nullable|string|max:255',
            'HinhAnh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'CCCD.required' => 'CCCD là bắt buộc',
            'CCCD.digits' => 'CCCD phải có đúng 12 chữ số',
            'SDT.required' => 'Số điện thoại là bắt buộc',
            'SDT.digits' => 'Số điện thoại phải có đúng 10 chữ số',
            'Email.required' => 'Email là bắt buộc',
            'Email.email' => 'Email không đúng định dạng',
            'Email.regex' => 'Email chỉ được chứa chữ cái, số và các ký tự . _ - @'
        ]);
        
        $maGV = Auth::user()->MaSo;
        $gv = GiangVien::where('MaGV', $maGV)->first();

        /** @var array $data */
        $data = (array) $request->only([
            'TenGV', 'GioiTinh', 'NgaySinh', 'SDT', 'Email', 'HocVi', 'HocHam', 'ChuyenNganh'
        ]);

        // Handle CCCD - convert to JSON format
        if ($request->filled('CCCD')) {
            $data['CCCD'] = json_encode([
                'MaCCCD' => $request->CCCD,
                'NgayCap' => now()->format('Y-m-d H:i:s'),
                'NoiCap' => 'Chưa cập nhật'
            ]);
        }

        if ($request->hasFile('HinhAnh')) {
            $file = $request->file('HinhAnh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('img/uploads/images');
            $file->move($path, $filename);
            $data['HinhAnh'] = 'img/uploads/images/' . $filename;
        }

        $gv->update($data);

        return redirect()->route('giangvien.hoso.index')->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
