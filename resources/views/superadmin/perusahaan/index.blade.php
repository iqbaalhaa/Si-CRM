@extends('layouts.master')

@section('content')
<div class="page-heading">
    <h3>Perusahaan</h3>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perusahaans as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->code }}</td>
                                        <td>{{ $p->address }}</td>
                                        <td>{{ $p->phone }}</td>
                                        <td>{{ $p->email }}</td>
                                        <td>{{ $p->status }}</td>
                                        <td>
                                            <a href="{{ route('perusahaan.edit', $p->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data perusahaan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $perusahaans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

