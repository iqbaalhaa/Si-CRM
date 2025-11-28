@extends('layouts.master')

@section('content')
    <div class="page-heading mb-3">
        <h3>Customer</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Daftar Customer</h5>

                            @can('create customers')
                                <a href="{{ route('customers.create') }}"
                                   class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-plus-circle"></i>
                                    <span>Tambah Customer</span>
                                </a>
                            @endcan
                        </div>

                        @php
                            $canUpdate = auth()->user()->can('update customers');
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-customers">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Perusahaan</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                        <th>Stage</th>
                                        @if ($canUpdate)
                                            <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $c)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $c->name }}</td>
                                            <td>{{ optional($c->company)->name ?? '-' }}</td>
                                            <td>{{ optional($c->company)->address ?? '-' }}</td>
                                            <td>{{ $c->phone }}</td>
                                            <td>{{ $c->email }}</td>
                                            <td>{{ optional($c->stage)->name ?? '-' }}</td>

                                            @if ($canUpdate)
                                                <td class="text-center">
                                                    <a href="{{ route('customers.edit', $c->id) }}"
                                                       class="btn btn-sm btn-secondary">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('customers.destroy', $c->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Hapus customer ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($customers->isEmpty())
                            <div class="text-center text-muted py-3">
                                Belum ada data customer
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet"
          href="{{ asset('admindash/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(function() {
            $('#table-customers').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [[0, 'asc']]
            });
        });
    </script>
@endpush
