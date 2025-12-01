@extends('layouts.master')

@section('content')
    <div class="page-heading mb-3">
        <h3>Pipeline</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="row g-3">

                @php
                    $user = auth()->user();
                    $canCreatePipeline = $user->can('create pipelines');
                    $canUpdatePipeline = $user->can('update pipelines');
                    $canDeletePipeline = $user->can('delete pipelines');
                    $isLeadOperations = $user->hasRole(['lead-operations']);
                    $showActionColumn = ($canUpdatePipeline || $canDeletePipeline) && !$isLeadOperations;
                @endphp

                {{-- Kolom kiri (5/12 di layar besar) - hanya kalau boleh CREATE --}}
                @if ($canCreatePipeline && !$isLeadOperations)
                    <div class="col-12 col-lg-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Buat Pipeline Stage</h5>

                                <form action="{{ route('pipeline-stages.store') }}" method="POST">
                                    @csrf

                                    {{-- Nama Stage --}}
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Stage
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tipe Stage --}}
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Tipe Stage</label>
                                        <select id="type" name="type"
                                            class="form-select @error('type') is-invalid @enderror">
                                            <option value="">Pilih tipe (opsional)</option>
                                            <option value="lead" {{ old('type') === 'lead' ? 'selected' : '' }}>Lead
                                            </option>
                                            <option value="prospect" {{ old('type') === 'prospect' ? 'selected' : '' }}>
                                                Prospect</option>
                                            <option value="negotiation"
                                                {{ old('type') === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                            <option value="won" {{ old('type') === 'won' ? 'selected' : '' }}>Won
                                            </option>
                                            <option value="lost" {{ old('type') === 'lost' ? 'selected' : '' }}>Lost
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Urutan --}}
                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label">Urutan</label>
                                        <input type="number" id="sort_order" name="sort_order"
                                            class="form-control @error('sort_order') is-invalid @enderror"
                                            value="{{ old('sort_order') }}" min="0">
                                        <small class="text-muted">Semakin kecil angka, semakin atas posisinya.</small>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus-circle me-1"></i> Simpan Stage
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Kolom kanan (7/12 di layar besar) --}}
                <div class="col-12 {{ $canCreatePipeline && !$isLeadOperations ? 'col-lg-7' : 'col-lg-12' }}">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Daftar Pipeline</h5>

                            @if ($stages->isEmpty())
                                <div class="alert alert-info mb-0">
                                    Belum ada data pipeline stage. @if ($canCreatePipeline && !$isLeadOperations)
                                        Silakan tambahkan stage di form sebelah kiri.
                                    @endif
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-pipeline-stages">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Nama Stage</th>
                                                <th>Tipe</th>
                                                <th>Urutan</th>
                                                <th>Default</th>
                                                <th>Perusahaan</th>
                                                @if ($showActionColumn)
                                                    <th>Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stages as $stage)
                                                <tr data-id="{{ $stage->id }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $stage->name }}</td>
                                                    <td>{{ $stage->type ?? '-' }}</td>

                                                    {{-- Urutan: editable hanya jika boleh UPDATE dan bukan marketing/cs --}}
                                                    <td style="width: 100px;">
                                                        @if ($canUpdatePipeline && !$isLeadOperations)
                                                            <input type="number"
                                                                class="form-control form-control-sm sort-input"
                                                                name="sort_order" value="{{ $stage->sort_order ?? 0 }}"
                                                                min="0">
                                                        @else
                                                            {{ $stage->sort_order ?? '-' }}
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($stage->is_default)
                                                            <span class="badge bg-success">Ya</span>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ optional($stage->company)->name ?? '-' }}</td>

                                                    @if ($showActionColumn)
                                                        <td class="text-nowrap">
                                                            @if ($canUpdatePipeline)
                                                                <button type="button"
                                                                    class="btn btn-sm btn-primary btn-save-sort">
                                                                    <i class="bi bi-save"></i>
                                                                </button>
                                                            @endif

                                                            @if ($canDeletePipeline)
                                                                <form
                                                                    action="{{ route('pipeline-stages.destroy', $stage->id) }}"
                                                                    method="POST" class="d-inline pipeline-delete-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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
            // DataTables tetap jalan untuk semua yang bisa read pipelines
            $('#table-pipeline-stages').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ]
            });

            @if ($showActionColumn)
                const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').first()
                    .val();

                // Click handler untuk tombol Simpan di setiap baris
                $('#table-pipeline-stages').on('click', '.btn-save-sort', function() {
                    const $row = $(this).closest('tr');
                    const id = $row.data('id');
                    const sort = $row.find('.sort-input').val();

                    Swal.fire({
                        icon: 'question',
                        title: 'Simpan perubahan urutan?',
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        $.ajax({
                            url: '{{ url('/pipeline-stages') }}/' + id,
                            method: 'PUT',
                            data: {
                                _token: csrfToken,
                                sort_order: sort
                            },
                            success: function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Urutan diperbarui'
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Tidak dapat menyimpan perubahan'
                                });
                            }
                        });
                    });
                });

                // Konfirmasi delete
                $('#table-pipeline-stages').on('submit', '.pipeline-delete-form', function(e) {
                    e.preventDefault();
                    const form = this;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Hapus stage?',
                        text: 'Tindakan tidak dapat dibatalkan',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            @endif
        });
    </script>
@endpush
