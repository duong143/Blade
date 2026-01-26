<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ĐĂNG NHẬP
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Sai số điện thoại hoặc mật khẩu'
            ], 401);
        }

        session([
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'name' => $user->name,
                'is_admin' => $user->is_admin
            ]
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // ĐĂNG KÝ
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        session([
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'name' => $user->name,
                'is_admin' => $user->is_admin ?? 0
            ]
        ]);

        return response()->json([
            'success' => true
        ]);
    }
    // ĐĂNG XUẤT
    public function logout()
    {
        session()->forget('user');

        return response()->json([
            'success' => true
        ]);
    }
}
