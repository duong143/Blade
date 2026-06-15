<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('admin_id')) {
            return redirect('/admin/login');
        }

        $timeout = 30 * 60;

        if (session()->has('admin_last_activity')) {
            $inactiveTime = time() - session('admin_last_activity');

            if ($inactiveTime > $timeout) {
                Auth::logout();

                session()->forget([
                    'admin',
                    'admin_id',
                    'admin_last_activity',
                ]);

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'expired' => true,
                        'message' => 'Phiên đăng nhập đã hết hạn.'
                    ], 401);
                }

                return redirect('/admin/login')
                    ->withErrors([
                        'login' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.'
                    ]);
            }
        }

        session(['admin_last_activity' => time()]);

        $adminId = session('admin_id');

        if (!Auth::check() || Auth::id() != $adminId) {
            $user = User::find($adminId);

            if (!$user) {
                session()->flush();
                return redirect()->route('admin.login');
            }

            Auth::login($user);
        }

        return $next($request);
    }
}
