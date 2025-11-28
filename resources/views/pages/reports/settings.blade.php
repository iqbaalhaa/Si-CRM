@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Report Settings</h3>
    <div class="text-muted">{{ $company->name ?? 'Perusahaan' }}</div>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            {{-- Satu form untuk semua template --}}
            <form action="{{ route('reports.settings.save') }}" method="POST">
                @csrf

                {{-- ======================= --}}
                {{-- Template Karyawan      --}}
                {{-- ======================= --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Template Karyawan</h6>
                    </div>

                    {{-- Nama file (template_name untuk karyawan) --}}
                    <div class="mb-2">
                        <label for="employees-template-name" class="form-label small mb-1">
                            Nama File Karyawan (template_name)
                        </label>
                        <input
                            type="text"
                            id="employees-template-name"
                            name="employees_template_name"
                            class="form-control form-control-sm @error('employees_template_name') is-invalid @enderror"
                            placeholder="misal: report-karyawan"
                            value="{{ old('employees_template_name', $employeesTemplateName ?? 'employees') }}"
                        >
                        @error('employees_template_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Nilai ini akan dipakai sebagai nama file saat download (misal: <code>report-karyawan-20251128.pdf</code>).
                        </small>
                    </div>

                    {{-- Isi HTML dari TinyMCE --}}
                    <textarea
                        class="tinymce-editor"
                        id="employees-editor"
                        name="employees_template"
                        rows="12"
                    >{!! $employeesTpl !!}</textarea>

                    @error('employees_template')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                {{-- ======================= --}}
                {{-- Template Customers      --}}
                {{-- ======================= --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Template Customers</h6>
                    </div>

                    {{-- Nama file (template_name untuk customers) --}}
                    <div class="mb-2">
                        <label for="customers-template-name" class="form-label small mb-1">
                            Nama File Customers (template_name)
                        </label>
                        <input
                            type="text"
                            id="customers-template-name"
                            name="customers_template_name"
                            class="form-control form-control-sm @error('customers_template_name') is-invalid @enderror"
                            placeholder="misal: report-customers"
                            value="{{ old('customers_template_name', $customersTemplateName ?? 'customers') }}"
                        >
                        @error('customers_template_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Nilai ini akan dipakai sebagai nama file saat download (misal: <code>report-customers-20251128.pdf</code>).
                        </small>
                    </div>

                    {{-- Isi HTML dari TinyMCE --}}
                    <textarea
                        class="tinymce-editor"
                        id="customers-editor"
                        name="customers_template"
                        rows="12"
                    >{!! $customersTpl !!}</textarea>

                    @error('customers_template')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary" type="submit">
                        Simpan Semua Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <x-head.tinymce-config/>
@endpush
