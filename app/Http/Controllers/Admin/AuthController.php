<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('admin')) {
            return redirect('/admin');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $login = $request->login;

        // Xác định đăng nhập bằng email hay phone
        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';


        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Tài khoản hoặc mật khẩu không đúng'
            ]);
        }

        if (! $user->hasAnyPermission([
            'users.view',
            'news.view',
            'banners.view',
            'roles.view',
            'footer.view',
        ])) {
            return back()->withErrors([
                'login' => 'Bạn không có quyền vào admin'
            ]);
        }

        // Đăng nhập admin
        session([
            'admin' => true,
            'admin_id' => $user->id
        ]);

        return redirect('/admin');
    }

    public function logout()
    {
        // logout Auth (Spatie dùng Auth::user())
        \Illuminate\Support\Facades\Auth::logout();

        // xoá session admin (KHÔNG flush toàn bộ để tránh ảnh hưởng session khác)
        session()->forget(['admin', 'admin_id']);

        // regenerate session để sạch CSRF/session fixation
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
