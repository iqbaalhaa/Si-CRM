<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



// Guest only
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Auth only
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        // sementara simple, nanti arahkan ke dashboard CRM kamu
        return view('dashboard');
    })->name('dashboard');
});
