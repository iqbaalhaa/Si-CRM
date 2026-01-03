@extends('layouts.master')

@section('title', 'Produk')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Produk</h3>
            <p class="text-muted mb-0">Kelola daftar produk, status aktif, dan detail produk untuk setiap perusahaan.</p>
        </div>
    </div>

    @php
        $totalProducts = $products->count();
        $activeProducts = $products->where('is_active', true)->count();
        $inactiveProducts = $products->where('is_active', false)->count();
    @endphp

    <div class="page-content">
        {{-- Ringkasan singkat --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Total Produk</small>
                            <h4 class="mb-0">{{ $totalProducts }}</h4>
                        </div>
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Aktif</small>
                            <h4 class="mb-0 text-success">{{ $activeProducts }}</h4>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            <i class="bi bi-check-circle me-1"></i> Aktif
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Tidak Aktif</small>
                            <h4 class="mb-0 text-danger">{{ $inactiveProducts }}</h4>
                        </div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                            <i class="bi bi-x-circle me-1"></i> Tidak Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card utama: toolbar + table + mass update --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Toolbar atas --}}
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
                            <div>
                                <h5 class="mb-0">Daftar Produk</h5>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <div class="btn-group btn-group-sm me-2" role="group" aria-label="Filter produk">
                                    <button type="button" class="btn btn-outline-primary active filter-products"
                                        data-filter="all">Semua</button>
                                    <button type="button" class="btn btn-outline-success filter-products"
                                        data-filter="active">Aktif</button>
                                    <button type="button" class="btn btn-outline-secondary filter-products"
                                        data-filter="inactive">Tidak Aktif</button>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-upload me-1"></i> Impor / Ekspor
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 260px;">
                                        <strong class="d-block mb-2" style="font-size: 0.85rem;">Ekspor</strong>

                                        {{-- Export XLSX --}}
                                        <a href="{{ route('products.export.xlsx') }}"
                                            class="btn btn-sm btn-outline-secondary w-100 mb-3">
                                            Ekspor XLSX
                                        </a>

                                        <hr class="my-2">

                                        <strong class="d-block mb-2" style="font-size: 0.85rem;">Impor</strong>

                                        <div class="mb-2">
                                            <a href="{{ route('products.template.xlsx') }}" class="btn btn-sm btn-outline-secondary w-100">
                                                Unduh Template XLSX
                                            </a>
                                        </div>

                                        {{-- Import XLSX --}}
                                        <form method="POST" action="{{ route('products.import.xlsx') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-1" style="font-size: 0.8rem;">
                                                <label class="form-label mb-1">XLSX</label>
                                                <input type="file" name="file" class="form-control form-control-sm"
                                                    accept=".xlsx" required>
                                            </div>
                                            <button class="btn btn-sm btn-primary w-100" type="submit">
                                                Impor XLSX
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#createProductModal">
                                    <i class="bi bi-plus-circle"></i>
                                    <span>Tambah Produk</span>
                                </button>
                            </div>
                        </div>

                        {{-- Form Aksi Massal (tidak membungkus tabel untuk menghindari nested form) --}}
                        <form id="massUpdateForm" method="POST" action="{{ route('products.mass-update') }}">
                            @csrf
                            <input type="hidden" name="is_active" id="mass_is_active">

                            {{-- Toolbar mass action --}}
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <button type="button" id="btn-select-toggle" class="btn btn-sm btn-outline-secondary">
                                        Mode Pilih
                                    </button>
                                    <button type="button" id="btn-bulk-activate" class="btn btn-sm btn-success" disabled>
                                        Aktifkan
                                    </button>
                                    <button type="button" id="btn-bulk-deactivate" class="btn btn-sm btn-warning" disabled>
                                        Nonaktifkan
                                    </button>
                                    <button type="button" id="btn-bulk-export" class="btn btn-sm btn-outline-primary" disabled>
                                        Ekspor XLSX
                                    </button>
                                    <button type="button" id="btn-bulk-delete" class="btn btn-sm btn-outline-danger" disabled>
                                        Hapus
                                    </button>
                                </div>

                            </div>
                            @if ($products->isEmpty())
                                <div class="alert alert-info">
                                    Belum ada produk. Tambahkan terlebih dahulu.
                                </div>
                            @endif
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-products">
                                <thead>
                                    <tr class="text-center">
                                        <th class="select-col" style="width: 30px;">
                                            <input type="checkbox" id="check-all">
                                        </th>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Detail Produk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $index => $product)
                                        @php
                                            $details = $product->relationLoaded('details')
                                                ? $product->details
                                                : $product->details;
                                            $detailPreview = $details->take(3);
                                        @endphp
                                        <tr data-active="{{ $product->is_active ? 1 : 0 }}">
                                            <td class="text-center select-col">
                                                <input type="checkbox" value="{{ $product->id }}" class="row-check">
                                            </td>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>
                                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if ($product->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($details->isEmpty())
                                                    <span class="text-muted">-</span>
                                                @else
                                                    <div class="small">
                                                        @foreach ($detailPreview as $d)
                                                            <div>
                                                                <strong>{{ $d->label }}:</strong>
                                                                {{ \Illuminate\Support\Str::limit($d->value, 40) }}
                                                            </div>
                                                        @endforeach
                                                        @if ($details->count() > 3)
                                                            <span class="text-muted">
                                                                +{{ $details->count() - 3 }} detail lain
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-nowrap text-center">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-info btn-show-product"
                                                    data-bs-toggle="modal" data-bs-target="#showProductModal"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    data-base_price="{{ $product->base_price }}"
                                                    data-description="{{ $product->description }}"
                                                    data-photo_path="{{ $product->photo_path }}"
                                                    data-is_active="{{ $product->is_active ? 1 : 0 }}"
                                                    data-created_at="{{ optional($product->created_at)->format('d M Y H:i') }}"
                                                    data-updated_at="{{ optional($product->updated_at)->format('d M Y H:i') }}"
                                                    data-details="{{ json_encode($product->details->toArray()) }}"
                                                    title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary btn-edit-product"
                                                    data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    data-base_price="{{ $product->base_price }}"
                                                    data-description="{{ $product->description }}"
                                                    data-photo_path="{{ $product->photo_path }}"
                                                    data-is_active="{{ $product->is_active ? 1 : 0 }}"
                                                    data-details="{{ json_encode($product->details->toArray()) }}"
                                                    title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <form action="{{ route('products.destroy', $product) }}"
                                                    method="POST" class="d-inline product-delete-form"
                                                    data-product-name="{{ $product->name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Pindahkan ke Sampah">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create Product --}}
    @include('pages.products.create')
    {{-- Modal Edit Product --}}
    @include('pages.products.edit')
    {{-- Modal Show Product --}}
    @include('pages.products.show')
