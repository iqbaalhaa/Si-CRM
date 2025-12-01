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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-team">
                        Tambah Akun Tim
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-team">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
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
                                        <td>{{ $u->profile->job_title ?? '-' }}</td>
                                        <td>{{ $u->getRoleNames()->first() }}</td>
                                        <td>{{ $u->created_at?->format('d M Y') }}</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-sm btn-secondary btn-edit-team"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                data-email="{{ $u->email }}"
                                                data-job_title="{{ $u->profile->job_title }}"
                                                data-action="{{ route('teamrole.update', $u->id) }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('teamrole.destroy', $u->id) }}"
                                                  method="POST"
                                                  class="d-inline team-delete-form">
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
                                        <td class="text-center" colspan="7">Belum ada akun tim</td>
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
    $(function(){
        $('#table-team').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'asc']]
        });

        // Tombol "Tambah Akun Tim"
        $('[data-bs-target="#modal-team"]').on('click', function(){
            const form = $('#modal-team form');
            form.attr('action', '{{ route('teamrole.store') }}');
            form.find('input[name=_method]').remove();
            $('#modal-team .modal-title span').text('Tambah Akun Tim');
            form[0].reset();

            $('#modal-team input#name')[0].required = true;
            $('#modal-team input#email')[0].required = true;
            $('#modal-team input#password')[0].required = true;
            $('#modal-team input#job_title')[0].required = false;

            $('#modal-team input#password').attr('placeholder', '');
        });

        // Tombol "Edit"
        $('.btn-edit-team').on('click', function(){
            const id        = $(this).data('id');
            const name      = $(this).data('name');
            const email     = $(this).data('email');
            const jobTitle  = $(this).data('job_title');
            const action    = $(this).data('action');

            const form = $('#modal-team form');
            form.attr('action', action);
            if (!form.find('input[name=_method]').length) {
                form.prepend('<input type="hidden" name="_method" value="PUT">');
            }

            $('#modal-team .modal-title span').text('Edit Akun Tim');
            $('#modal-team input#name').val(name);
            $('#modal-team input#email').val(email);
            $('#modal-team input#job_title').val(jobTitle ?? '');
            $('#modal-team input#password').val('');

            const m = new bootstrap.Modal(document.getElementById('modal-team'));
            m.show();

            $('#modal-team input#name')[0].required      = false;
            $('#modal-team input#email')[0].required     = false;
            $('#modal-team input#job_title')[0].required = false;
            $('#modal-team input#password')[0].required  = false;
            $('#modal-team input#password').attr('placeholder', 'Kosongkan jika tidak ingin mengganti password');
        });

        // Konfirmasi hapus
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
<div class="modal fade" id="modal-team" tabindex="-1" aria-labelledby="modalTeamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('teamrole.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <div>
                        <h5 class="modal-title d-flex align-items-center gap-2" id="modalTeamLabel">
                            <i class="bi bi-people-fill"></i>
                            <span>Tambah Akun Tim</span>
                        </h5>
                        <small class="d-block fw-normal text-white-50">
                            Buat akun Lead Operations untuk perusahaan ini.
                        </small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Kolom kiri: identitas --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       placeholder="Misal: Rina Putri" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Kerja</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       placeholder="nama@perusahaan.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="job_title" class="form-label">Job Title</label>
                                <input type="text" name="job_title" id="job_title" class="form-control"
                                       placeholder="Misal: Lead Operations, Kepala Tim CRM">
                            </div>
                        </div>

                        {{-- Kolom kanan: password & role (fixed) --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="form-label mb-0">Password</label>
                                    <small class="text-muted">Min. 8 karakter</small>
                                </div>
                                <input type="password" name="password" id="password" class="form-control"
                                       minlength="8" placeholder="••••••••">
                            </div>

                            <div class="mb-3">
                                <label class="form-label mb-1">Role</label>
                                <input type="text" class="form-control" value="lead-operations" readonly>
                                {{-- hidden input supaya tetap terkirim ke server --}}
                                <input type="hidden" name="role" value="lead-operations">
                                <small class="text-muted">
                                    Role ini dikunci sebagai <strong>lead-operations</strong> dan tidak dapat diubah di sini.
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex align-items-center gap-2 small text-muted">
                        <i class="bi bi-shield-lock"></i>
                        <span>
                            Data akun ini akan digunakan untuk login ke sistem CRM.
                            Pastikan email aktif dan password disimpan dengan aman.
                        </span>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
