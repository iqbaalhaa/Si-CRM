@extends('layouts.master')

@section('title', 'Campaign Active')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Campaign Active</h3>
            <p class="text-muted mb-0">
                Pantau semua campaign yang sedang berjalan dan klik untuk lihat detail & kontak.
            </p>
        </div>
        <a href="{{ url('/campaigns/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Buat Campaign Baru
        </a>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">

                {{-- Summary cards (pure frontend, angka dummy) --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Total Campaign Active</div>
                                    <h4 class="mb-0">3</h4>
                                    <small class="text-muted">Termasuk WhatsApp, Email, dan Telemarketing.</small>
                                </div>
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="bi bi-bullseye fs-4 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Total Kontak di Campaign</div>
                                    <h4 class="mb-0">1.250</h4>
                                    <small class="text-muted">Akumulasi dari semua campaign aktif.</small>
                                </div>
                                <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                                    <i class="bi bi-people fs-4 text-secondary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Perkiraan Closing Rate</div>
                                    <h4 class="mb-0">18%</h4>
                                    <small class="text-muted">Estimasi rata-rata dari semua campaign.</small>
                                </div>
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <form class="row g-2 align-items-end" id="form-filter-campaign">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Cari Campaign</label>
                                <input type="text" class="form-control form-control-sm"
                                       id="filter-search"
                                       placeholder="Nama campaign / deskripsi">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Channel</label>
                                <select class="form-select form-select-sm" id="filter-channel">
                                    <option value="">Semua Channel</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="email">Email</option>
                                    <option value="telemarketing">Telemarketing</option>
                                    <option value="social">Social Media</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Periode</label>
                                <select class="form-select form-select-sm" id="filter-period">
                                    <option value="">Semua</option>
                                    <option value="this-month">Bulan ini</option>
                                    <option value="last-3-months">3 bulan terakhir</option>
                                    <option value="last-6-months">6 bulan terakhir</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Status</label>
                                <select class="form-select form-select-sm" id="filter-status">
                                    <option value="">Aktif & Pause</option>
                                    <option value="active">Active</option>
                                    <option value="pause">Pause</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Campaign list as cards --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                            <div>
                                <h5 class="mb-0">Campaign yang Sedang Berjalan</h5>
                                <small class="text-muted">
                                    Klik kartu untuk lihat detail, atau gunakan tombol aksi di kanan bawah.
                                </small>
                            </div>
                            <div class="small text-muted">
                                <span id="campaign-count">3</span> campaign ditampilkan
                            </div>
                        </div>

                        <div class="row g-3" id="campaign-list">
                            {{-- Campaign 1 --}}
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="campaign-card h-100 p-3"
                                     data-name="Winter Sale 2025"
                                     data-desc="Diskon akhir tahun untuk customer lama dan baru"
                                     data-channel="whatsapp"
                                     data-status="active"
                                     data-period="this-month">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Winter Sale 2025</h6>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                Diskon akhir tahun untuk customer lama & baru.
                                            </p>
                                        </div>
                                        <a href="{{ url('/campaigns/1') }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 small">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-whatsapp me-1"></i>WhatsApp Blast
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>01 Dec 2025 - 31 Dec 2025
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            520 kontak
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                 style="width: 28px; height: 28px;">
                                                AD
                                            </div>
                                            <div>
                                                <div class="fw-semibold small mb-0">Admin Depati</div>
                                                <div class="text-muted xsmall">Owner Campaign</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="xsmall text-muted mb-1">Perkiraan closing</div>
                                            <div class="progress" style="height: 6px; width: 120px;">
                                                <div class="progress-bar" role="progressbar" style="width: 20%;"
                                                     aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="xsmall text-muted mt-1">~20% closing rate</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex flex-wrap gap-2 xsmall text-muted">
                                            <span><i class="bi bi-person-lines-fill me-1"></i>Segment: Customer lama & lead baru</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-campaign-pause">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-campaign-duplicate">
                                                <i class="bi bi-files"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Campaign 2 --}}
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="campaign-card h-100 p-3"
                                     data-name="Onboarding Client Baru Q1"
                                     data-desc="Follow up semua lead yang masuk dari iklan"
                                     data-channel="email"
                                     data-status="active"
                                     data-period="last-3-months">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Onboarding Client Baru Q1</h6>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                Follow up semua lead yang masuk dari iklan.
                                            </p>
                                        </div>
                                        <a href="{{ url('/campaigns/2') }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 small">
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="bi bi-envelope me-1"></i>Email Sequence
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>15 Nov 2025 - 15 Jan 2026
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            380 kontak
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                                 style="width: 28px; height: 28px;">
                                                MK
                                            </div>
                                            <div>
                                                <div class="fw-semibold small mb-0">Marketing Team</div>
                                                <div class="text-muted xsmall">Owner Campaign</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="xsmall text-muted mb-1">Perkiraan closing</div>
                                            <div class="progress" style="height: 6px; width: 120px;">
                                                <div class="progress-bar" role="progressbar" style="width: 22%;"
                                                     aria-valuenow="22" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="xsmall text-muted mt-1">~22% closing rate</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex flex-wrap gap-2 xsmall text-muted">
                                            <span><i class="bi bi-funnel me-1"></i>Segment: Lead inbound dari Ads</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-campaign-pause">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-campaign-duplicate">
                                                <i class="bi bi-files"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Campaign 3 --}}
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="campaign-card h-100 p-3"
                                     data-name="Upsell Paket Premium"
                                     data-desc="Naikkan ARPU dari customer aktif"
                                     data-channel="telemarketing"
                                     data-status="active"
                                     data-period="last-6-months">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Upsell Paket Premium</h6>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                Naikkan ARPU dari customer aktif.
                                            </p>
                                        </div>
                                        <a href="{{ url('/campaigns/3') }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 small">
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="bi bi-telephone-outbound me-1"></i>Telemarketing
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>01 Oct 2025 - 31 Dec 2025
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            350 kontak
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                                 style="width: 28px; height: 28px;">
                                                CS
                                            </div>
                                            <div>
                                                <div class="fw-semibold small mb-0">CS Team</div>
                                                <div class="text-muted xsmall">Owner Campaign</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="xsmall text-muted mb-1">Perkiraan closing</div>
                                            <div class="progress" style="height: 6px; width: 120px;">
                                                <div class="progress-bar" role="progressbar" style="width: 15%;"
                                                     aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="xsmall text-muted mt-1">~15% closing rate</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex flex-wrap gap-2 xsmall text-muted">
                                            <span><i class="bi bi-stars me-1"></i>Segment: Customer aktif (upsell)</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-campaign-pause">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-campaign-duplicate">
                                                <i class="bi bi-files"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Jika nanti kosong, bisa tambahkan state empty di JS --}}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .campaign-card {
            border-radius: 0.9rem;
            border: 1px solid var(--bs-border-color);
            background-color: var(--bs-body-bg);
            transition: box-shadow 0.15s ease, transform 0.15s ease, border-color 0.15s ease;
            cursor: default;
        }

        .campaign-card:hover {
            box-shadow: 0 0.5rem 1.25rem rgba(15, 23, 42, 0.08);
            transform: translateY(-2px);
            border-color: rgba(255, 156, 0, 0.4); /* hint warna primary Depati */
        }

        .xsmall {
            font-size: 0.7rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script>
        $(function () {
            const $cards = $('#campaign-list .campaign-card');

            function applyFilters() {
                const search = $('#filter-search').val().toLowerCase();
                const channel = $('#filter-channel').val();
                const period = $('#filter-period').val();
                const status = $('#filter-status').val();

                let visibleCount = 0;

                $cards.each(function () {
                    const $c = $(this);
                    const name = ($c.data('name') || '').toString().toLowerCase();
                    const desc = ($c.data('desc') || '').toString().toLowerCase();
                    const cChannel = ($c.data('channel') || '').toString();
                    const cPeriod = ($c.data('period') || '').toString();
                    const cStatus = ($c.data('status') || '').toString();

                    let show = true;

                    if (search) {
                        show = name.indexOf(search) !== -1 || desc.indexOf(search) !== -1;
                    }

                    if (show && channel) {
                        show = (cChannel === channel);
                    }

                    if (show && period) {
                        show = (cPeriod === period);
                    }

                    if (show && status) {
                        show = (cStatus === status);
                    }

                    if (show) {
                        $c.closest('.col-12').show();
                        visibleCount++;
                    } else {
                        $c.closest('.col-12').hide();
                    }
                });

                $('#campaign-count').text(visibleCount);
            }

            $('#filter-search').on('keyup', function () {
                applyFilters();
            });
            $('#filter-channel, #filter-period, #filter-status').on('change', function () {
                applyFilters();
            });

            // Tombol Pause (Prototype)
            $(document).on('click', '.btn-campaign-pause', function () {
                const $card = $(this).closest('.campaign-card');
                const name = $card.data('name') || 'Campaign';
                Swal.fire({
                    icon: 'info',
                    title: 'Pause Campaign (Prototype)',
                    text: 'Di versi production, campaign "' + name + '" akan dipause / dihentikan sementara.'
                });
            });

            // Tombol Duplicate (Prototype)
            $(document).on('click', '.btn-campaign-duplicate', function () {
                const $card = $(this).closest('.campaign-card');
                const name = $card.data('name') || 'Campaign';
                Swal.fire({
                    icon: 'success',
                    title: 'Duplicate Campaign (Prototype)',
                    text: 'Di versi production, akan dibuat draft campaign baru berdasarkan "' + name + '".'
                });
            });

            // Jalankan filter awal untuk sync jumlah
            applyFilters();
        });
    </script>
@endpush
