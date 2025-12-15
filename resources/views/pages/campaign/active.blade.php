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
                            @foreach($campaigns as $campaign)
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="campaign-card h-100 p-3"
                                         data-name="{{ $campaign->name }}"
                                         data-status="active"
                                         data-url="{{ route('campaign.show', $campaign->id) }}"
                                         onclick="window.location.href='{{ route('campaign.show', $campaign->id) }}'">
                                        <div class="d-flex justify-content-between align-items-start mb-2 title-row">
                                            <div class="d-flex align-items-center gap-2 flex-grow-1 title-left">
                                                <h6 class="mb-0 campaign-name">{{ $campaign->name }}</h6>
                                            </div>
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

                                        <div class="d-flex justify-content-between align-items-center mb-2 small owner-row">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                    {{ strtoupper(substr($campaign->creator->name ?? 'U',0,1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small mb-0">{{ $campaign->creator->name ?? 'Unknown' }}</div>
                                                    <div class="text-muted xsmall">Owner</div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="progress" style="height: 6px; width: 120px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $stats['avg_closing_rate'] ?? 0 }}%;"
                                                         aria-valuenow="{{ $stats['avg_closing_rate'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top footer-row">
                                            <div class="d-flex flex-wrap gap-2 xsmall text-muted">
                                                <span><i class="bi bi-people me-1"></i>Tim: {{ $campaign->teams->count() }}</span>
                                            </div>
                                            
                                        </div>
                                        
                                        <span class="status-badge {{ $campaign->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $campaign->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
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
