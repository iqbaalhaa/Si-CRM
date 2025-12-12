@extends('layouts.master')

@section('title', 'History Campaign')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>History Campaign</h3>
            <p class="text-muted mb-0">
                Arsip campaign yang sudah selesai / dihentikan. Cocok untuk evaluasi dan re-use.
            </p>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">

                

                

                {{-- Campaign history list as cards --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                            <div>
                                <h5 class="mb-0">Daftar Campaign Selesai</h5>
                                <small class="text-muted">Klik <strong>Detail</strong> untuk melihat isi campaign.</small>
                            </div>
                            <div class="small text-muted">
                                <span id="history-count">{{ $campaigns->count() }}</span> campaign ditampilkan
                            </div>
                        </div>

                        <div class="row g-3" id="history-list">
                            @foreach($campaigns as $campaign)
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="campaign-card h-100 p-3 history-card" data-name="{{ $campaign->name }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <h6 class="mb-0">{{ $campaign->name }}</h6>
                                                    <span class="badge bg-secondary">Selesai</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 mb-2 small">
                                            <span class="badge bg-light text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ optional($campaign->from)->format('d M Y') }} - {{ optional($campaign->to)->format('d M Y') }}
                                            </span>
                                            <span class="badge bg-light text-muted">{{ optional($campaign->to)->format('Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                            <div class="d-flex align-items-center gap-2 small">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 26px; height: 26px;">
                                                    {{ strtoupper(substr($campaign->creator->name ?? 'U',0,1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small mb-0">{{ $campaign->creator->name ?? 'Unknown' }}</div>
                                                    <div class="text-muted xsmall">Owner</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Removed static history cards --}}
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="campaign-card h-100 p-3 history-card"
                                     data-name="Ramadhan Sale 2025"
                                     data-desc="Paket promo menjelang Idul Fitri"
                                     data-channel="whatsapp"
                                     data-year="2025"
                                     data-result="success">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Ramadhan Sale 2025</h6>
                                                <span class="badge bg-success">Selesai • Sukses</span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                Paket promo menjelang Idul Fitri.
                                            </p>
                                        </div>
                                        <a href="{{ url('/campaigns/ramadhan-sale-2025') }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 small">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-whatsapp me-1"></i>WhatsApp Blast
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>01 Mar 2025 - 10 Apr 2025
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            2025
                                        </span>
                                    </div>

                                    {{-- Metrics --}}
                                    <div class="row small mb-2">
                                        <div class="col-6">
                                            <div class="text-muted xsmall mb-1">Kontak</div>
                                            <div class="fw-semibold">450</div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="text-muted xsmall mb-1">Closing</div>
                                            <div class="fw-semibold">72 (~16%)</div>
                                        </div>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%;"
                                             aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2 xsmall text-muted">
                                        <span><i class="bi bi-flag-fill me-1 text-success"></i>Di atas target</span>
                                        <span>Performa baik untuk seasonal promo</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex align-items-center gap-2 small">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                 style="width: 26px; height: 26px;">
                                                AD
                                            </div>
                                            <div>
                                                <div class="fw-semibold small mb-0">Admin Depati</div>
                                                <div class="text-muted xsmall">Owner</div>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-history-template"
                                                    data-template-name="Ramadhan Sale 2025">
                                                <i class="bi bi-clipboard-plus"></i>
                                                <span class="d-none d-lg-inline"> Template</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            

                        </div> {{-- end row --}}
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
            border-color: rgba(255, 156, 0, 0.4);
        }

        .xsmall {
            font-size: 0.7rem;
        }
    </style>
@endpush

@push('scripts')
@endpush
