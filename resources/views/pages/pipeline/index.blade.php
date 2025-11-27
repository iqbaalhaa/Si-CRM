@extends('layouts.master')

@section('content')
    <div class="page-heading mb-3">
        <h3>Pipeline</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="row g-3">
                {{-- Kolom kiri (5/12 di layar besar) --}}
                <div class="col-12 col-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Buat Pipeline Stage</h5>

                            <form action="{{ route('pipeline-stages.store') }}" method="POST">
                                @csrf

                                {{-- Nama Stage --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Stage<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        required>
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
                                        <option value="lead" {{ old('type') === 'lead' ? 'selected' : '' }}>Lead</option>
                                        <option value="prospect" {{ old('type') === 'prospect' ? 'selected' : '' }}>Prospect
                                        </option>
                                        <option value="negotiation" {{ old('type') === 'negotiation' ? 'selected' : '' }}>
                                            Negotiation</option>
                                        <option value="won" {{ old('type') === 'won' ? 'selected' : '' }}>Won</option>
                                        <option value="lost" {{ old('type') === 'lost' ? 'selected' : '' }}>Lost</option>
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

                                {{-- Default --}}
                                {{-- <div class="mb-3 form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_default"
                                        name="is_default" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        Jadikan sebagai stage default
                                    </label>
                                    @error('is_default')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Simpan Stage
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- Kolom kanan (7/12 di layar besar) --}}
                <div class="col-12 col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Daftar Pipeline</h5>

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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stages as $stage)
                                            <tr data-id="{{ $stage->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $stage->name }}</td>
                                                <td>{{ $stage->type ?? '-' }}</td>

                                                {{-- Kolom Urutan bisa diedit --}}
                                                <td style="width: 100px;">
                                                    <input type="number" class="form-control form-control-sm sort-input"
                                                        name="sort_order" value="{{ $stage->sort_order ?? 0 }}"
                                                        min="0">
                                                </td>

                                                <td>
                                                    @if ($stage->is_default)
                                                        <span class="badge bg-success">Ya</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak</span>
                                                    @endif
                                                </td>
                                                <td>{{ optional($stage->company)->name ?? '-' }}</td>
                                                <td class="text-nowrap">
                                                    {{-- Tombol SIMPAN (inline) --}}
                                                    <button type="button" class="btn btn-sm btn-primary btn-save-sort">
                                                        <i class="bi bi-save"></i>
                                                    </button>

                                                    {{-- Delete tetap sama --}}
                                                    <form action="{{ route('pipeline-stages.destroy', $stage->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Hapus stage ini?');">
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
                                                <td colspan="7" class="text-center">Belum ada data pipeline stage</td>
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
            // DataTables
            $('#table-pipeline-stages').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ]
            });

            // Ambil CSRF dari meta (pastikan di <head> layout sudah ada)
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Click handler untuk tombol Simpan di setiap baris
            $('#table-pipeline-stages').on('click', '.btn-save-sort', function() {
                const $row = $(this).closest('tr');
                const id = $row.data('id');
                const sort = $row.find('.sort-input').val();

                $.ajax({
                    url: '{{ url('/pipeline-stages') }}/' + id,
                    method: 'PUT',
                    data: {
                        _token: csrfToken,
                        sort_order: sort
                    },
                    success: function(res) {
                        // Opsional: kasih feedback kecil
                        toastr?.success?.('Urutan berhasil diperbarui'); // kalau pakai Toastr
                        // atau:
                        // alert('Urutan berhasil diperbarui');
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('Gagal mengupdate urutan. Coba lagi.');
                    }
                });
            });
        });
    </script>
@endpush
