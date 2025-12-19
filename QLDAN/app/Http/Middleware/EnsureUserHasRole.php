<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Middleware kiểm tra quyền truy cập.
     * 
     * Dùng trong routes:
     * ->middleware('role:Admin')
     * ->middleware('role:GiangVien,SinhVien')
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        // Nếu chưa đăng nhập thì đưa về trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = Auth::user();
        $userRole = trim((string)($user->VaiTro ?? ''));
        $allowedRoles = array_map('trim', $roles);

        // ✅ Admin có toàn quyền truy cập
        if ($userRole === 'Admin') {
            return $next($request);
        }

        // Nếu không truyền role → cho phép tất cả (tùy route)
        if (empty($allowedRoles)) {
            return $next($request);
        }

        // Nếu VaiTro có trong danh sách cho phép → cho qua
        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        // ❌ Nếu không có quyền → trả về lỗi 403
        return response()->view('errors.403', [
            'message' => 'Bạn không có quyền truy cập vào trang này.',
        ], 403);
    }
}
