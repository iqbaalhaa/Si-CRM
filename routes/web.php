<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerStageHistoryController;
use App\Http\Controllers\PipelineStageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// =========================
// Guest only
// =========================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Login routes (accessible even if already authenticated)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Redirect root to login explicitly
Route::get('/', function () {
    return redirect()->route('login');
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

        if (! $user) {
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
            if ($user->hasRole('lead-operations')) {
                return redirect()->route('dashboard.lead_operations');
            }
        }

        // Fallback terakhir
        return redirect()->route('dashboard.admin');
    })->name('dashboard');

    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadmin'])
        ->middleware('role:super-admin')
        ->name('dashboard.superadmin');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/lead-operations', [DashboardController::class, 'leadOperations'])
        ->middleware('role:lead-operations')
        ->name('dashboard.lead_operations');

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

        // Contact (legacy routes, akan diganti dengan RESTful di bawah)
        Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'create'])
            ->name('contact.index');

        Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])
            ->name('contact.store');

        Route::get('/setting-menu', [\App\Http\Controllers\ProfileController::class, 'editSelf'])
            ->name('settings.profile');

        Route::post('/setting-menu', [\App\Http\Controllers\ProfileController::class, 'updateSelf'])
            ->name('settings.profile.update');
    });

    // Contacts (RESTful)
    Route::get('/contacts', [\App\Http\Controllers\ContactController::class, 'index'])
        ->name('contacts.index');
    Route::get('/contacts/create', [\App\Http\Controllers\ContactController::class, 'create'])
        ->name('contacts.create');
    Route::post('/contacts', [\App\Http\Controllers\ContactController::class, 'store'])
        ->name('contacts.store');
    Route::get('/contacts/{contact}', [\App\Http\Controllers\ContactController::class, 'show'])
        ->whereNumber('contact')
        ->name('contacts.show');
    Route::get('/contacts/{contact}/edit', [\App\Http\Controllers\ContactController::class, 'edit'])
        ->whereNumber('contact')
        ->name('contacts.edit');
    Route::put('/contacts/{contact}', [\App\Http\Controllers\ContactController::class, 'update'])
        ->whereNumber('contact')
        ->name('contacts.update');
    Route::delete('/contacts/{contact}', [\App\Http\Controllers\ContactController::class, 'destroy'])
        ->whereNumber('contact')
        ->name('contacts.destroy');
    Route::get('contacts/advanced', [\App\Http\Controllers\ContactController::class, 'advancedIndex'])
        ->name('contacts.advanced');
    Route::get('contacts/export', [\App\Http\Controllers\ContactController::class, 'export'])
        ->name('contacts.export');
    // === IMPORT ===
    Route::get('contacts/template/{type}', [\App\Http\Controllers\ContactController::class, 'downloadTemplate'])
        ->whereIn('type', ['individual', 'company', 'organization'])
        ->name('contacts.template');

    Route::post('contacts/import', [\App\Http\Controllers\ContactController::class, 'import'])
        ->name('contacts.import');

     

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

    // /////////////////////////////////////////////////////////////////////////////
    // Campaign
    Route::get('/campaigns/create', [CampaignController::class, 'create'])
        ->name('campaign.create');

    Route::post('/campaigns', [CampaignController::class, 'store'])
        ->name('campaign.store');

    Route::post('/campaigns/preview', [CampaignController::class, 'preview'])
        ->name('campaign.preview');
    
    Route::get('/campaigns/products-search', [CampaignController::class, 'productsSearch'])
        ->name('campaign.products.search');

    Route::get('/campaigns/active', [CampaignController::class, 'active'])
        ->name('campaign.active');

    Route::get('/campaigns/history', [CampaignController::class, 'history'])
        ->name('campaign.history');

    Route::get('/campaigns/{id}', [CampaignController::class, 'show'])
        ->whereNumber('id')
        ->name('campaign.show');

    Route::post('/campaigns/{id}/contacts/{ccId}/stage', [CampaignController::class, 'updateContactStage'])
        ->whereNumber('id')
        ->whereNumber('ccId')
        ->name('campaign.contacts.stage');

    Route::post('/campaigns/{id}/contacts/{contactId}/products', [CampaignController::class, 'updateContactProducts'])
        ->whereNumber('id')
        ->whereNumber('contactId')
        ->name('campaign.contacts.products');

    Route::post('/campaigns/{id}/team', [CampaignController::class, 'updateTeam'])
        ->whereNumber('id')
        ->name('campaign.team.update');

    Route::post('/campaigns/{id}/contacts/assign', [CampaignController::class, 'assignContacts'])
        ->whereNumber('id')
        ->name('campaign.contacts.assign');

    Route::post('/campaigns/{id}/contacts/import', [CampaignController::class, 'importContacts'])
        ->whereNumber('id')
        ->name('campaign.contacts.import');

    Route::get('/campaigns/{id}/contacts/{ccId}/pipeline', [CampaignController::class, 'pipeline'])
        ->whereNumber('id')
        ->whereNumber('ccId')
        ->name('campaign.contacts.pipeline');

    Route::get('/campaigns/{id}/edit', [CampaignController::class, 'edit'])
        ->whereNumber('id')
        ->name('campaign.edit');

    Route::put('/campaigns/{id}', [CampaignController::class, 'update'])
        ->whereNumber('id')
        ->name('campaign.update');

    Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy'])
        ->whereNumber('id')
        ->name('campaign.destroy');
    // /////////////////////////////////////////////////////////////////////////////

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

    // Resource
    Route::resource('products', ProductController::class);

    // Mass update
    Route::post('/products/mass-update', [ProductController::class, 'massUpdate'])
        ->name('products.mass-update');

    // Export / import CSV
    Route::get('/products-export-csv', [ProductController::class, 'exportCsv'])
        ->name('products.export.csv');

    Route::post('/products-import-csv', [ProductController::class, 'importCsv'])
        ->name('products.import.csv');

    // Export / import XLSX
    Route::get('/products-export-xlsx', [ProductController::class, 'exportXlsx'])
        ->name('products.export.xlsx');

    Route::post('/products-import-xlsx', [ProductController::class, 'importXlsx'])
        ->name('products.import.xlsx');

    Route::get('/products-template-xlsx', [ProductController::class, 'templateXlsx'])
        ->name('products.template.xlsx');

    Route::post('/products-export-selected-xlsx', [ProductController::class, 'exportSelectedXlsx'])
        ->name('products.export.selected.xlsx');

    Route::post('/products/mass-delete', [ProductController::class, 'massDelete'])
        ->name('products.mass-delete');

    // /////////////////////////////////////////////////////////////////////
    Route::get('/activities', [ActivitiesController::class, 'index'])
        ->name('activities.index');
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    // /////////////////////////////////////////////////////////////////////
});
