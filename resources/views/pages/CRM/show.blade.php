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

                            // jalur stage (tanpa duplikat, urut waktu)
                            $stagePath = $histories->isNotEmpty()
                                ? $histories
                                    ->sortBy('created_at')
                                    ->map(fn($h) => optional($h->toStage)->name ?? optional($h->fromStage)->name)
                                    ->filter()
                                    ->unique()
                                    ->values()
                                : collect();
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Histori Stage / Progression</h5>
                            @if ($stagePath->isNotEmpty())
                                <span class="badge bg-light text-dark border">
                                    {{ $stagePath->join(' → ') }}
                                </span>
                            @endif
                        </div>
                        <small class="text-muted d-block mb-3">
                            Menampilkan perubahan stage dari waktu ke waktu beserta catatan singkat.
                        </small>

                        {{-- Timeline progression pakai list-group bootstrap --}}
                        <ul class="list-group list-group-flush mb-3">
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

                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <strong>{{ $toName ?? '-' }}</strong>
                                            @if ($toName)
                                                <span class="badge {{ $badgeClass }} ms-2">
                                                    {{ $toName }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-muted" style="font-size: 0.8rem;">
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
                                    <small class="text-muted d-block">
                                        {{ $history->note ?? 'Tidak ada catatan tambahan.' }}
                                    </small>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">
                                    Belum ada riwayat perpindahan stage untuk customer ini.
                                </li>
                            @endforelse
                        </ul>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali ke daftar
                            </a>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modal-update-stage">
                                    <i class="bi bi-graph-up-arrow me-1"></i>
                                    Update Stage
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Update Stage --}}
    <div class="modal fade" id="modal-update-stage" tabindex="-1" aria-labelledby="modal-update-stage-label"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('customers.update-stage', $customer) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modal-update-stage-label">Update Stage Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" value="{{ $customer->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="to_stage_id" class="form-label">Stage Baru</label>
                        <select name="to_stage_id" id="to_stage_id"
                            class="form-select @error('to_stage_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Stage --</option>
                            @foreach ($stages as $stage)
                                <option value="{{ $stage->id }}" @selected(old('to_stage_id', $customer->current_stage_id) == $stage->id)>
                                    {{ $stage->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('to_stage_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-0">
                        <label for="note" class="form-label">Catatan (opsional)</label>
                        <textarea name="note" id="note" rows="3" class="form-control @error('note') is-invalid @enderror"
                            placeholder="Contoh: Naik stage karena sudah deal, menunggu pembayaran.">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
