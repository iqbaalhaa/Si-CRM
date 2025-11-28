@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>Manage Admin Perusahaan</h3>
    <div class="d-flex gap-2">
        <a href="{{ url('/perusahaan') }}" class="btn btn-light">Lihat Perusahaan</a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-admin">
            <i class="bi bi-person-plus me-1"></i> Tambah Admin Perusahaan
        </button>
    </div>
</div>

@php
    $perusahaans = $perusahaans ?? \App\Models\Perusahaan::orderBy('name')->get();
    $admins = $admins ?? \App\Models\User::role('admin')->get();
@endphp

<div class="page-content">
    <div class="row">
        <div class="col-12">
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
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
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
                                        <td>
                                            <div class="form-check form-switch d-inline-block">
                                                <input type="checkbox" class="form-check-input toggle-active"
                                                    data-id="{{ $u->id }}"
                                                    data-action="{{ route('manageadmin.active', $u->id) }}"
                                                    {{ $u->is_active ? 'checked' : '' }}>
                                            </div>
                                            <span class="ms-2 small text-muted">{{ $u->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        </td>
                                        <td>{{ $u->created_at?->format('d M Y') }}</td>
                                        <td class="text-nowrap">
                                            <button type="button"
                                                class="btn btn-sm btn-primary btn-edit-admin"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                data-email="{{ $u->email }}"
                                                data-company_id="{{ $u->company_id }}"
                                                data-action="{{ route('manageadmin.update', $u->id) }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <form action="{{ route('manageadmin.destroy', $u->id) }}" method="POST" class="d-inline manageadmin-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Belum ada admin perusahaan</td>
                                        <td></td>
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
        $('#table-admins').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'asc']]
        });
        document.getElementById('modal-add-admin')?.addEventListener('show.bs.modal', function(){
            const form = this.querySelector('form');
            form.reset();
        });

        $('#table-admins').on('click', '.btn-edit-admin', function(){
            const id = $(this).data('id');
            const name = $(this).data('name');
            const email = $(this).data('email');
            const companyId = $(this).data('company_id');
            const action = $(this).data('action');

            const form = $('#modal-edit-admin form');
            form.attr('action', action);
            if (!form.find('input[name=_method]').length) {
                form.prepend('<input type="hidden" name="_method" value="PUT">');
            }
            $('#modal-edit-admin input#edit-name').val(name);
            $('#modal-edit-admin input#edit-email').val(email);
            $('#modal-edit-admin select#edit-company_id').val(companyId);
            $('#modal-edit-admin input#edit-password').val('');

            const m = new bootstrap.Modal(document.getElementById('modal-edit-admin'));
            m.show();
        });

        $('#table-admins').on('submit', '.manageadmin-delete-form', function(e){
            e.preventDefault();
            const form = this;
            Swal.fire({
                icon: 'warning',
                title: 'Hapus admin ini?',
                text: 'Tindakan tidak dapat dibatalkan',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        $('#table-admins').on('change', '.toggle-active', function(){
            const $box = $(this);
            const url = $box.data('action');
            const willActive = $box.is(':checked');
            const prev = !willActive;
            Swal.fire({
                icon: 'question',
                title: willActive ? 'Aktifkan admin?' : 'Nonaktifkan admin?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) { $box.prop('checked', prev); return; }
                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: { _token: $('meta[name="csrf-token"]').attr('content'), is_active: willActive ? 1 : 0 },
                    success: function(){
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Status diperbarui' });
                        const label = $box.closest('td').find('span');
                        label.text(willActive ? 'Aktif' : 'Tidak Aktif');
                    },
                    error: function(){
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memperbarui status' });
                        $box.prop('checked', prev);
                    }
                });
            });
        });
    });
</script>
@endpush

<div class="modal fade" id="modal-add-admin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Tambah Admin Perusahaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ url('/manage-admin-perusahaan') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="modal-company_id" class="form-label">Perusahaan</label>
            <select name="company_id" id="modal-company_id" class="form-select" required>
              <option value="" disabled selected>Pilih Perusahaan</option>
              @foreach($perusahaans as $p)
                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="modal-name" class="form-label">Nama Admin</label>
            <input type="text" name="name" id="modal-name" class="form-control" placeholder="Nama lengkap" required>
          </div>
          <div class="mb-3">
            <label for="modal-email" class="form-label">Email</label>
            <input type="email" name="email" id="modal-email" class="form-control" placeholder="email@perusahaan.com" required>
          </div>
          <div class="mb-3">
            <label for="modal-password" class="form-label">Password Awal</label>
            <input type="password" name="password" id="modal-password" class="form-control" placeholder="Minimal 8 karakter" minlength="8" required>
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Daftarkan Admin</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-edit-admin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Edit Admin Perusahaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST">
          @csrf
          <div class="mb-3">
            <label for="edit-company_id" class="form-label">Perusahaan</label>
            <select name="company_id" id="edit-company_id" class="form-select">
              @foreach($perusahaans as $p)
                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="edit-name" class="form-label">Nama Admin</label>
            <input type="text" name="name" id="edit-name" class="form-control" placeholder="Nama lengkap">
          </div>
          <div class="mb-3">
            <label for="edit-email" class="form-label">Email</label>
            <input type="email" name="email" id="edit-email" class="form-control" placeholder="email@perusahaan.com">
          </div>
          <div class="mb-3">
            <label for="edit-password" class="form-label">Password (opsional)</label>
            <input type="password" name="password" id="edit-password" class="form-control" placeholder="Kosongkan jika tidak mengganti" minlength="8">
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
