<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Perusahaan;
use App\Models\ReportTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

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
        $employees = User::where('company_id', $companyId)->whereHas('roles', function ($q) {
            $q->whereIn('name', ['marketing', 'cs']);
        })->get();

        return view('pages.reports.employees', compact('company', 'employees'));
    }

    public function customersDownload()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $customers = Customer::where('company_id', $companyId)->latest()->take(50)->get();

        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('template_name', 'customers')
            ->first();

        $html = $tpl?->content ?? view('pages.reports._default_customers', compact('company', 'customers'))->render();

        $filename = 'report-customers-'.now()->format('Ymd').'.html';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    public function customersPdf()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $customers = Customer::where('company_id', $companyId)->latest()->take(50)->get();

        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('template_name', 'customers')
            ->first();

        $htmlContent = $tpl?->content ?? view('pages.reports._default_customers', compact('company', 'customers'))->render();

        $html = '<html><head><meta charset="utf-8"><style>'.
            'body{font-family:DejaVu Sans, sans-serif; font-size:12px;}'.
            'h2,h3{margin:0 0 10px;}'.
            'table{width:100%; border-collapse:collapse;}'.
            'th,td{border:1px solid #333; padding:6px;}'.
            'thead th{background:#f2f2f2;}'.
            '</style></head><body>'.$htmlContent.'</body></html>';

        $mpdf = new Mpdf(['format' => 'A4', 'orientation' => 'P']);
        $mpdf->WriteHTML($html);
        $filename = 'report-customers-'.now()->format('Ymd').'.pdf';

        return response($mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function employeesDownload()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $employees = User::where('company_id', $companyId)->whereHas('roles', function ($q) {
            $q->whereIn('name', ['marketing', 'cs']);
        })->get();

        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('template_name', 'employees')
            ->first();

        $html = $tpl?->content ?? view('pages.reports._default_employees', compact('company', 'employees'))->render();

        $filename = 'report-karyawan-'.now()->format('Ymd').'.html';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    public function employeesPdf()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $employees = User::where('company_id', $companyId)->whereHas('roles', function ($q) {
            $q->whereIn('name', ['marketing', 'cs']);
        })->get();

        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('template_name', 'employees')
            ->first();

        $htmlContent = $tpl?->content ?? view('pages.reports._default_employees', compact('company', 'employees'))->render();

        $html = '<html><head><meta charset="utf-8"><style>'.
            'body{font-family:DejaVu Sans, sans-serif; font-size:12px;}'.
            'h2,h3{margin:0 0 10px;}'.
            'table{width:100%; border-collapse:collapse;}'.
            'th,td{border:1px solid #333; padding:6px;}'.
            'thead th{background:#f2f2f2;}'.
            '</style></head><body>'.$htmlContent.'</body></html>';

        $mpdf = new Mpdf(['format' => 'A4', 'orientation' => 'P']);
        $mpdf->WriteHTML($html);
        $filename = 'report-karyawan-'.now()->format('Ymd').'.pdf';

        return response($mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function settings()
    {
        $companyId = Auth::user()->company_id;
        $company = Perusahaan::find($companyId);
        $customers = Customer::where('company_id', $companyId)->latest()->take(50)->get();
        $employees = User::where('company_id', $companyId)->whereHas('roles', function ($q) {
            $q->whereIn('name', ['marketing', 'cs']);
        })->get();

        $defaultCustomers = view('pages.reports._default_customers', compact('company', 'customers'))->render();
        $defaultEmployees = view('pages.reports._default_employees', compact('company', 'employees'))->render();

        $customersTpl = ReportTemplate::where('company_id', $companyId)
            ->where('template_name', 'customers')
            ->value('content') ?? $defaultCustomers;

        $employeesTpl = ReportTemplate::where('company_id', $companyId)
            ->where('template_name', 'employees')
            ->value('content') ?? $defaultEmployees;

        return view('pages.reports.settings', compact('company', 'customersTpl', 'employeesTpl'));
    }

    public function settingsSave(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $data = $request->validate([
            'customers_template' => ['nullable', 'string'],
            'employees_template' => ['nullable', 'string'],
        ]);

        if ($request->filled('customers_template')) {
            ReportTemplate::updateOrCreate(
                ['company_id' => $companyId, 'template_name' => 'customers'],
                ['content' => $data['customers_template']]
            );
        }

        if ($request->filled('employees_template')) {
            ReportTemplate::updateOrCreate(
                ['company_id' => $companyId, 'template_name' => 'employees'],
                ['content' => $data['employees_template']]
            );
        }

        return redirect()->route('reports.settings')->with('success', 'Template laporan disimpan');
    }
}
