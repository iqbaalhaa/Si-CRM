@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h3>Report Customers</h3>
        <div class="text-muted">{{ $company->name ?? 'Perusahaan' }}</div>
    </div>
    <a href="{{ route('reports.customers.download') }}" class="btn btn-primary"><i class="bi bi-download me-1"></i> Download Report</a>
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
                            <th>Telepon</th>
                            <th>Sumber</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone }}</td>
                            <td>{{ $c->source }}</td>
                            <td>{{ $c->created_at?->format('d M Y') }}</td>
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
