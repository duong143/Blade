<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $adminId = session('admin_id');

        if (!$adminId) {
            abort(403, 'Bạn chưa đăng nhập admin');
        }

        $admin = User::find($adminId);

        if (!$admin) {
            abort(403, 'Admin không tồn tại');
        }

        /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN CỨNG (CHỈ 1 NGƯỜI)
    |--------------------------------------------------------------------------
    */

        if ($admin->id == 3) { // 👈 đổi thành id admin mặc định của bạn
            return $next($request);
        }

        /*
    |--------------------------------------------------------------------------
    | Kiểm tra quyền cụ thể
    |--------------------------------------------------------------------------
    */

        if ($admin->$permission != 1) {
            abort(403, 'Bạn không có thẩm quyền, vui lòng liên hệ admin chính');
        }

        return $next($request);
    }
}
