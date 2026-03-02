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

        $adminId = session('admin_id');

        // Nếu Auth chưa login hoặc đang login sai user -> sync lại
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
