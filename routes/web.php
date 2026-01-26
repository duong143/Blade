<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('home');
});

Route::get('/flightsearch', function () {
    return view('flightsearch');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::prefix('admin')->group(function () {
    Route::resource('users', UserController::class)
        ->names('admin.users');
});