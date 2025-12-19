<?php
namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\Khoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KhoaController extends Controller
{
    // Lấy MaKhoa của cán bộ đang đăng nhập
    private function getCanBoKhoa()
    {
        $user = Auth::user();
        $canbo = $user->canBoQL;
        return $canbo ? $canbo->MaKhoa : null;
    }

    public function index()
    {
        $maKhoa = $this->getCanBoKhoa();
        
        if (!$maKhoa) {
            abort(403, 'Bạn chưa được phân công quản lý khoa nào.');
        }
        
        // Chỉ hiển thị khoa của cán bộ
        $khoas = Khoa::where('MaKhoa', $maKhoa)->withCount('lops')->get();
        
        return view('canbo.khoa.index', compact('khoas'));
    }

    // Cán bộ không được thêm/sửa/xóa khoa (chỉ xem thông tin)
    public function store(Request $request)
    {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền thêm khoa'], 403);
    }

    public function edit($id)
    {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền sửa khoa'], 403);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền sửa khoa'], 403);
    }

    public function destroy($id)
    {
        return response()->json(['success' => false, 'message' => 'Bạn không có quyền xóa khoa'], 403);
    }

    public function export()
    {
        return response()->json(['success' => false, 'message' => 'Chức năng tạm thời không khả dụng'], 403);
    }

    public function import(Request $request)
    {
        return response()->json(['success' => false, 'message' => 'Chức năng tạm thời không khả dụng'], 403);
    }
}
