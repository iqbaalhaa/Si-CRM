@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h3>Report Karyawan</h3>
        <div class="text-muted">{{ $company->name ?? 'Perusahaan' }}</div>
    </div>
    {{-- tombol download all dihapus, karena sekarang per-user --}}
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
                            <th>Role</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th> {{-- kolom baru --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->getRoleNames()->first() }}</td>
                            <td>{{ $u->created_at?->format('d M Y') }}</td>
                            <td class="text-center text-nowrap">
                                {{-- Download HTML per karyawan --}}
                                <a href="{{ route('reports.employees.download', $u->id) }}"
                                   class="btn btn-sm btn-light"
                                   title="Download Report HTML">
                                    <i class="bi bi-download"></i>
                                </a>

                                {{-- Download PDF per karyawan --}}
                                <a href="{{ route('reports.employees.pdf', $u->id) }}"
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
