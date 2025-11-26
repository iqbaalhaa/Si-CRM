@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Perusahaan</h3>
    <a href="{{ route('perusahaan.create') }}" class="btn btn-primary">Tambah Perusahaan</a>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-perusahaan">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perusahaans as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->code }}</td>
                                        <td>{{ $p->address }}</td>
                                        <td>{{ $p->phone }}</td>
                                        <td>{{ $p->email }}</td>
                                        <td>
                                            <span class="badge {{ strcasecmp($p->status, 'Aktif') === 0 ? 'bg-success' : 'bg-danger' }}">{{ $p->status }}</span>
                                        </td>
                                        <td>{{ $p->created_at?->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('perusahaan.edit', $p->id) }}" class="btn btn-sm btn-secondary"><i class="bi bi-pencil-square"></i></a>
                                            <form action="{{ route('perusahaan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus perusahaan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data perusahaan</td>
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
<link rel="stylesheet" href="{{ asset('admindash/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admindash/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admindash/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(function(){
        $('#table-perusahaan').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']]
        });
    });
</script>
@endpush

