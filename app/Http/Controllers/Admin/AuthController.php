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

        // Tìm user admin
        $user = User::where($field, $login)
            ->where('is_admin', 1)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Tài khoản hoặc mật khẩu không đúng'
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
        session()->forget('admin');
        return redirect('/admin/login');
    }
}
