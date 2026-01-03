@extends('layouts.master')

@section('title', 'Campaign Aktif')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Campaign Aktif</h3>
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

                {{-- WA BLAST - CARD INFORMASI TERAKHIR (PURE FRONTEND) --}}
                <div class="card mb-3" id="wa-blast-info" style="display: none;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0">
                                    <i class="bi bi-whatsapp me-1 text-success"></i>
                                    WA Blast Terakhir
                                </h6>
                                <small class="text-muted">
                                    Informasi blast terakhir yang kamu jalankan dari halaman ini.
                                </small>
                            </div>
                            <span class="badge bg-success-subtle text-success" id="wa-info-status">
                                Sukses (Dummy)
                            </span>
                        </div>
                        <div class="row g-2 small">
                            <div class="col-md-3">
                                <div class="text-muted xsmall">Campaign</div>
                                <div id="wa-info-campaign" class="fw-semibold">-</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted xsmall">Jumlah Kontak</div>
                                <div id="wa-info-contacts">-</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted xsmall">Waktu Blast</div>
                                <div id="wa-info-time">-</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted xsmall">Channel</div>
                                <div id="wa-info-channel">WhatsApp</div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="text-muted xsmall mb-1">Preview Format Pesan</div>
                                <pre class="bg-light border rounded p-2 xsmall mb-0" id="wa-info-message"
                                     style="white-space: pre-wrap;">-</pre>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- END WA BLAST CARD INFO --}}

                {{-- Campaign list as cards --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                            <div>
                                <h5 class="mb-0">Campaign yang Sedang Berjalan</h5>
                            </div>
                            <div class="small text-muted">
                                <span id="campaign-count">{{ $campaigns->count() }}</span> campaign ditampilkan
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
                                     data-period="this-month"
                                     data-contacts="520">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <form method="POST" action="{{ route('campaign.stop', $campaign->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation()">
                                                        <i class="bi bi-stop-circle"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap gap-2 mb-2 small meta-row">
                                            <span class="badge bg-light text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                @if($campaign->to)
                                                    {{ optional($campaign->from)->format('d M Y') }} - {{ optional($campaign->to)->format('d M Y') }}
                                                @else
                                                    Mulai: {{ optional($campaign->from)->format('d M Y') }} (tanpa akhir)
                                                @endif
                                            </span>
                                            <span class="badge bg-light text-muted">
                                                {{ $campaign->contacts->count() }} kontak
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
                                            {{-- WA BLAST BUTTON --}}
                                            <button type="button"
                                                    class="btn btn-outline-success btn-campaign-wa-blast">
                                                <i class="bi bi-whatsapp"></i>
                                            </button>
                                            {{-- END WA BLAST BUTTON --}}
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
                                     data-period="last-3-months"
                                     data-contacts="380">
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
                                    </div`

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
                                        </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex flex-wrap gap-2 xsmall text-muted">
                                            <span><i class="bi bi-funnel me-1"></i>Segment: Lead inbound dari Ads</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            {{-- WA BLAST BUTTON --}}
                                            <button type="button"
                                                    class="btn btn-outline-success btn-campaign-wa-blast">
                                                <i class="bi bi-whatsapp"></i>
                                            </button>
                                            {{-- END WA BLAST BUTTON --}}
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
                                     data-period="last-6-months"
                                     data-contacts="350">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Upsell Paket Premium</h6>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            
                                        </div>
                                        
                                        <span class="status-badge {{ $campaign->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $campaign->is_active ? 'Aktif' : 'Nonaktif' }}
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
                                            {{-- WA BLAST BUTTON --}}
                                            <button type="button"
                                                    class="btn btn-outline-success btn-campaign-wa-blast">
                                                <i class="bi bi-whatsapp"></i>
                                            </button>
                                            {{-- END WA BLAST BUTTON --}}
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
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- WA BLAST MODAL --}}
    <div class="modal fade" id="modal-wa-blast" tabindex="-1" aria-labelledby="modalWaBlastLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalWaBlastLabel">
                        <i class="bi bi-whatsapp me-1 text-success"></i>
                        WA Blast Campaign
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                {{-- PURE FRONTEND ONLY --}}
                <form id="form-wa-blast" action="javascript:void(0)" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning xsmall mb-3">
                            Ini masih prototype. Tidak benar-benar mengirim pesan WhatsApp.
                            Di versi production, sistem akan mengirim pesan ke seluruh kontak di campaign terpilih.
                        </div>

                        <input type="hidden" id="wa-campaign-name-hidden">
                        <input type="hidden" id="wa-campaign-contacts-hidden">
                        <input type="hidden" id="wa-campaign-channel-hidden">

                        <div class="mb-3">
                            <label class="form-label small">Campaign</label>
                            <div class="fw-semibold" id="wa-campaign-name">-</div>
                            <div class="text-muted xsmall" id="wa-campaign-meta">Channel: WhatsApp · 0 kontak</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">
                                Format Pesan WhatsApp <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control form-control-sm" id="wa-message" rows="5"
                                      placeholder="Contoh:
Halo 'nama udin', kami dari Depati Digital.
Saat ini ada promo spesial untuk campaign 'nama campaiign'.

Balas pesan ini jika tertarik ya."></textarea>
                            <div class="form-text xsmall">
                                Kamu bisa gunakan placeholder seperti <code>{{ '{nama}' }}</code> atau
                                <code>{{ '{campaign}' }}</code> (dummy, belum ada parser).
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small">Simulasi Target Kontak</label>
                            <div class="d-flex flex-wrap gap-3 small">
                                <div>
                                    <span class="text-muted xsmall d-block">Total kontak</span>
                                    <span id="wa-summary-contacts" class="fw-semibold">0</span>
                                </div>
                                <div>
                                    <span class="text-muted xsmall d-block">Perkiraan terkirim</span>
                                    <span id="wa-summary-delivered" class="fw-semibold">0</span>
                                </div>
                                <div>
                                    <span class="text-muted xsmall d-block">Perkiraan respon</span>
                                    <span id="wa-summary-response" class="fw-semibold">0</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-whatsapp me-1"></i> Blast Sekarang (Prototype)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END WA BLAST MODAL --}}
@endsection

@push('styles')
    <style>
        .campaign-card {
            border-radius: 1rem;
            border: 1px solid var(--bs-border-color);
            background: linear-gradient(135deg, rgba(99,102,241,.06), rgba(14,165,233,.05));
            box-shadow: 0 12px 30px rgba(15,23,42,.08);
            transition: box-shadow .18s ease, transform .18s ease, border-color .18s ease, background .18s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .campaign-card:hover {
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
            transform: translateY(-2px);
            border-color: rgba(99,102,241,.35);
            background: linear-gradient(135deg, rgba(99,102,241,.10), rgba(14,165,233,.08));
        }

        .xsmall {
            font-size: 0.7rem;
        }
        .badge.bg-light.text-muted{
            background-color: rgba(148,163,184,.12) !important;
            border: 1px solid rgba(148,163,184,.35);
        }
        .btn.btn-sm.btn-outline-primary, .btn.btn-sm.btn-outline-danger{
            border-radius: .6rem;
        }
        .title-row{ min-height: 32px; }
        .title-left{ min-width: 0; }
        .campaign-name{ font-size: .95rem; font-weight: 600; max-width: 75%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .meta-row{ min-height: 26px; }
        .owner-row{ min-height: 48px; }
        .footer-row{ min-height: 32px; }
        .status-badge{
            position: absolute;
            right: .75rem;
            bottom: .75rem;
            padding: .25rem .5rem;
            border-radius: .6rem;
            font-size: .75rem;
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

            // ==== WA BLAST HANDLER (PROTOTYPE) ====
            $(document).on('click', '.btn-campaign-wa-blast', function () {
                const $card = $(this).closest('.campaign-card');
                const name = $card.data('name') || 'Campaign';
                const channel = $card.data('channel') || 'whatsapp';
                const contacts = parseInt($card.data('contacts') || '0', 10);

                $('#wa-campaign-name-hidden').val(name);
                $('#wa-campaign-contacts-hidden').val(contacts);
                $('#wa-campaign-channel-hidden').val(channel);

                $('#wa-campaign-name').text(name);
                $('#wa-campaign-meta').text(
                    'Channel: WhatsApp · ' + contacts + ' kontak'
                );

                // Default format pesan
                const defaultMessage =
`Halo udin, kami dari Depati Digital.

Saat ini kami sedang menjalankan campaign "nama campaign".
Ada penawaran khusus yang sayang kalau dilewatkan.

Balas pesan ini jika tertarik ya.`;
                $('#wa-message').val(defaultMessage.replace('nama campaign', name));

                // Simulasi angka (dummy)
                $('#wa-summary-contacts').text(contacts);
                $('#wa-summary-delivered').text(Math.round(contacts * 0.9));
                $('#wa-summary-response').text(Math.round(contacts * 0.15));

                // Buka modal
                $('#modal-wa-blast').modal('show');
            });

            // Submit WA Blast (Prototype)
            $('#form-wa-blast').on('submit', function (e) {
                e.preventDefault();

                const message = ($('#wa-message').val() || '').trim();
                if (!message) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format pesan kosong',
                        text: 'Silakan isi format pesan WA terlebih dahulu.'
                    });
                    return;
                }

                const name = $('#wa-campaign-name-hidden').val() || 'Campaign';
                const contacts = parseInt($('#wa-campaign-contacts-hidden').val() || '0', 10);

                // Update card info blast terakhir (pure frontend)
                $('#wa-blast-info').show();
                $('#wa-info-campaign').text(name);
                $('#wa-info-contacts').text(contacts + ' kontak');
                $('#wa-info-channel').text('WhatsApp');
                $('#wa-info-message').text(message);

                const now = new Date();
                const formatted = now.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                $('#wa-info-time').text(formatted);

                $('#wa-info-status').removeClass('bg-danger-subtle text-danger')
                    .addClass('bg-success-subtle text-success')
                    .text('Sukses (Dummy)');

                $('#modal-wa-blast').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'WA Blast (Prototype)',
                    text: 'Di versi production, pesan WA akan dikirim ke ' + contacts + ' kontak dari campaign "' + name + '".'
                });
            });

            // Jalankan filter awal untuk sync jumlah
            applyFilters();
            
            $(document).on('click', '.campaign-card', function (e) {
                if ($(e.target).closest('a,button,input,textarea,select,label').length) return;
                const url = $(this).data('url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
