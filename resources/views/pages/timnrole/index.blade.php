@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Tim & Role</h3>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Akun Tim Perusahaan</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-team">Tambah Akun Tim</button>
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
                                    <th>Aksi</th>
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
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-secondary btn-edit-team"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                data-email="{{ $u->email }}"
                                                data-role="{{ $u->getRoleNames()->first() }}"
                                                data-action="{{ route('teamrole.update', $u->id) }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('teamrole.destroy', $u->id) }}" method="POST" class="d-inline team-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Belum ada akun tim</td>
                                        <td></td>
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

        $('[data-bs-target="#modal-team"]').on('click', function(){
            const form = $('#modal-team form');
            form.attr('action', '{{ route('teamrole.store') }}');
            form.find('input[name=_method]').remove();
            $('#modal-team .modal-title').text('Tambah Akun Tim');
            form[0].reset();
            $('#modal-team input#name')[0].required = true;
            $('#modal-team input#email')[0].required = true;
            $('#modal-team input#password')[0].required = true;
            $('#modal-team select#role')[0].required = true;
        });

        $('.btn-edit-team').on('click', function(){
            const id = $(this).data('id');
            const name = $(this).data('name');
            const email = $(this).data('email');
            const role = $(this).data('role');
            const action = $(this).data('action');

            const form = $('#modal-team form');
            form.attr('action', action);
            if (!form.find('input[name=_method]').length) {
                form.prepend('<input type="hidden" name="_method" value="PUT">');
            }
            $('#modal-team .modal-title').text('Edit Akun Tim');
            $('#modal-team input#name').val(name);
            $('#modal-team input#email').val(email);
            $('#modal-team input#password').val('');
            $('#modal-team select#role').val(role);
            const m = new bootstrap.Modal(document.getElementById('modal-team'));
            m.show();
            $('#modal-team input#name')[0].required = false;
            $('#modal-team input#email')[0].required = false;
            $('#modal-team select#role')[0].required = false;
            $('#modal-team input#password')[0].required = false;
        });

        $('.team-delete-form').on('submit', function(e){
            e.preventDefault();
            const form = this;
            Swal.fire({
                icon: 'warning',
                title: 'Hapus akun?',
                text: 'Tindakan tidak bisa dibatalkan',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush

@push('scripts')
<div class="modal fade" id="modal-team" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('teamrole.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Akun Tim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control"  placeholder="Kosongkan jika tidak ingin mengganti password" minlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="marketing">Marketing</option>
                            <option value="cs">CS</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endpush
