<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Perusahaan;
use App\Models\ReportTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;

class ReportController extends Controller
{
    public function customers()
    {
        $companyId = Auth::user()->company_id;
        $company   = Perusahaan::find($companyId);

        // ini hanya untuk tampilan list / preview di halaman Report Customers
        $customers = Customer::where('company_id', $companyId)
            ->latest()
            ->take(50)
            ->get();

        return view('pages.reports.customers', compact('company', 'customers'));
    }

    public function employees()
    {
        $companyId = Auth::user()->company_id;
        $company   = Perusahaan::find($companyId);

        // list karyawan yang mau kamu tampilkan di halaman report karyawan
        $employees = User::where('company_id', $companyId)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['marketing', 'cs']);
            })
            ->get();

        return view('pages.reports.employees', compact('company', 'employees'));
    }

    /**
     * DOWNLOAD HTML REPORT CUSTOMERS (FULL HTML dari TinyMCE)
     */
    public function customersDownload()
    {
        $companyId = Auth::user()->company_id;
        $company   = Perusahaan::find($companyId);

        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('type', 'customers')
            ->first();

        $content      = $tpl?->content ?? '<p>Belum ada template laporan customers yang disimpan.</p>';
        $baseFileName = $tpl?->template_name ?: 'customers';

        $html = view('pages.reports._default_customers', [
            'company' => $company,
            'content' => $content,
        ])->render();

        $filename = $baseFileName . '-' . now()->format('Ymd') . '.html';

        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }


    /**
     * DOWNLOAD PDF REPORT CUSTOMERS (FULL HTML dari TinyMCE)
     */
    public function customersPdf()
    {
        $companyId = Auth::user()->company_id;
        $company   = Perusahaan::find($companyId);
    
        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('type', 'customers')
            ->first();
    
        $content      = $tpl?->content ?? '<p>Belum ada template laporan customers yang disimpan.</p>';
        $baseFileName = $tpl?->template_name ?: 'customers';
    
        // CSS khusus PDF
        $css = <<<CSS
    @page {
        size: A4;
        margin: 20mm 15mm;
    }
    
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        line-height: 1.5;
    }
    
    .report-wrapper {
        /* kalau mau kasih padding di seluruh isi */
    }
    CSS;
    
        // FRAGMENT BODY dari Blade (_default_customers)
        $bodyHtml = view('pages.reports._default_customers', [
            'company' => $company,
            'content' => $content,
        ])->render();
    
        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 15,
            'margin_right'  => 15,
            'margin_top'    => 20,
            'margin_bottom' => 20,
        ]);
    
        // CSS dulu
        $mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
        // Baru body HTML (tanpa <html>/<head>)
        $mpdf->WriteHTML($bodyHtml, HTMLParserMode::HTML_BODY);
    
        $filename = $baseFileName . '-' . now()->format('Ymd') . '.pdf';
    
        return response(
            $mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN)
        )
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }


    public function employeesDownload()
    {
        $companyId = Auth::user()->company_id;
        $company   = Perusahaan::find($companyId);

        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('type', 'employees')
            ->first();

        $content      = $tpl?->content ?? '<p>Belum ada template laporan karyawan yang disimpan.</p>';
        $baseFileName = $tpl?->template_name ?: 'employees';

        $html = view('pages.reports._default_employees', [
            'company' => $company,
            'content' => $content,
        ])->render();

        $filename = $baseFileName . '-' . now()->format('Ymd') . '.html';

        return response($html, 200, [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }


    public function employeesPdf()
    {
        $companyId = Auth::user()->company_id;
        $company   = Perusahaan::find($companyId);

        $tpl = ReportTemplate::where('company_id', $companyId)
            ->where('type', 'employees')
            ->first();

        $content      = $tpl?->content ?? '<p>Belum ada template laporan karyawan yang disimpan.</p>';
        $baseFileName = $tpl?->template_name ?: 'employees';

        // Render HTML final (A4 + konten TinyMCE)
        $html = view('pages.reports._default_employees', [
            'company' => $company,
            'content' => $content,
        ])->render();


        // Pakai config mPDF yang eksplisit & bersih
        $mpdf = new \Mpdf\Mpdf([
            'mode'           => 'utf-8',
            'format'         => 'A4',
            'margin_left'    => 15,
            'margin_right'   => 15,
            'margin_top'     => 20,
            'margin_bottom'  => 20,
        ]);

        $mpdf->WriteHTML($html);

        $filename = $baseFileName . '-' . now()->format('Ymd') . '.pdf';

        return response(
            $mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN)
        )
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    

    /**
     * HALAMAN PENGATURAN TEMPLATE (TINYMCE)
     */
    public function settings()
    {
        $companyId = Auth::user()->company_id;
        $company   = Perusahaan::find($companyId);

        // Template karyawan
        $employeesRow = ReportTemplate::where('company_id', $companyId)
            ->where('type', 'employees')
            ->first();

        $employeesTpl          = $employeesRow?->content ?? '';
        $employeesTemplateName = $employeesRow?->template_name ?? 'employees';

        // Template customers
        $customersRow = ReportTemplate::where('company_id', $companyId)
            ->where('type', 'customers')
            ->first();

        $customersTpl          = $customersRow?->content ?? '';
        $customersTemplateName = $customersRow?->template_name ?? 'customers';

        return view('pages.reports.settings', compact(
            'company',
            'customersTpl',
            'employeesTpl',
            'customersTemplateName',
            'employeesTemplateName',
        ));
    }

    public function settingsSave(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $data = $request->validate([
            'customers_template'       => ['nullable', 'string'],
            'employees_template'       => ['nullable', 'string'],
            'customers_template_name'  => ['nullable', 'string', 'max:150'],
            'employees_template_name'  => ['nullable', 'string', 'max:150'],
        ]);

        // Simpan template KARYAWAN
        if ($request->filled('employees_template') || $request->filled('employees_template_name')) {
            $employeesTpl = ReportTemplate::firstOrNew([
                'company_id' => $companyId,
                'type'       => 'employees',
            ]);

            if (!empty($data['employees_template_name'])) {
                $employeesTpl->template_name = $data['employees_template_name'];
            } elseif (empty($employeesTpl->template_name)) {
                $employeesTpl->template_name = 'employees';
            }

            if ($request->filled('employees_template')) {
                $employeesTpl->content = $data['employees_template'];
            }

            $employeesTpl->save();
        }

        // Simpan template CUSTOMERS
        if ($request->filled('customers_template') || $request->filled('customers_template_name')) {
            $customersTpl = ReportTemplate::firstOrNew([
                'company_id' => $companyId,
                'type'       => 'customers',
            ]);

            if (!empty($data['customers_template_name'])) {
                $customersTpl->template_name = $data['customers_template_name'];
            } elseif (empty($customersTpl->template_name)) {
                $customersTpl->template_name = 'customers';
            }

            if ($request->filled('customers_template')) {
                $customersTpl->content = $data['customers_template'];
            }

            $customersTpl->save();
        }

        return redirect()
            ->route('reports.settings')
            ->with('success', 'Template laporan disimpan');
    }

}
