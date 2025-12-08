@extends('layouts.master')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <h3>Kontak</h3>
    </div>

    <div class="card shadow-sm border-0 rounded-3 mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Advance Search</h6>
                @if(method_exists($contacts, 'total') ? $contacts->total() : $contacts->count())
                    <div class="btn-group">
                        <a id="exportCsvBtn" href="{{ route('contacts.export', ['format' => 'csv']) }}" class="btn btn-outline-primary btn-sm" data-format="csv">Export CSV</a>
                        <a id="exportXlsxBtn" href="{{ route('contacts.export', ['format' => 'xlsx']) }}" class="btn btn-outline-success btn-sm" data-format="xlsx">Export XLSX</a>
                    </div>
                @endif
            </div>

            <form method="GET" action="{{ route('contacts.advanced') }}" class="row g-2" id="contactSearchForm">
                <div class="col-12 col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Contoh: umur:20 / umur>20 / umur<20, kerinci, sungai penuh" id="qInput">
                </div>
                <div class="col-6 col-md-3">
                    <select name="type" class="form-select" id="typeSelect">
                        <option value="">Semua Tipe</option>
                        <option value="individual" {{ request('type') === 'individual' ? 'selected' : '' }}>Individu</option>
                        <option value="company" {{ request('type') === 'company' ? 'selected' : '' }}>Perusahaan</option>
                        <option value="organization" {{ request('type') === 'organization' ? 'selected' : '' }}>Organisasi</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="only_active" value="1" id="onlyActive" {{ request('only_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="onlyActive">Hanya yang aktif</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-1">Daftar Kontak</h5>
                                <p class="text-muted small mb-0">Kelola dan hubungi kontak dengan cepat.</p>
                            </div>

                            {{-- ❗️ BAGIAN INI DITAMBAH: Tombol Tambah + Dropdown Import --}}
                            <div class="d-flex gap-2">
                                <button type="button"
                                        class="btn btn-success btn-sm d-flex align-items-center gap-1 import-trigger"
                                        title="Import kontak dari file">
                                    <i class="bi bi-upload"></i>
                                    <span>Import</span>
                                </button>
                                <a href="{{ route('contacts.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-plus-circle"></i>
                                    <span>Tambah Kontak</span>
                                </a>

                            </div>
                            {{-- ❗️ END BAGIAN BARU --}}

                        </div>

                        <div class="table-responsive table-wrapper">
                            <table class="table table-modern align-middle mb-0" id="table-contacts">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Informasi Kontak</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contacts as $c)
                                        @php
                                            $email = optional($c->channels->firstWhere('label', 'email'))->value;
                                            $phone = optional($c->channels->firstWhere('label', 'phone'))->value;
                                            $wa    = optional($c->channels->firstWhere('label', 'whatsapp'))->value;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="contact-main">
                                                    <span class="contact-name">{{ $c->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-type
                                                    @if($c->type === 'individual') badge-type-individual
                                                    @elseif($c->type === 'company') badge-type-company
                                                    @elseif($c->type === 'organization') badge-type-organization
                                                    @endif
                                                ">
                                                    {{ ucfirst($c->type) }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="contact-info-cell">
                                                    <div class="contact-chips">
                                                        @if($phone)
                                                            <a href="tel:{{ $phone }}" class="contact-chip" title="Telepon">
                                                                <i class="bi bi-telephone"></i>
                                                                <span>{{ $phone }}</span>
                                                            </a>
                                                        @endif

                                                        @if($email)
                                                            <a href="mailto:{{ $email }}" class="contact-chip" title="Kirim Email">
                                                                <i class="bi bi-envelope"></i>
                                                                <span>{{ $email }}</span>
                                                            </a>
                                                        @endif

                                                        @if($wa)
                                                            <a href="https://wa.me/{{ ltrim($wa, '+') }}" target="_blank" class="contact-chip" title="WhatsApp">
                                                                <i class="bi bi-whatsapp"></i>
                                                                <span>WhatsApp</span>
                                                            </a>
                                                        @endif
                                                    </div>

                                                    @if(!$phone && !$email && !$wa)
                                                        <span class="text-muted small">Belum ada informasi kontak utama.</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                @if($c->is_active)
                                                    <span class="badge bg-success rounded-pill px-3 py-1 small">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary rounded-pill px-3 py-1 small">Nonaktif</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="table-actions">
                                                    <a href="{{ route('contacts.show', $c->id) }}" class="btn btn-light btn-icon border" title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('contacts.edit', $c->id) }}" class="btn btn-light btn-icon border" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('contacts.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kontak ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-light btn-icon border" title="Hapus">
                                                            <i class="bi bi-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted py-4">Belum ada data kontak</td>
                                            <td></td><td></td><td></td><td></td><td></td>
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

    {{-- ❗️ MODAL IMPORT KONTAK --}}
    <div class="modal fade" id="importContactsModal" tabindex="-1" aria-labelledby="importContactsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header border-0">
                    <div>
                        <h5 class="modal-title" id="importContactsModalLabel">Import Kontak</h5>
                        <p class="text-muted small mb-0">
                            Langkah: 1) Pilih tipe kontak, 2) Download template, 3) Isi template, 4) Upload file di bawah.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data" id="importContactsForm">
                    @csrf
                    <input type="hidden" name="type" id="importType" value="individual">

                    <div class="modal-body pt-0">
                        <div class="mb-3">
                            <span class="text-uppercase text-muted small fw-semibold">Tipe Kontak</span>
                            <div class="row g-2 mt-1">
                                <div class="col-md-4">
                                    <div class="form-check border rounded-3 p-2 h-100">
                                        <input class="form-check-input import-type-radio" type="radio" name="type_radio" id="import_type_individual" value="individual" checked>
                                        <label class="form-check-label d-block" for="import_type_individual">
                                            <strong>Individu</strong>
                                            <small class="d-block text-muted">Customer perorangan</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check border rounded-3 p-2 h-100">
                                        <input class="form-check-input import-type-radio" type="radio" name="type_radio" id="import_type_company" value="company">
                                        <label class="form-check-label d-block" for="import_type_company">
                                            <strong>Perusahaan</strong>
                                            <small class="d-block text-muted">Badan usaha / client</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check border rounded-3 p-2 h-100">
                                        <input class="form-check-input import-type-radio" type="radio" name="type_radio" id="import_type_organization" value="organization">
                                        <label class="form-check-label d-block" for="import_type_organization">
                                            <strong>Organisasi</strong>
                                            <small class="d-block text-muted">Komunitas / yayasan</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3">
                            <span class="text-uppercase text-muted small fw-semibold">Download Template</span>
                            <p class="text-muted small mb-2">
                                Download template sesuai tipe kontak, isi datanya, lalu upload kembali menggunakan form di bawah.
                            </p>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <a href="{{ route('contacts.template', ['type' => 'individual']) }}" class="btn btn-outline-secondary w-100 btn-sm">
                                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Template Individu
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('contacts.template', ['type' => 'company']) }}" class="btn btn-outline-secondary w-100 btn-sm">
                                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Template Perusahaan
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('contacts.template', ['type' => 'organization']) }}" class="btn btn-outline-secondary w-100 btn-sm">
                                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Template Organisasi
                                    </a>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="mb-2">
                            <label class="form-label">Upload File (.xlsx)</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx" required>
                            <small class="text-muted d-block mt-1">
                                Pastikan format kolom mengikuti template yang sudah di-download.
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer border-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Import Kontak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admindash/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.css') }}">

    <style>
        .table-wrapper {
            background-color: transparent;
            border-radius: 1rem;
            padding: .75rem 1rem;
            box-shadow: 0 .75rem 1.5rem rgba(15, 23, 42, .06);
            border: 1px solid rgba(148, 163, 184, .3);
        }

        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        /* .table-modern thead {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #ffffff;
        } */

        .table-modern thead th {
            border: none;
            padding: .75rem .9rem;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
        }

        .table-modern tbody tr {
            transition: background .15s ease, transform .1s ease, box-shadow .15s ease;
        }

        .table-modern tbody td {
            border-top: 1px solid #e5e7eb;
            padding: .65rem .9rem;
            font-size: .875rem;
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background-color: var(--bs-tertiary-bg);
            transform: translateY(-1px);
            box-shadow: 0 .35rem .9rem rgba(15, 23, 42, .08);
        }

        .contact-name {
            font-weight: 600;
            color: var(--bs-body-color);
        }

        .badge-type {
            border-radius: 999px;
            font-size: .7rem;
            padding: .25rem .7rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .badge-type-individual {
            background: rgba(59, 130, 246, .08);
            color: #1d4ed8;
            border-color: rgba(59, 130, 246, .2);
        }

        .badge-type-company {
            background: rgba(16, 185, 129, .08);
            color: #047857;
            border-color: rgba(16, 185, 129, .2);
        }

        .badge-type-organization {
            background: rgba(234, 179, 8, .08);
            color: #92400e;
            border-color: rgba(234, 179, 8, .25);
        }

        .contact-info-cell {
            display: flex;
            flex-direction: column;
            gap: .15rem;
        }

        .contact-chips {
            display: flex;
            flex-wrap: wrap;
            gap: .3rem;
        }

        .contact-chip {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            padding: .2rem .5rem;
            border-radius: 999px;
            font-size: .75rem;
            text-decoration: none;
            border: 1px solid var(--bs-border-color);
            color: var(--bs-body-color);
            background-color: transparent;
            transition: all .15s ease;
        }

        .contact-chip i {
            font-size: .9rem;
        }

        .contact-chip:hover {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            text-decoration: none;
        }

        .table-actions {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
        }

        .table-actions .btn-icon {
            width: 2.1rem;
            height: 2.1rem;
            min-width: 2.1rem;
            min-height: 2.1rem;
            border-radius: 999px;
            display: inline-grid;
            place-items: center;
            padding: 0 !important;
            background-color: transparent !important;
            color: var(--bs-body-color) !important;
            border-color: var(--bs-border-color) !important;
            --bs-btn-bg: transparent;
            --bs-btn-hover-bg: transparent;
            --bs-btn-active-bg: transparent;
            --bs-btn-border-color: var(--bs-border-color);
            --bs-btn-hover-border-color: var(--bs-border-color);
            --bs-btn-active-border-color: var(--bs-border-color);
            --bs-btn-box-shadow: none;
            --bs-btn-line-height: 1;
        }

        .table-actions .btn-icon:hover,
        .table-actions .btn-icon:focus,
        .table-actions .btn-icon:active {
            background-color: transparent !important;
            color: var(--bs-body-color) !important;
            border-color: var(--bs-border-color) !important;
            box-shadow: none !important;
        }

        .table-actions .btn-icon i {
            display: inline-block;
            font-size: 1rem;
            line-height: 1;
        }

        .dataTables_wrapper .pagination .page-link {
            color: var(--bs-body-color) !important;
            background-color: transparent !important;
            border-color: var(--bs-border-color) !important;
        }
        .dataTables_wrapper .pagination .page-link:hover,
        .dataTables_wrapper .pagination .page-link:focus {
            color: var(--bs-body-color) !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }

        @media (max-width: 768px) {
            .table-wrapper {
                padding: .5rem .75rem;
            }

            .table-modern thead th {
                font-size: .7rem;
            }

            .table-modern tbody td {
                font-size: .8rem;
            }

            .contact-chip {
                font-size: .7rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(function() {
            const table = $('#table-contacts').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [[0, 'asc']],
                dom: 'lrtip'
            });

            const baseUrl = '{{ route('contacts.advanced') }}';
            const $q = $('#qInput');
            const $type = $('#typeSelect');
            const $onlyActive = $('#onlyActive');

            function updateExportHref() {
                const params = {};
                const qVal = ($q.val() || '').trim();
                if (qVal) params.q = qVal;
                const tVal = ($type.val() || '').trim();
                if (tVal) params.type = tVal;
                if ($onlyActive.is(':checked')) params.only_active = 1;
                const $csv = $('#exportCsvBtn');
                const $xlsx = $('#exportXlsxBtn');
                if ($csv.length) {
                    const baseCsv = ($csv.data('base') || $csv.attr('href').split('?')[0]);
                    const fmtCsv = ($csv.data('format') || 'csv');
                    const hrefCsv = baseCsv + '?' + $.param(Object.assign({ format: fmtCsv }, params));
                    $csv.attr('href', hrefCsv);
                    $csv.data('base', baseCsv);
                }
                if ($xlsx.length) {
                    const baseXlsx = ($xlsx.data('base') || $xlsx.attr('href').split('?')[0]);
                    const fmtXlsx = ($xlsx.data('format') || 'xlsx');
                    const hrefXlsx = baseXlsx + '?' + $.param(Object.assign({ format: fmtXlsx }, params));
                    $xlsx.attr('href', hrefXlsx);
                    $xlsx.data('base', baseXlsx);
                }
            }

            function buildRow(item, idx) {
                const emailChip = item.email ? `<a href="mailto:${item.email}" class="contact-chip" title="Kirim Email"><i class="bi bi-envelope"></i><span>${item.email}</span></a>` : '';
                const phoneChip = item.phone ? `<a href="tel:${item.phone}" class="contact-chip" title="Telepon"><i class="bi bi-telephone"></i><span>${item.phone}</span></a>` : '';
                const waChip = item.whatsapp ? `<a href="https://wa.me/${String(item.whatsapp).replace('+','')}" target="_blank" class="contact-chip" title="WhatsApp"><i class="bi bi-whatsapp"></i><span>WhatsApp</span></a>` : '';
                const status = item.is_active ? '<span class="badge bg-success rounded-pill px-3 py-1 small">Aktif</span>' : '<span class="badge bg-secondary rounded-pill px-3 py-1 small">Nonaktif</span>';
                const nameHtml = `<div class="contact-main"><span class="contact-name">${item.name}</span></div>`;
                const typeHtml = `<span class="badge-type">${(item.type || '').charAt(0).toUpperCase() + (item.type || '').slice(1)}</span>`;
                const infoHtml = `<div class="contact-info-cell"><div class="contact-chips">${phoneChip}${emailChip}${waChip}</div>${(!item.phone && !item.email && !item.whatsapp) ? '<span class="text-muted small">Belum ada informasi kontak utama.</span>' : ''}</div>`;
                const actionsHtml = `<div class="table-actions"><a href="${window.location.origin}/contacts/${item.id}" class="btn btn-light btn-icon border" title="Detail"><i class="bi bi-eye"></i></a><a href="${window.location.origin}/contacts/${item.id}/edit" class="btn btn-light btn-icon border" title="Edit"><i class="bi bi-pencil-square"></i></a></div>`;
                return [idx, nameHtml, typeHtml, infoHtml, status, actionsHtml];
            }

            function render(items) {
                table.clear();
                let i = 1;
                for (const item of items) table.row.add(buildRow(item, i++));
                if (!items.length) table.row.add(['', 'Belum ada data kontak', '', '', '', '']);
                table.draw(false);
            }

            function fetchContacts() {
                const params = {};
                const qVal = ($q.val() || '').trim();
                if (qVal) params.q = qVal;
                const tVal = ($type.val() || '').trim();
                if (tVal) params.type = tVal;
                if ($onlyActive.is(':checked')) params.only_active = 1;
                const qs = $.param(params);
                $.ajax({
                    url: baseUrl + (qs ? ('?' + qs) : ''),
                    headers: { 'Accept': 'application/json' },
                    success: function(d) {
                        render((d && d.items) ? d.items : []);
                        updateExportHref();
                    }
                });
            }

            let qTimer;
            $q.on('input', function() {
                clearTimeout(qTimer);
                qTimer = setTimeout(fetchContacts, 300);
            });
            $type.on('change', fetchContacts);
            $onlyActive.on('change', fetchContacts);

            updateExportHref();

            // ❗️ LOGIKA IMPORT: sinkron dropdown -> modal
            const typeLabelMap = {
                individual: 'Individu',
                company: 'Perusahaan',
                organization: 'Organisasi'
            };

            $('.import-trigger').on('click', function () {
                const type = $(this).data('type') || 'individual';
                $('#importType').val(type);

                // set radio di modal
                $('.import-type-radio').prop('checked', false);
                $(`.import-type-radio[value="${type}"]`).prop('checked', true);

                // buka modal
                const modalEl = document.getElementById('importContactsModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });

            // kalau user ganti tipe di radio, sinkron ke hidden input
            $(document).on('change', '.import-type-radio', function () {
                $('#importType').val($(this).val());
            });
        });
    </script>
@endpush
