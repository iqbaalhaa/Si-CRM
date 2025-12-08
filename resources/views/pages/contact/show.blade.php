@extends('layouts.master')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Kontak</h2>
            <p class="text-muted mb-0">Informasi lengkap kontak dan channel komunikasinya.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-primary">
                {{-- <i class="bi bi-pencil me-1"></i> --}}
                Edit
            </a>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                {{-- <i class="bi bi-arrow-left me-1"></i> --}}
                Kembali
            </a>
        </div>
    </div>

    <div class="page-content">
        <div class="row g-4">
            {{-- Kolom kiri: info utama kontak --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 56px; height: 56px; background: #e9ecef; font-weight: 600;">
                                    {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <h4 class="mb-1">{{ $contact->name }}</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark text-capitalize">
                                        {{ $contact->type }}
                                    </span>
                                    @if($contact->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-2">
                            <dl class="row mb-0 small">
                                <dt class="col-5 text-muted">Dibuat oleh</dt>
                                <dd class="col-7 text-end fw-semibold mb-2">
                                    {{ optional($contact->creator)->name ?? '-' }}
                                </dd>

                                <dt class="col-5 text-muted">Dibuat pada</dt>
                                <dd class="col-7 text-end fw-semibold mb-2">
                                    {{ $contact->created_at->format('d M Y H:i') }}
                                </dd>

                                <dt class="col-5 text-muted">Status</dt>
                                <dd class="col-7 text-end mb-0">
                                    @if($contact->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan: channels & details --}}
            <div class="col-lg-8">
                <div class="row g-4">
                    {{-- Channels --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Channels</h6>
                                    <small class="text-muted">Informasi nomor, email, dan channel komunikasi lainnya.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse($contact->channels as $ch)
                                    <div class="d-flex align-items-center justify-content-between border rounded-3 px-3 py-2 mb-2">
                                        <div class="me-3">
                                            <div class="text-capitalize fw-semibold">{{ $ch->label }}</div>
                                            <div class="text-muted small">{{ $ch->value }}</div>
                                        </div>
                                        <div class="text-end">
                                            @if($ch->is_primary)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                    Primary
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Belum ada channel yang ditambahkan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Detail Tambahan</h6>
                                    <small class="text-muted">Informasi lain terkait kontak ini.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse($contact->details as $d)
                                    <div class="d-flex align-items-center justify-content-between border rounded-3 px-3 py-2 mb-2">
                                        <div class="text-capitalize text-muted small">{{ $d->label }}</div>
                                        <div class="fw-semibold text-end">{{ $d->value }}</div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Belum ada detail tambahan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
