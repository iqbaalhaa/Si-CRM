@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h3>Report Karyawan</h3>
        <div class="text-muted">{{ $company->name ?? 'Perusahaan' }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.employees.download') }}" class="btn btn-primary"><i class="bi bi-download me-1"></i> Download HTML</a>
        <a href="{{ route('reports.employees.pdf') }}" class="btn btn-outline-primary"><i class="bi bi-filetype-pdf me-1"></i> Download PDF</a>
    </div>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->getRoleNames()->first() }}</td>
                            <td>{{ $u->created_at?->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
