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
                        <a id="exportCsvBtn" href="{{ route('contacts.export', ['format' => 'csv']) }}" class="btn btn-outline-primary btn-sm">Export CSV</a>
                        <a id="exportXlsxBtn" href="{{ route('contacts.export', ['format' => 'xlsx']) }}" class="btn btn-outline-success btn-sm">Export XLSX</a>
                    </div>
                @endif
            </div>

            <form method="GET" action="{{ route('contacts.advanced') }}" class="row g-2">
                <div class="col-12 col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Contoh: muhammad, kerinci, sungai penuh">
                </div>
                <div class="col-6 col-md-3">
                    <select name="type" class="form-select">
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
                            <div>
                                <a href="{{ route('contacts.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-plus-circle"></i>
                                    <span>Tambah Kontak</span>
                                </a>
                            </div>
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

                                            {{-- Informasi kontak digabung: tel / email / WA --}}
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

        .table-modern thead {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #ffffff;
        }

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
        .table-actions .btn-icon .bi:before {
            display: inline-block;
            line-height: 1;
            vertical-align: middle;
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

            const $q = $('input[name="q"]');
            const $type = $('select[name="type"]');
            const $onlyActive = $('#onlyActive');
            const $export = $('#exportBtn');

            function updateExportHref() {
                const params = {};
                const qVal = ($q.val() || '').trim();
                if (qVal) params.q = qVal;
                const tVal = ($type.val() || '').trim();
                if (tVal) params.type = tVal;
                if ($onlyActive.is(':checked')) params.only_active = 1;
                const qs = $.param(params);
                const $csv = $('#exportCsvBtn');
                const $xlsx = $('#exportXlsxBtn');
                if ($csv.length) {
                    const baseCsv = ($csv.data('base') || $csv.attr('href').split('?')[0]);
                    $csv.attr('href', qs ? baseCsv + '?' + qs : baseCsv);
                    $csv.data('base', baseCsv);
                }
                if ($xlsx.length) {
                    const baseXlsx = ($xlsx.data('base') || $xlsx.attr('href').split('?')[0]);
                    $xlsx.attr('href', qs ? baseXlsx + '?' + qs : baseXlsx);
                    $xlsx.data('base', baseXlsx);
                }
            }

            $type.on('change', function() {
                const params = {};
                const qVal = ($q.val() || '').trim();
                if (qVal) params.q = qVal;
                const tVal = ($type.val() || '').trim();
                if (tVal) params.type = tVal;
                if ($onlyActive.is(':checked')) params.only_active = 1;
                const qs = $.param(params);
                window.location.href = '{{ route('contacts.advanced') }}' + (qs ? ('?' + qs) : '');
            });

            @if(!empty($type))
                $type.trigger('change');
            @endif

            let qTimer;
            $q.on('input', function() {
                clearTimeout(qTimer);
                qTimer = setTimeout(function() {
                    const params = {};
                    const qVal = ($q.val() || '').trim();
                    if (qVal) params.q = qVal;
                    const tVal = ($type.val() || '').trim();
                    if (tVal) params.type = tVal;
                    if ($onlyActive.is(':checked')) params.only_active = 1;
                    const qs = $.param(params);
                    window.location.href = '{{ route('contacts.advanced') }}' + (qs ? ('?' + qs) : '');
                }, 350);
            });

            $onlyActive.on('change', function() {
                const params = {};
                const qVal = ($q.val() || '').trim();
                if (qVal) params.q = qVal;
                const tVal = ($type.val() || '').trim();
                if (tVal) params.type = tVal;
                if (this.checked) params.only_active = 1;
                const qs = $.param(params);
                window.location.href = '{{ route('contacts.advanced') }}' + (qs ? ('?' + qs) : '');
            });

            // Initial: hanya update href export, filtering handled server-side
            updateExportHref();

            // Izinkan submit form agar navigasi advance search berjalan
        });
    </script>
@endpush
