@extends('layouts.master')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Detail Kontak</h3>
            <p class="text-muted mb-0">Informasi lengkap kontak.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Nama</span>
                                        <strong>{{ $contact->name }}</strong>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Tipe</span>
                                        <strong>{{ ucfirst($contact->type) }}</strong>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Status</span>
                                        <strong>
                                            @if($contact->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </strong>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Dibuat oleh</span>
                                        <strong>{{ optional($contact->creator)->name ?? '-' }}</strong>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Dibuat pada</span>
                                        <strong>{{ $contact->created_at->format('d M Y H:i') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-2">Channels</h6>
                                <ul class="list-group mb-3">
                                    @forelse($contact->channels as $ch)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-capitalize">{{ $ch->label }}</span>
                                            <span>{{ $ch->value }}</span>
                                            @if($ch->is_primary)
                                                <span class="badge bg-primary">Primary</span>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">Belum ada channel</li>
                                    @endforelse
                                </ul>

                                <h6 class="mb-2">Details</h6>
                                <ul class="list-group">
                                    @forelse($contact->details as $d)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-capitalize">{{ $d->label }}</span>
                                            <span>{{ $d->value }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">Belum ada detail</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
