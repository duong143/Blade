<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // CHỈ CHECK ADMIN SESSION
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        return $next($request);
    }
}