@endsection

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('admindash/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.css') }}">
    <style>
        .table-wrapper {
            background-color: transparent;
            border-radius: 1rem;
            padding: .75rem 1rem;
            box-shadow: 0 .75rem 1.5rem rgba(15, 23, 42, .06);
            border: 1px solid rgba(148, 163, 184, .3);
        }
        #table-products .select-col { display: none; }
        #table-products.select-mode .select-col { display: table-cell; }
        #table-products.select-mode .table-actions button,
        #table-products.select-mode .product-delete-form button,
        #table-products.select-mode .btn-edit-product,
        #table-products.select-mode .btn-show-product {
            pointer-events: none;
            opacity: .5;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(function() {
            // DataTable
            $('#table-products').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [1, 'asc']
                ]
            });

            // Check all
            $('#check-all').on('change', function() {
                $('.row-check').prop('checked', this.checked);
                updateBulkButtons();
            });

            // Filter buttons (All / Active / Inactive)
            $('.filter-products').on('click', function() {
                $('.filter-products').removeClass('active');
                $(this).addClass('active');
                const filter = $(this).data('filter');
                $('#table-products tbody tr').each(function() {
                    const active = $(this).data('active') ? 'active' : 'inactive';
                    if (filter === 'all' || filter === active) $(this).show();
                    else $(this).hide();
                });
            });

            function setSelectMode(on) {
                const $table = $('#table-products');
                if (on) {
                    $table.addClass('select-mode');
                } else {
                    $table.removeClass('select-mode');
                    $('#check-all').prop('checked', false).trigger('change');
                    $('.row-check').prop('checked', false);
                }
                updateBulkButtons();
            }

            function updateBulkButtons() {
                const count = $('.row-check:checked').length;
                const enabled = count > 0 && $('#table-products').hasClass('select-mode');
                $('#btn-bulk-activate').prop('disabled', !enabled);
                $('#btn-bulk-deactivate').prop('disabled', !enabled);
                $('#btn-bulk-export').prop('disabled', !enabled);
                $('#btn-bulk-delete').prop('disabled', !enabled);
            }

            $(document).on('change', '.row-check', updateBulkButtons);

            $('#btn-select-toggle').on('click', function() {
                const on = !$('#table-products').hasClass('select-mode');
                setSelectMode(on);
                $(this).toggleClass('btn-outline-secondary btn-secondary');
                $(this).text(on ? 'Selesai Pilih' : 'Mode Pilih');
            });

            function submitBulk(url, extra) {
                const checked = $('.row-check:checked');
                if (checked.length === 0) return;
                $('#massUpdateForm .mass-ids').remove();
                checked.each(function() {
                    const id = $(this).val();
                    $('#massUpdateForm').append(
                        $('<input>', { type: 'hidden', name: 'ids[]', value: id, class: 'mass-ids' })
                    );
                });
                if (extra && typeof extra === 'function') extra();
                $('#massUpdateForm').attr('action', url).submit();
            }

            $('#btn-bulk-activate').on('click', function() {
                if (!confirm('Terapkan status Aktif ke produk terpilih?')) return;
                $('#mass_is_active').val(1);
                submitBulk('{{ route('products.mass-update') }}');
            });

            $('#btn-bulk-deactivate').on('click', function() {
                if (!confirm('Terapkan status Tidak Aktif ke produk terpilih?')) return;
                $('#mass_is_active').val(0);
                submitBulk('{{ route('products.mass-update') }}');
            });

            $('#btn-bulk-export').on('click', function() {
                submitBulk('{{ route('products.export.selected.xlsx') }}');
            });

            $('#btn-bulk-delete').on('click', function() {
                if (!confirm('Hapus produk terpilih?')) return;
                submitBulk('{{ route('products.mass-delete') }}');
            });

            // Confirm soft delete
            $('#table-products').on('submit', '.product-delete-form', function(e) {
                e.preventDefault();
                const form = this;
                const name = form.getAttribute('data-product-name') || 'produk';
                if (window.Swal && typeof Swal.fire === 'function') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Apakah Anda yakin?',
                        text: `Produk "${name}" akan dihapus.`,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#d33'
                    }).then((r) => {
                        if (r.isConfirmed) form.submit();
                    });
                } else {
                    if (confirm(`Pindahkan produk "${name}" ke sampah?`)) form.submit();
                }
            });
        });
    </script>
@endpush
