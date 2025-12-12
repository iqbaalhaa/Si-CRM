@extends('layouts.master')

@section('title', 'Pipeline History')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Riwayat Pipeline</h3>
            <p class="text-muted mb-0">
                {{ $campaign->name }} • Contact: <strong>{{ $cc->contact->name ?? ('#'.$cc->contact_id) }}</strong>
            </p>
        </div>
        <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Campaign
        </a>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-1">Perubahan Stage</h6>
                                <small class="text-muted">Dari status awal hingga terakhir, termasuk catatan.</small>
                            </div>
                        </div>

                        <div class="timeline">
                            @forelse($histories as $h)
                                <div class="timeline-item py-2 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">{{ $h->status }}</div>
                                            <div class="text-muted small">{{ $h->notes ?? '-' }}</div>
                                        </div>
                                        <div class="text-end xsmall text-muted">
                                            <div>{{ $h->changer->name ?? 'Unknown' }}</div>
                                            <div>{{ optional($h->created_at)->format('d M Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Belum ada riwayat perubahan stage.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-item:last-child { border-bottom: 0; }
        .xsmall { font-size: 0.7rem; }
    </style>
@endpush
