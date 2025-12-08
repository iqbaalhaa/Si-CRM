@extends('layouts.master')

@section('title', 'Product')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Product</h3>
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
                            <i class="bi bi-check-circle me-1"></i> Active
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
                            <i class="bi bi-x-circle me-1"></i> Inactive
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
                                <small class="text-muted">
                                    Centang beberapa produk untuk melakukan update status aktif secara massal,
                                    atau gunakan menu import / export.
                                </small>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                {{-- Import / Export dropdown --}}
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-upload me-1"></i> Import / Export
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 260px;">
                                        <strong class="d-block mb-2" style="font-size: 0.85rem;">Export</strong>

                                        {{-- Export CSV dengan delimiter --}}
                                        <form method="GET" action="{{ route('products.export.csv') }}"
                                            class="d-flex gap-2 align-items-center mb-2">
                                            <input type="hidden" name="delimiter" id="csv-delimiter-input" value=";">
                                            <select class="form-select form-select-sm" id="csv-delimiter-select"
                                                style="max-width: 90px;">
                                                <option value=";">;</option>
                                                <option value=",">,</option>
                                            </select>
                                            <button class="btn btn-sm btn-outline-secondary flex-grow-1" type="submit">
                                                CSV
                                            </button>
                                        </form>

                                        {{-- Export XLSX --}}
                                        <a href="{{ route('products.export.xlsx') }}"
                                            class="btn btn-sm btn-outline-secondary w-100 mb-3">
                                            XLSX
                                        </a>

                                        <hr class="my-2">

                                        <strong class="d-block mb-2" style="font-size: 0.85rem;">Import</strong>

                                        {{-- Import CSV --}}
                                        <form method="POST" action="{{ route('products.import.csv') }}"
                                            enctype="multipart/form-data" class="mb-2">
                                            @csrf
                                            <div class="mb-1" style="font-size: 0.8rem;">
                                                <label class="form-label mb-1">CSV (name, base_price, ...)</label>
                                                <input type="file" name="file" class="form-control form-control-sm"
                                                    accept=".csv,.txt" required>
                                            </div>
                                            <div class="mb-2">
                                                <select name="delimiter" class="form-select form-select-sm">
                                                    <option value=";">Delimiter ;</option>
                                                    <option value=",">Delimiter ,</option>
                                                </select>
                                            </div>
                                            <button class="btn btn-sm btn-primary w-100" type="submit">
                                                Import CSV
                                            </button>
                                        </form>

                                        {{-- Import XLSX --}}
                                        <form method="POST" action="{{ route('products.import.xlsx') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-1" style="font-size: 0.8rem;">
                                                <label class="form-label mb-1">XLSX</label>
                                                <input type="file" name="file" class="form-control form-control-sm"
                                                    accept=".xlsx,.xls,.csv" required>
                                            </div>
                                            <button class="btn btn-sm btn-primary w-100" type="submit">
                                                Import XLSX
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

                        {{-- Form Mass Update (wrap table) --}}
                        <form id="massUpdateForm" method="POST" action="{{ route('products.mass-update') }}">
                            @csrf
                            <input type="hidden" name="is_active" id="mass_is_active">
                            <input type="hidden" name="base_price" id="mass_base_price">

                            {{-- Toolbar mass action --}}
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <select id="bulk-action" class="form-select form-select-sm"
                                        style="min-width: 180px;">
                                        <option value="">Aksi Massal</option>
                                        <option value="activate">Aktifkan</option>
                                        <option value="deactivate">Nonaktifkan</option>
                                    </select>
                                    <button type="button" id="btn-apply-bulk" class="btn btn-sm btn-outline-primary">
                                        Terapkan
                                    </button>
                                </div>

                                <small class="text-muted">
                                    Centang produk yang ingin diubah, lalu pilih aksi massal.
                                </small>
                            </div>
                            @if ($products->isEmpty())
                                <div class="alert alert-info">
                                    Belum ada produk. Tambahkan terlebih dahulu.
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped align-middle" id="table-products">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 30px;">
                                                <input type="checkbox" id="check-all">
                                            </th>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Detail Produk</th>
                                            <th>Dibuat</th>
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
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="ids[]" value="{{ $product->id }}"
                                                        class="row-check">
                                                </td>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if ($product->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
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
                                                <td>
                                                    {{ optional($product->created_at)->format('d M Y H:i') }}
                                                </td>
                                                <td class="text-nowrap text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-secondary btn-edit-product"
                                                        data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                        data-base_price="{{ $product->base_price }}"
                                                        data-description="{{ $product->description }}"
                                                        data-photo_path="{{ $product->photo_path }}"
                                                        data-is_active="{{ $product->is_active ? 1 : 0 }}"
                                                        data-details="{{ e($product->details->toJson()) }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="{{ route('products.destroy', $product) }}"
                                                        method="POST" class="d-inline product-delete-form"
                                                        data-product-name="{{ $product->name }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-warning"
                                                            title="Hapus Produk Ini?">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create Product --}}
    @include('pages.products.create')
    {{-- Modal Edit Product --}}
    @include('pages.products.edit')
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
            // DataTable
            $('#table-products').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [1, 'asc']
                ]
            });

            // Sinkron delimiter CSV
            $('#csv-delimiter-select').on('change', function() {
                $('#csv-delimiter-input').val($(this).val());
            });

            // Check all
            $('#check-all').on('change', function() {
                $('.row-check').prop('checked', this.checked);
            });

            $('.row-check').on('change', function() {
                if (!this.checked) {
                    $('#check-all').prop('checked', false);
                }
            });

            // Apply bulk action
            $('#btn-apply-bulk').on('click', function() {
                const action = $('#bulk-action').val();
                const checked = $('.row-check:checked');

                if (!action) {
                    alert('Pilih aksi massal terlebih dahulu.');
                    return;
                }

                if (checked.length === 0) {
                    alert('Pilih minimal satu produk.');
                    return;
                }

                // Set payload mass update
                $('#mass_is_active').val('');
                $('#mass_base_price').val('');

                if (action === 'activate') {
                    $('#mass_is_active').val(1);
                } else if (action === 'deactivate') {
                    $('#mass_is_active').val(0);
                }

                // Kalau mau extend: set_base_price, dsb.

                // Konfirmasi
                if (window.Swal) {
                    Swal.fire({
                        icon: 'question',
                        title: 'Terapkan aksi massal?',
                        text: 'Aksi ini akan mengubah beberapa produk sekaligus.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#massUpdateForm').submit();
                        }
                    });
                } else {
                    if (confirm('Terapkan aksi massal ke produk terpilih?')) {
                        $('#massUpdateForm').submit();
                    }
                }
            });

            // Konfirmasi delete single product (soft delete)
            $('#table-products').on('submit', '.product-delete-form', function(e) {
                e.preventDefault();
                const form = this;
                const productName = $(form).data('product-name') || 'produk ini';

                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pindahkan produk ke Sampah?',
                        text: `Produk "${productName}" akan dipindahkan ke sampah dan dapat dipulihkan nanti.`,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, pindahkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#f59e0b' // amber / warning
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                } else {
                    if (confirm(`Pindahkan ${productName} ke sampah?`)) {
                        form.submit();
                    }
                }
            });

            // Edit product button click
            $('.btn-edit-product').on('click', function() {
                const button = $(this);
                const modal = $('#editProductModal');

                // Get data attributes from button
                const id = button.data('id');
                const name = button.data('name');
                const base_price = button.data('base_price');
                const description = button.data('description');
                const photo_path = button.data('photo_path');
                const is_active = button.data('is_active');
                const details = button.data('details');

                // Set form action to update route
                const form = modal.find('form');
                form.attr('action', '{{ url('products') }}/' + id);

                // Fill in the form fields
                modal.find('input[name="name"]').val(name);
                modal.find('input[name="base_price"]').val(base_price);
                modal.find('textarea[name="description"]').val(description);
                modal.find('input[name="photo_path"]').val(photo_path);
                modal.find('select[name="is_active"]').val(is_active);

                // Clear existing detail rows
                const detailsContainer = modal.find('.detail-rows');
                detailsContainer.empty();

                // Add detail rows from product data
                if (details && details.length > 0) {
                    details.forEach((detail, index) => {
                        const row = `
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col">
                                    <input type="text" name="details[${index}][label]" class="form-control form-control-sm"
                                        placeholder="Label" value="${detail.label}" required>
                                </div>
                                <div class="col">
                                    <input type="text" name="details[${index}][value]" class="form-control form-control-sm"
                                        placeholder="Value" value="${detail.value}" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-sm btn-danger remove-detail-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        detailsContainer.append(row);
                    });
                }

                // Show the modal
                modal.modal('show');
            });

            // Remove detail row
            $(document).on('click', '.remove-detail-row', function() {
                $(this).closest('.row').remove();
            });
        });
    </script>
@endpush
