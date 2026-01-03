@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h3>Report Customers</h3>
        <div class="text-muted">{{ $company->name ?? 'Perusahaan' }}</div>
    </div>
    
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Sumber</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
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
                            <td class="text-center text-nowrap">
                                <a href="{{ route('reports.customers.download', ['customer' => $c->id]) }}"
                                   class="btn btn-sm btn-light"
                                   title="Download Report HTML">
                                    <i class="bi bi-download"></i>
                                </a>

                                <a href="{{ route('reports.customers.pdf', ['customer' => $c->id]) }}"
                                   class="btn btn-sm btn-outline-danger"
                                   title="Download Report PDF">
                                    <i class="bi bi-filetype-pdf"></i>
                                </a>
                            </td>
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
