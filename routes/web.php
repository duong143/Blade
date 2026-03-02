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

//AUTH (USER – FRONTEND)

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

//ADMIN AUTH (KHÔNG DÙNG middleware admin)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');

//ADMIN PANEL (SESSION ADMIN + SPATIE)

Route::prefix('admin')
    ->middleware(['admin']) // ✅ dùng middleware của bạn
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        // ROLES

        Route::get('roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('admin.roles.index');

        Route::get('roles/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])
            ->middleware('permission:roles.create')
            ->name('admin.roles.create');

        Route::post('roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])
            ->middleware('permission:roles.create')
            ->name('admin.roles.store');

        Route::get('roles/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])
            ->middleware('permission:roles.edit')
            ->name('admin.roles.edit');

        Route::put('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])
            ->middleware('permission:roles.edit')
            ->name('admin.roles.update');

        Route::delete('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])
            ->middleware('permission:roles.delete')
            ->name('admin.roles.destroy');


        // USERS
        Route::resource('users', UserController::class)
            ->only(['index'])
            ->middleware('permission:users.view')
            ->names('admin.users');

        Route::resource('users', UserController::class)
            ->only(['create', 'store'])
            ->middleware('permission:users.create')
            ->names('admin.users');

        Route::resource('users', UserController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:users.edit')
            ->names('admin.users');

        Route::resource('users', UserController::class)
            ->only(['destroy'])
            ->middleware('permission:users.delete')
            ->names('admin.users');

        // NEWS
        Route::resource('news', NewsController::class)
            ->only(['index'])
            ->middleware('permission:news.view')
            ->names('admin.news');

        Route::resource('news', NewsController::class)
            ->only(['create', 'store'])
            ->middleware('permission:news.create')
            ->names('admin.news');

        Route::resource('news', NewsController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:news.edit')
            ->names('admin.news');

        Route::resource('news', NewsController::class)
            ->only(['destroy'])
            ->middleware('permission:news.delete')
            ->names('admin.news');

        Route::delete('news-images/{image}', [NewsController::class, 'deleteImage'])
            ->name('admin.news-images.destroy')
            ->middleware('permission:news.edit');

        // BANNERS
        Route::resource('banners', BannerController::class)
            ->only(['index'])
            ->middleware('permission:banners.view')
            ->names('admin.banners');

        Route::resource('banners', BannerController::class)
            ->only(['create', 'store'])
            ->middleware('permission:banners.create')
            ->names('admin.banners');

        Route::resource('banners', BannerController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:banners.edit')
            ->names('admin.banners');

        Route::resource('banners', BannerController::class)
            ->only(['destroy'])
            ->middleware('permission:banners.delete')
            ->names('admin.banners');

        //footer setting

        Route::get('settings/footer', [SettingController::class, 'editFooter'])
            ->middleware('permission:footer.view')
            ->name('admin.settings.footer');

        Route::post('settings/footer', [SettingController::class, 'updateFooter'])
            ->middleware('permission:footer.edit')
            ->name('admin.settings.footer.update');
    });

//frontend
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/flightsearch', function () {
    return view('flightsearch');
});
