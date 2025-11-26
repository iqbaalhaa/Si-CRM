<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;



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
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard/superadmin', function () {
        return view('superadmin.dashboard');
    })->middleware('role:super-admin')->name('dashboard.superadmin');

    Route::get('/dashboard/admin', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('dashboard.admin');

    Route::get('/dashboard/marketing', function () {
        return view('marketing.dashboard');
    })->middleware('role:marketing')->name('dashboard.marketing');

    Route::get('/dashboard/cs', function () {
        return view('cs.dashboard');
    })->middleware('role:cs')->name('dashboard.cs');

    Route::get('/perusahaan', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'index'])
        ->middleware('role:super-admin')
        ->name('perusahaan.index');



    Route::get('/customers', [CustomerController::class, 'index'])
        ->middleware('permission:view customer')
        ->name('customers.index');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->middleware('permission:create customer')
        ->name('customers.store');

    Route::put('/customers/{customer}', [CustomerController::class, 'update'])
        ->middleware('permission:update customer')
        ->name('customers.update');

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
        ->middleware('permission:delete customer')
        ->name('customers.destroy');
});