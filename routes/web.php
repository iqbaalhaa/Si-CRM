<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PipelineStageController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\CustomerStageHistoryController;

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


    // Perusahaan
    Route::get('/perusahaan', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'index'])
        ->middleware('role:super-admin')
        ->name('perusahaan.index');

    Route::get('/perusahaan/create', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'create'])
        ->middleware('role:super-admin')
        ->name('perusahaan.create');

    Route::post('/perusahaan', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'store'])
        ->middleware('role:super-admin')
        ->name('perusahaan.store');

    Route::get('/perusahaan/{perusahaan}/edit', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'edit'])
        ->middleware('role:super-admin')
        ->name('perusahaan.edit');

    Route::put('/perusahaan/{perusahaan}', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'update'])
        ->middleware('role:super-admin')
        ->name('perusahaan.update');

    Route::delete('/perusahaan/{perusahaan}', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'destroy'])
        ->middleware('role:super-admin')
        ->name('perusahaan.destroy');

    // Tim & Role (Admin perusahaan)
    Route::get('/tim-dan-role', [\App\Http\Controllers\TeamRoleController::class, 'index'])
        ->middleware('role:admin')
        ->name('teamrole.index');

    Route::post('/tim-dan-role', [\App\Http\Controllers\TeamRoleController::class, 'store'])
        ->middleware('role:admin')
        ->name('teamrole.store');

    Route::put('/tim-dan-role/{user}', [\App\Http\Controllers\TeamRoleController::class, 'update'])
        ->middleware('role:admin')
        ->name('teamrole.update');

    Route::delete('/tim-dan-role/{user}', [\App\Http\Controllers\TeamRoleController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('teamrole.destroy');

    // Manage Admin Perusahaan (Super Admin)
    Route::get('/manage-admin-perusahaan', [\App\Http\Controllers\Superadmin\ManageAdminController::class, 'index'])
        ->middleware('role:super-admin')
        ->name('manageadmin.index');

    Route::post('/manage-admin-perusahaan', [\App\Http\Controllers\Superadmin\ManageAdminController::class, 'store'])
        ->middleware('role:super-admin')
        ->name('manageadmin.store');


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

    // Pipeline Stages
    Route::get('/pipeline-stages', [PipelineStageController::class, 'index'])
        ->middleware('permission:manage pipelines')
        ->name('pipeline-stages.index');

    Route::get('/pipeline-stages/create', [PipelineStageController::class, 'create'])
        ->middleware('permission:manage pipelines')
        ->name('pipeline-stages.create');

    Route::post('/pipeline-stages', [PipelineStageController::class, 'store'])
        ->middleware('permission:manage pipelines')
        ->name('pipeline-stages.store');

    Route::get('/pipeline-stages/{pipelineStage}/edit', [PipelineStageController::class, 'edit'])
        ->middleware('permission:manage pipelines')
        ->name('pipeline-stages.edit');

    Route::put('/pipeline-stages/{pipelineStage}', [PipelineStageController::class, 'update'])
        ->middleware('permission:manage pipelines')
        ->name('pipeline-stages.update');

    Route::delete('/pipeline-stages/{pipelineStage}', [PipelineStageController::class, 'destroy'])
        ->middleware('permission:manage pipelines')
        ->name('pipeline-stages.destroy');



    Route::get('/stages', [CustomerStageHistoryController::class, 'index'])
        ->middleware('permission:view customers')
        ->name('stages.index');

    Route::get('/stages/{customer}', [CustomerStageHistoryController::class, 'show'])
        ->middleware('permission:view customers')
        ->name('stages.show');

    Route::get('/assign', [CustomerController::class, 'index'])
        ->middleware('permission:edit customers')
        ->name('stages.index');

    Route::get('/assign', [CustomerController::class, 'index'])
        ->middleware('permission:edit customers')
        ->name('stages.index');
});