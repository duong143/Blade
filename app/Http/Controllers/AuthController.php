<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
                'email' => $user->email,
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        session([
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'name' => $user->name,
                'email' => $user->email,
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

    // HIỂN THỊ FORM QUÊN MẬT KHẨU KHÁCH HÀNG
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // GỬI LINK ĐẶT LẠI MẬT KHẨU
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($this->isAdminUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Email này không thuộc tài khoản khách hàng.'
            ], 403);
        }
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = url('/password/reset/' . $token . '?email=' . urlencode($user->email));

        Mail::send('auth.emails.reset-password', [
            'user' => $user,
            'resetUrl' => $resetUrl,
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Đặt lại mật khẩu Travel Link');
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Liên kết đặt lại mật khẩu đã được gửi vào email của bạn.'
            ]);
        }

        return back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi vào email của bạn.');
    }

    // HIỂN THỊ FORM ĐẶT LẠI MẬT KHẨU
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // CẬP NHẬT MẬT KHẨU MỚI
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors([
                'email' => 'Mã đặt lại mật khẩu không hợp lệ.'
            ]);
        }

        if (Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return back()->withErrors([
                'email' => 'Liên kết đặt lại mật khẩu đã hết hạn.'
            ]);
        }

        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors([
                'email' => 'Mã đặt lại mật khẩu không hợp lệ.'
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if ($this->isAdminUser($user)) {
            return back()->withErrors([
                'email' => 'Email này không thuộc tài khoản khách hàng.'
            ]);
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect('/')
            ->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập lại.');
    }
    private function isAdminUser(User $user): bool
    {
        try {
            return $user->hasRole('Admin')
                || $user->hasAnyPermission([
                    'users.view',
                    'news.view',
                    'banners.view',
                    'roles.view',
                    'footer.view',
                ]);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
