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


    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])
        ->middleware('permission:view customers')
        ->name('customers.index');

    Route::get('/customers/create', [CustomerController::class, 'create'])
        ->middleware('permission:create customers')
        ->name('customers.create');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->middleware('permission:create customers')
        ->name('customers.store');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->middleware('permission:update customers')
        ->name('customers.edit');

    Route::put('/customers/{customer}', [CustomerController::class, 'update'])
        ->middleware('permission:update customers')
        ->name('customers.update');

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
        ->middleware('permission:delete customers')
        ->name('customers.destroy');
});