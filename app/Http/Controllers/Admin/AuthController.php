<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        $login = $request->input('login');

        $throttleKey = Str::lower($login) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau ' . $minutes . ' phút.'
                ]);
        }

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 15 * 60);

            $remaining = RateLimiter::remaining($throttleKey, 5);

            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'Tài khoản hoặc mật khẩu không đúng. Bạn còn ' . $remaining . ' lần thử.'
                ]);
        }

        Auth::login($user);

        if (! $user->hasAnyPermission([
            'users.view',
            'news.view',
            'banners.view',
            'roles.view',
            'footer.view',
        ])) {
            Auth::logout();

            RateLimiter::hit($throttleKey, 15 * 60);

            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'Bạn không có quyền vào admin'
                ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        session([
            'admin' => true,
            'admin_id' => $user->id,
            'admin_last_activity' => now()->timestamp,
        ]);

        return redirect('/admin');
    }

    public function logout()
    {
        Auth::logout();

        session()->forget([
            'admin',
            'admin_id',
            'admin_last_activity',
        ]);

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function showForgotPassword()
    {
        return view('admin.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = url('/admin/password/reset/' . $token . '?email=' . urlencode($user->email));

        Mail::send('admin.emails.reset-password', [
            'user' => $user,
            'resetUrl' => $resetUrl,
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Đặt lại mật khẩu quản trị Travel Link');
        });

        return back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi vào email của bạn.');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('admin.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

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

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect('/admin/login')
            ->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập lại.');
    }
}
