<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Perusahaan;

class ReportController extends Controller
{
    public function customers()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $customers = Customer::where('company_id', $companyId)->latest()->take(50)->get();
        return view('pages.reports.customers', compact('company', 'customers'));
    }

    public function employees()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $employees = User::where('company_id', $companyId)->whereHas('roles', function($q){ $q->whereIn('name', ['marketing','cs']); })->get();
        return view('pages.reports.employees', compact('company', 'employees'));
    }

    public function customersDownload()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $customers = Customer::where('company_id', $companyId)->latest()->take(50)->get();

        if (Storage::disk('local')->exists("reports/$companyId/customers.html")) {
            $html = Storage::disk('local')->get("reports/$companyId/customers.html");
        } else {
            $html = view('pages.reports._default_customers', compact('company','customers'))->render();
        }

        $filename = 'report-customers-'.now()->format('Ymd').'.html';
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    public function employeesDownload()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $employees = User::where('company_id', $companyId)->whereHas('roles', function($q){ $q->whereIn('name', ['marketing','cs']); })->get();

        if (Storage::disk('local')->exists("reports/$companyId/employees.html")) {
            $html = Storage::disk('local')->get("reports/$companyId/employees.html");
        } else {
            $html = view('pages.reports._default_employees', compact('company','employees'))->render();
        }

        $filename = 'report-karyawan-'.now()->format('Ymd').'.html';
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    public function settings()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $customers = Customer::where('company_id', $companyId)->latest()->take(50)->get();
        $employees = User::where('company_id', $companyId)->whereHas('roles', function($q){ $q->whereIn('name', ['marketing','cs']); })->get();

        $defaultCustomers = view('pages.reports._default_customers', compact('company','customers'))->render();
        $defaultEmployees = view('pages.reports._default_employees', compact('company','employees'))->render();

        $customersTpl = Storage::disk('local')->exists("reports/$companyId/customers.html")
            ? Storage::disk('local')->get("reports/$companyId/customers.html")
            : $defaultCustomers;

        $employeesTpl = Storage::disk('local')->exists("reports/$companyId/employees.html")
            ? Storage::disk('local')->get("reports/$companyId/employees.html")
            : $defaultEmployees;

        return view('pages.reports.settings', compact('company', 'customersTpl', 'employeesTpl'));
    }

    public function settingsSave(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $data = $request->validate([
            'customers_template' => ['required', 'string'],
            'employees_template' => ['required', 'string'],
        ]);

        Storage::disk('local')->put("reports/$companyId/customers.html", $data['customers_template']);
        Storage::disk('local')->put("reports/$companyId/employees.html", $data['employees_template']);

        return redirect()->route('reports.settings')->with('success', 'Template laporan disimpan');
    }
}
