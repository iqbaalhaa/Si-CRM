<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerStageHistoryController;
use App\Http\Controllers\PipelineStageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =========================
// Guest only
// =========================
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// =========================
// Auth only
// =========================
Route::middleware('auth')->group(function () {

    // -------------------------
    // Auth / Dashboard
    // -------------------------
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Kalau punya helper dashboardRoute di model User, pakai itu saja
        if (method_exists($user, 'dashboardRoute')) {
            return redirect($user->dashboardRoute());
        }

        // Fallback kalau helper belum ada / belum dipakai
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('super-admin')) {
                return redirect()->route('dashboard.superadmin');
            }
            if ($user->hasRole('admin')) {
                return redirect()->route('dashboard.admin');
            }
            if ($user->hasRole('marketing')) {
                return redirect()->route('dashboard.marketing');
            }
            if ($user->hasRole('cs')) {
                return redirect()->route('dashboard.cs');
            }
        }

        // Fallback terakhir
        return redirect()->route('dashboard.admin');
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

    // -------------------------
    // SUPER ADMIN: Perusahaan & Manage Admin Perusahaan
    // -------------------------
    Route::middleware('role:super-admin')->group(function () {
        // Perusahaan
        Route::get('/perusahaan', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'index'])
            ->name('perusahaan.index');

        Route::get('/perusahaan/create', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'create'])
            ->name('perusahaan.create');

        Route::post('/perusahaan', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'store'])
            ->name('perusahaan.store');

        Route::get('/perusahaan/{perusahaan}/edit', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'edit'])
            ->name('perusahaan.edit');

        Route::put('/perusahaan/{perusahaan}', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'update'])
            ->name('perusahaan.update');

        Route::delete('/perusahaan/{perusahaan}', [\App\Http\Controllers\Superadmin\PerusahaanController::class, 'destroy'])
            ->name('perusahaan.destroy');

        // Manage Admin Perusahaan
        Route::get('/manage-admin-perusahaan', [\App\Http\Controllers\Superadmin\ManageAdminController::class, 'index'])
            ->name('manageadmin.index');

        Route::post('/manage-admin-perusahaan', [\App\Http\Controllers\Superadmin\ManageAdminController::class, 'store'])
            ->name('manageadmin.store');

        Route::put('/manage-admin-perusahaan/{user}', [\App\Http\Controllers\Superadmin\ManageAdminController::class, 'update'])
            ->name('manageadmin.update');

        Route::delete('/manage-admin-perusahaan/{user}', [\App\Http\Controllers\Superadmin\ManageAdminController::class, 'destroy'])
            ->name('manageadmin.destroy');

        Route::put('/manage-admin-perusahaan/{user}/active', [\App\Http\Controllers\Superadmin\ManageAdminController::class, 'updateActive'])
            ->name('manageadmin.active');
    });

    // -------------------------
    // Reports (TinyMCE editor)
    // -------------------------
    // READ reports
    Route::middleware('permission:read reports')->group(function () {
        Route::get('/report-customers', [\App\Http\Controllers\ReportController::class, 'customers'])
            ->name('reports.customers');

        Route::get('/report-karyawan', [\App\Http\Controllers\ReportController::class, 'employees'])
            ->name('reports.employees');

        Route::get('/report-customers/download', [\App\Http\Controllers\ReportController::class, 'customersDownload'])
            ->name('reports.customers.download');

        Route::get('/report-customers/pdf', [\App\Http\Controllers\ReportController::class, 'customersPdf'])
            ->name('reports.customers.pdf');

        Route::get('/report-karyawan/download', [\App\Http\Controllers\ReportController::class, 'employeesDownload'])
            ->name('reports.employees.download');

        Route::get('/report-karyawan/pdf', [\App\Http\Controllers\ReportController::class, 'employeesPdf'])
            ->name('reports.employees.pdf');

        Route::get('/report-settings', [\App\Http\Controllers\ReportController::class, 'settings'])
            ->name('reports.settings');
    });

    // UPDATE reports (save pengaturan)
    Route::post('/report-settings', [\App\Http\Controllers\ReportController::class, 'settingsSave'])
        ->middleware('permission:update reports')
        ->name('reports.settings.save');

    // -------------------------
    // Tim & Role (Admin perusahaan)
    // -------------------------
    Route::middleware('role:admin')->group(function () {
        Route::get('/tim-dan-role', [\App\Http\Controllers\TeamRoleController::class, 'index'])
            ->name('teamrole.index');

        Route::post('/tim-dan-role', [\App\Http\Controllers\TeamRoleController::class, 'store'])
            ->name('teamrole.store');

        Route::put('/tim-dan-role/{user}', [\App\Http\Controllers\TeamRoleController::class, 'update'])
            ->name('teamrole.update');

        Route::delete('/tim-dan-role/{user}', [\App\Http\Controllers\TeamRoleController::class, 'destroy'])
            ->name('teamrole.destroy');

        // Contact
        Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'create'])
            ->name('contact.index');

        Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])
            ->name('contact.store');
    });

    // -------------------------
    // Customers
    // -------------------------
    Route::middleware('permission:read customers')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');
    });

    Route::middleware('permission:create customers')->group(function () {
        Route::get('/customers/create', [CustomerController::class, 'create'])
            ->name('customers.create');

        Route::post('/customers', [CustomerController::class, 'store'])
            ->name('customers.store');
    });

    Route::middleware('permission:update customers')->group(function () {
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
            ->name('customers.edit');

        Route::put('/customers/{customer}', [CustomerController::class, 'update'])
            ->name('customers.update');
    });

    Route::middleware('permission:delete customers')->group(function () {
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
            ->name('customers.destroy');
    });

    // -------------------------
    // Pipeline Stages (CRUD per permission)
    // -------------------------

    // READ
    Route::get('/pipeline-stages', [PipelineStageController::class, 'index'])
        ->middleware('permission:read pipelines')
        ->name('pipeline-stages.index');

    // CREATE
    Route::get('/pipeline-stages/create', [PipelineStageController::class, 'create'])
        ->middleware('permission:create pipelines')
        ->name('pipeline-stages.create');

    Route::post('/pipeline-stages', [PipelineStageController::class, 'store'])
        ->middleware('permission:create pipelines')
        ->name('pipeline-stages.store');

    // UPDATE
    Route::get('/pipeline-stages/{pipelineStage}/edit', [PipelineStageController::class, 'edit'])
        ->middleware('permission:update pipelines')
        ->name('pipeline-stages.edit');

    Route::put('/pipeline-stages/{pipelineStage}', [PipelineStageController::class, 'update'])
        ->middleware('permission:update pipelines')
        ->name('pipeline-stages.update');

    // DELETE
    Route::delete('/pipeline-stages/{pipelineStage}', [PipelineStageController::class, 'destroy'])
        ->middleware('permission:delete pipelines')
        ->name('pipeline-stages.destroy');

    // -------------------------
    // Stages (History)
    // -------------------------
    Route::get('/stages', [CustomerStageHistoryController::class, 'index'])
        ->middleware('permission:read customers')
        ->name('stages.index');

    // CRM Show & Update Stage
    Route::get('/crm/customers/{customer}', [CustomerController::class, 'show'])
        ->name('crm.show');

    Route::put('/crm/customers/{customer}/stage', [CustomerController::class, 'updateStage'])
        ->name('customers.update-stage');

    // -------------------------
    // Assign
    // -------------------------
    Route::get('/assign', [CustomerController::class, 'assign'])
        ->middleware('permission:read customers')
        ->name('assign.index');

    Route::post('/assign-to/{customer}', [CustomerController::class, 'assignTo'])
        ->middleware('permission:update customers')
        ->name('assign.store');

    // -------------------------
    // Notifications
    // -------------------------
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.readAll');

    Route::get('/notifications/read/{id}', function ($id) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notif = $user->notifications()->findOrFail($id);
        $notif->markAsRead();

        return redirect($notif->data['url'] ?? '/');
    })->name('notifications.read');
});
