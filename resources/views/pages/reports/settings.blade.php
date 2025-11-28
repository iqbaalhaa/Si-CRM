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
            <form action="{{ route('reports.settings.save') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Template Karyawan</h6>
                        </div>
                        <textarea class="tinymce-editor" id="employees-editor" name="employees_template" rows="12">{!! $employeesTpl !!}</textarea>
                        @error('employees_template')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <h6 class="mb-2">Template Customers</h6>
                        <textarea class="tinymce-editor" id="customers-editor" name="customers_template" rows="12">{!! $customersTpl !!}</textarea>
                        @error('customers_template')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary" type="submit">Simpan Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<x-head.tinymce-config/>
<script>
    document.getElementById('btn-maximize-employees')?.addEventListener('click', function(){
        var ed = tinymce.get('employees-editor');
        if (ed) ed.execCommand('mceFullScreen');
    });
    </script>
@endpush
