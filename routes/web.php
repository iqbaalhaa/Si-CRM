<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerStageHistoryController;
use App\Http\Controllers\PipelineStageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Superadmin\ManageAdminController;
use App\Http\Controllers\Superadmin\PerusahaanController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamRoleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest only
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/', fn () => redirect()->route('login'));

// Auth only
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        if (method_exists($user, 'dashboardRoute')) return redirect($user->dashboardRoute());
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('super-admin')) return redirect()->route('dashboard.superadmin');
            if ($user->hasRole('admin')) return redirect()->route('dashboard.admin');
            if ($user->hasRole('lead-operations')) return redirect()->route('dashboard.lead_operations');
        }
        return redirect()->route('dashboard.admin');
    })->name('dashboard');

    Route::get('/dashboard/superadmin', fn () => view('superadmin.dashboard'))
        ->middleware('role:super-admin')->name('dashboard.superadmin');

    Route::get('/dashboard/admin', fn () => view('admin.dashboard'))
        ->middleware('role:admin')->name('dashboard.admin');

    Route::get('/dashboard/lead-operations', fn () => view('lead-operations.dashboard'))
        ->middleware('role:lead-operations')->name('dashboard.lead_operations');

    // Super Admin: Perusahaan & Manage Admin
    Route::middleware('role:super-admin')->group(function () {
        Route::resource('perusahaan', PerusahaanController::class);
        Route::resource('manage-admin-perusahaan', ManageAdminController::class);
        Route::put('/manage-admin-perusahaan/{user}/active', [ManageAdminController::class, 'updateActive'])
            ->name('manageadmin.active');
    });

    // Reports
    Route::middleware('permission:read reports')->group(function () {
        Route::get('/report-customers', [ReportController::class, 'customers'])->name('reports.customers');
        Route::get('/report-karyawan', [ReportController::class, 'employees'])->name('reports.employees');
        Route::get('/report-customers/download', [ReportController::class, 'customersDownload'])->name('reports.customers.download');
        Route::get('/report-customers/pdf', [ReportController::class, 'customersPdf'])->name('reports.customers.pdf');
        Route::get('/report-karyawan/download', [ReportController::class, 'employeesDownload'])->name('reports.employees.download');
        Route::get('/report-karyawan/pdf', [ReportController::class, 'employeesPdf'])->name('reports.employees.pdf');
        Route::get('/report-settings', [ReportController::class, 'settings'])->name('reports.settings');
    });

    Route::post('/report-settings', [ReportController::class, 'settingsSave'])
        ->middleware('permission:update reports')->name('reports.settings.save');

    // Tim & Role
    Route::middleware('role:admin')->group(function () {
        Route::resource('tim-dan-role', TeamRoleController::class);
        Route::get('/contact', [ContactController::class, 'create'])->name('contact.index');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    });

    // Contacts
    Route::resource('contacts', ContactController::class);
    Route::get('contacts/advanced', [ContactController::class, 'advancedIndex'])->name('contacts.advanced');
    Route::get('contacts/export', [ContactController::class, 'export'])->name('contacts.export');

    // Customers
    Route::middleware('permission:read customers')->get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::middleware('permission:create customers')->group(function () {
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    });
    Route::middleware('permission:update customers')->group(function () {
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    });
    Route::middleware('permission:delete customers')->delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Pipeline Stages
    Route::middleware('permission:read pipelines')->get('/pipeline-stages', [PipelineStageController::class, 'index'])->name('pipeline-stages.index');
    Route::middleware('permission:create pipelines')->group(function () {
        Route::get('/pipeline-stages/create', [PipelineStageController::class, 'create'])->name('pipeline-stages.create');
        Route::post('/pipeline-stages', [PipelineStageController::class, 'store'])->name('pipeline-stages.store');
    });
    Route::middleware('permission:update pipelines')->group(function () {
        Route::get('/pipeline-stages/{pipelineStage}/edit', [PipelineStageController::class, 'edit'])->name('pipeline-stages.edit');
        Route::put('/pipeline-stages/{pipelineStage}', [PipelineStageController::class, 'update'])->name('pipeline-stages.update');
    });
    Route::middleware('permission:delete pipelines')->delete('/pipeline-stages/{pipelineStage}', [PipelineStageController::class, 'destroy'])->name('pipeline-stages.destroy');

    // Stages
    Route::middleware('permission:read customers')->get('/stages', [CustomerStageHistoryController::class, 'index'])->name('stages.index');
    Route::get('/crm/customers/{customer}', [CustomerController::class, 'show'])->name('crm.show');
    Route::put('/crm/customers/{customer}/stage', [CustomerController::class, 'updateStage'])->name('customers.update-stage');

    // Assign
    Route::middleware('permission:read customers')->get('/assign', [CustomerController::class, 'assign'])->name('assign.index');
    Route::middleware('permission:update customers')->post('/assign-to/{customer}', [CustomerController::class, 'assignTo'])->name('assign.store');

    // Campaign
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::get('/campaigns/active', [CampaignController::class, 'active'])->name('campaign.active');
    Route::get('/campaigns/history', [CampaignController::class, 'history'])->name('campaign.history');
    Route::get('/campaigns/{id}', [CampaignController::class, 'show'])->name('campaign.show');

    // Notifications
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    Route::get('/notifications/read/{id}', function ($id) {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        return redirect($notif->data['url'] ?? '/');
    })->name('notifications.read');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('/products/mass-update', [ProductController::class, 'massUpdate'])->name('products.mass-update');
    Route::get('/products-export-csv', [ProductController::class, 'exportCsv'])->name('products.export.csv');
    Route::post('/products-import-csv', [ProductController::class, 'importCsv'])->name('products.import.csv');
    Route::get('/products-export-xlsx', [ProductController::class, 'exportXlsx'])->name('products.export.xlsx');
    Route::post('/products-import-xlsx', [ProductController::class, 'importXlsx'])->name('products.import.xlsx');

    // Activities & Tasks
    Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities.index');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
});
