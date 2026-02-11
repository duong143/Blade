<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminId = session('admin_id');

        if (!$adminId) {
            return redirect('/admin/login');
        }

        $admin = User::find($adminId);

        if (!$admin || $admin->is_admin != 1) {
            abort(403, 'Bạn không có quyền truy cập admin');
        }

        return $next($request);
    }
}
