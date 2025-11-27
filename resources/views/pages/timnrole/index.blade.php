@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Tim & Role</h3>
    <div></div>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Akun Tim</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teamrole.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nama lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="email@perusahaan.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label fw-semibold">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="marketing">Marketing</option>
                                <option value="cs">CS</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Buat Akun</button>
                            <button type="reset" class="btn btn-light">Bersihkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Akun Tim Perusahaan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-team">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamUsers as $u)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ $u->getRoleNames()->first() }}</td>
                                        <td>{{ $u->created_at?->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Belum ada akun tim</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
        $('#table-team').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'asc']]
        });
    });
</script>
@endpush
