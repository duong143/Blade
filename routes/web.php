<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;


Route::get('/admin/login', [AdminAuthController::class, 'showLogin']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');


Route::prefix('admin')
    ->middleware('admin')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::resource('users', UserController::class)
            ->names('admin.users');
    });

Route::get('/', function () {
    return view('home');
});

Route::get('/flightsearch', function () {
    return view('flightsearch');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', function () {
    session()->forget('user');
    return response()->json([
        'success' => true
    ]);
});

Route::post('/change-password', function (Illuminate\Http\Request $request) {

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = session('user'); // theo cách bạn đang lưu session

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Chưa đăng nhập'
        ], 401);
    }

    $dbUser = \App\Models\User::find($user['id']);

    if (!Hash::check($request->current_password, $dbUser->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Mật khẩu hiện tại không đúng'
        ]);
    }

    $dbUser->password = Hash::make($request->new_password);
    $dbUser->save();


    return response()->json([
        'success' => true,
        'message' => 'Đổi mật khẩu thành công'
    ]);
});
