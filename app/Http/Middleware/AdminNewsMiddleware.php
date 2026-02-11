<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNewsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Chưa đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin tổng → luôn được
        if ($user->is_admin === 1) {
            return $next($request);
        }

        // Không có quyền admin tin tức
        if ($user->admin_news !== 1) {
            return redirect()->back()->with(
                'error',
                'Bạn không đủ thẩm quyền, yêu cầu liên hệ admin chính'
            );
        }

        return $next($request);
    }
}
