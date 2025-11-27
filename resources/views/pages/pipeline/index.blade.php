@extends('layouts.master')

@section('content')
    <div class="page-heading mb-3">
        <h3>Pipeline</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Daftar Pipeline</h5>

                            @can('create Pipelines')
                                <a href="{{ route('Pipelines.create') }}"
                                    class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-plus-circle"></i>
                                    <span>Tambah Pipeline</span>
                                </a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-Pipelines">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Perusahaan</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                        <th>Stage</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($Pipelines as $c)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $c->name }}</td>
                                            <td>{{ optional($c->company)->name ?? '-' }}</td>
                                            <td>{{ optional($c->company)->address ?? '-' }}</td>
                                            <td>{{ $c->phone }}</td>
                                            <td>{{ $c->email }}</td>
                                            <td>{{ optional($c->stage)->name ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('Pipelines.edit', $c->id) }}"
                                                    class="btn btn-sm btn-secondary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('Pipelines.destroy', $c->id) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Hapus Pipeline ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada data Pipeline</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

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
            $('#table-Pipelines').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
@endpush
