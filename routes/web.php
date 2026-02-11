<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| AUTH (USER – FRONTEND)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', function () {
    session()->forget('user');
    return response()->json(['success' => true]);
});

Route::post('/change-password', function (Request $request) {

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = session('user');
    if (!$user) {
        return response()->json(['success' => false], 401);
    }

    $dbUser = \App\Models\User::find($user['id']);

    if (!Hash::check($request->current_password, $dbUser->password)) {
        return response()->json(['success' => false]);
    }

    $dbUser->password = Hash::make($request->new_password);
    $dbUser->save();

    return response()->json(['success' => true]);
});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH (⚠️ KHÔNG DÙNG middleware admin)
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');

/*
|--------------------------------------------------------------------------
| ADMIN – DASHBOARD & USER (chỉ cần is_admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware('admin')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::resource('users', UserController::class)
            ->names('admin.users');
    });

/*
|--------------------------------------------------------------------------
| ADMIN – NEWS
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['admin', 'permission:admin_news'])
    ->group(function () {

        Route::resource('news', NewsController::class)
            ->names('admin.news');

        Route::delete('/news-images/{image}', [NewsController::class, 'deleteImage'])
            ->name('admin.news-images.delete');
    });

/*
|--------------------------------------------------------------------------
| ADMIN – BANNER
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['admin', 'permission:admin_banner'])
    ->group(function () {

        Route::resource('banners', BannerController::class)
            ->names('admin.banners');
    });

/*
|--------------------------------------------------------------------------
| ADMIN – FOOTER
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['admin', 'permission:admin_footer'])
    ->group(function () {

        Route::get('/settings/footer', [SettingController::class, 'editFooter'])
            ->name('admin.settings.footer');

        Route::post('/settings/footer', [SettingController::class, 'updateFooter'])
            ->name('admin.settings.footer.update');
    });

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/flightsearch', function () {
    return view('flightsearch');
});
