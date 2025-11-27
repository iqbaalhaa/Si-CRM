@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Manage Admin Perusahaan</h3>
    <a href="{{ url('/perusahaan') }}" class="btn btn-secondary">Lihat Perusahaan</a>
</div>

@php
    $perusahaans = $perusahaans ?? \App\Models\Perusahaan::orderBy('name')->get();
    $admins = $admins ?? \App\Models\User::role('admin')->get();
@endphp

<div class="page-content">
    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Admin Perusahaan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/manage-admin-perusahaan') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="company_id" class="form-label fw-semibold">Perusahaan</label>
                            <select name="company_id" id="company_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Perusahaan</option>
                                @foreach($perusahaans as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Admin</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nama lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="email@perusahaan.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Awal</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Daftarkan Admin</button>
                            <a href="{{ url('/manage-admin-perusahaan') }}" class="btn btn-light">Bersihkan</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Admin Perusahaan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-admins">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Perusahaan</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $u)
                                    @php $company = $u->company_id ? \App\Models\Perusahaan::find($u->company_id) : null; @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ $company?->name ?? '-' }}</td>
                                        <td>{{ $u->created_at?->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada admin perusahaan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-info">
                Super Admin mendaftarkan admin perusahaan. Admin perusahaan akan login dan mengelola akun CS & Marketing per perusahaan.
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
        $('#table-admins').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
