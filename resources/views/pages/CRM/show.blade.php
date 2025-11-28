@extends('layouts.master')

@section('title', 'Detail Progression')

@section('content')
    <div class="page-heading mb-3">
        <h3>Detail Progression Customer</h3>
    </div>

    <div class="page-content">
        <div class="row g-3">
            {{-- Info akun customer --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        @php
                            $currentStageName = optional($customer->stage)->name;
                            $currentStageBadge = match ($currentStageName) {
                                'New' => 'bg-secondary',
                                'Contact' => 'bg-info text-dark',
                                'Hold' => 'bg-warning text-dark',
                                'No Respon' => 'bg-dark',
                                'Loss' => 'bg-danger',
                                'Close' => 'bg-success',
                                default => 'bg-light text-dark',
                            };
                        @endphp

                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-1">{{ $customer->name }}</h5>
                                <small class="text-muted">
                                    {{ optional($customer->company)->name ?? '-' }}
                                </small>
                            </div>

                            @if ($currentStageName)
                                <span class="badge {{ $currentStageBadge }}">
                                    {{ $currentStageName }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted">-</span>
                            @endif
                        </div>

                        <hr>

                        <div class="mb-2">
                            <small class="text-muted d-block">Telepon</small>
                            <span>{{ $customer->phone ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Email</small>
                            <span>{{ $customer->email ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">PIC Internal</small>
                            <span>{{ optional($customer->assignedTo)->name ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Stage Awal</small>
                            @php
                                $firstHistory = $histories->last(); // paling lama
                                $firstStageName =
                                    optional(optional($firstHistory)->toStage)->name ??
                                    (optional(optional($firstHistory)->fromStage)->name ?? $currentStageName);
                            @endphp
                            @if ($firstStageName)
                                <span class="badge bg-secondary">{{ $firstStageName }}</span>
                            @else
                                <span class="badge bg-light text-muted">-</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Stage Terakhir</small>
                            @if ($currentStageName)
                                <span class="badge {{ $currentStageBadge }}">{{ $currentStageName }}</span>
                            @else
                                <span class="badge bg-light text-muted">-</span>
                            @endif
                            <small class="d-block text-muted mt-1">
                                Update:
                                {{ optional($customer->updated_at)->format('d M Y H:i') ?? '-' }}
                            </small>
                        </div>

                        <hr>

                        <small class="text-muted d-block mb-1">Ringkasan Perjalanan</small>
                        <ul class="mb-0">
                            @if ($histories->isEmpty())
                                <li>Belum ada perpindahan stage yang tercatat.</li>
                            @else
                                @php
                                    $stagePath = $histories
                                        ->sortBy('created_at')
                                        ->map(fn($h) => optional($h->toStage)->name ?? optional($h->fromStage)->name)
                                        ->filter()
                                        ->unique()
                                        ->values();
                                @endphp
                                <li>
                                    Jalur stage:
                                    {{ $stagePath->join(' → ') }}
                                </li>
                                <li>
                                    Total perpindahan: {{ $histories->count() }} kali
                                </li>
                                <li>
                                    Stage pertama:
                                    {{ $firstStageName ?? '-' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Histori progression --}}
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        @php
                            $pathLabel = $stagePath ?? collect();
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Histori Stage / Progression</h5>
                            @if (isset($stagePath) && $stagePath->isNotEmpty())
                                <span class="badge bg-light text-dark border">
                                    {{ $stagePath->join(' → ') }}
                                </span>
                            @endif
                        </div>
                        <small class="text-muted d-block mb-3">
                            Menampilkan perubahan stage dari waktu ke waktu beserta catatan singkat.
                        </small>

                        {{-- Timeline progression --}}
                        <div class="timeline">
                            <style>
                                .timeline-line {
                                    border-left: 2px solid #dee2e6;
                                    margin-left: 12px;
                                    padding-left: 20px;
                                }

                                .timeline-item {
                                    position: relative;
                                    margin-bottom: 1.5rem;
                                }

                                .timeline-item::before {
                                    content: '';
                                    position: absolute;
                                    left: -12px;
                                    top: 4px;
                                    width: 12px;
                                    height: 12px;
                                    border-radius: 50%;
                                    background-color: var(--bs-primary);
                                }

                                .timeline-time {
                                    font-size: 0.8rem;
                                    color: #6c757d;
                                }
                            </style>

                            <div class="timeline-line">
                                @forelse ($histories as $history)
                                    @php
                                        $toName = optional($history->toStage)->name;
                                        $badgeClass = match ($toName) {
                                            'New' => 'bg-secondary',
                                            'Contact' => 'bg-info text-dark',
                                            'Hold' => 'bg-warning text-dark',
                                            'No Respon' => 'bg-dark',
                                            'Loss' => 'bg-danger',
                                            'Close' => 'bg-success',
                                            default => 'bg-light text-dark',
                                        };
                                    @endphp

                                    <div class="timeline-item">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div>
                                                <strong>
                                                    {{ $toName ?? '-' }}
                                                </strong>
                                                @if ($toName)
                                                    <span class="badge {{ $badgeClass }} ms-2">
                                                        {{ $toName }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="timeline-time">
                                                {{ $history->created_at->format('d M Y H:i') }}
                                            </span>
                                        </div>
                                        <p class="mb-1">
                                            @if ($history->fromStage)
                                                Pindah dari
                                                <strong>{{ $history->fromStage->name }}</strong>
                                                ke
                                                <strong>{{ $toName ?? '-' }}</strong>
                                            @else
                                                Stage diset ke
                                                <strong>{{ $toName ?? '-' }}</strong>
                                            @endif

                                            @if ($history->changer)
                                                oleh <strong>{{ $history->changer->name }}</strong>.
                                            @endif
                                        </p>
                                        <small class="text-muted">
                                            {{ $history->note ?? 'Tidak ada catatan tambahan.' }}
                                        </small>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">
                                        Belum ada riwayat perpindahan stage untuk customer ini.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('stages.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali ke daftar
                            </a>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" disabled>
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Tambah Catatan (coming soon)
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" disabled>
                                    <i class="bi bi-graph-up-arrow me-1"></i>
                                    Update Stage (coming soon)
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
