@extends('layouts.master')

@section('title', 'Tambah Perusahaan')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="mb-1">Tambah Perusahaan</h3>
        <p class="text-muted mb-0">
            Lengkapi data perusahaan untuk memulai pencatatan relasi di DepatiCRM.
        </p>
    </div>
    <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left-short me-1"></i>
        Kembali
    </a>
</div>

<div class="page-content">
    <div class="row">
        {{-- Form utama --}}
        <div class="col-12 col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 fw-semibold">
                                <i class="bi bi-building me-1"></i>
                                Detail Perusahaan
                            </h6>
                            <p class="text-muted small mb-0">
                                Field bertanda <span class="text-danger">*</span> wajib diisi.
                            </p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary">
                            <i class="bi bi-plus-circle me-1"></i> Baru
                        </span>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <form action="{{ route('perusahaan.store') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="row g-3">
                            {{-- Nama & Kode --}}
                            <div class="col-lg-6">
                                <label for="name" class="form-label fw-semibold">
                                    Nama Perusahaan <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Contoh: PT Semata Digital"
                                    value="{{ old('name') }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label for="code" class="form-label fw-semibold">
                                    Kode Perusahaan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-hash"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="code"
                                        id="code"
                                        class="form-control text-uppercase @error('code') is-invalid @enderror"
                                        placeholder="Contoh: MJS-001"
                                        value="{{ old('code') }}"
                                        required
                                    >
                                </div>
                                @error('code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <div class="form-text">Kode unik untuk memudahkan pencarian dan integrasi.</div>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">
                                    Alamat <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    name="address"
                                    id="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Alamat lengkap kantor utama"
                                    required
                                >{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-text">Sertakan kota dan kode pos untuk memudahkan pengelompokan.</div>
                                @enderror
                            </div>

                            {{-- Kontak --}}
                            <div class="col-12 mt-2">
                                <h6 class="text-muted text-uppercase small mb-2">
                                    Informasi Kontak
                                </h6>
                            </div>

                            <div class="col-lg-6">
                                <label for="phone" class="form-label fw-semibold">
                                    Telepon
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="phone"
                                        id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="Contoh: 021-1234567 / +62..."
                                        value="{{ old('phone') }}"
                                    >
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <div class="form-text">Opsional, namun disarankan untuk kontak cepat.</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label for="email" class="form-label fw-semibold">
                                    Email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-at"></i>
                                    </span>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Contoh: info@perusahaan.com"
                                        value="{{ old('email') }}"
                                    >
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <div class="form-text">Gunakan email resmi perusahaan jika tersedia.</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-6">
                                <label for="status" class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="status"
                                    id="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required
                                >
                                    <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-text">Anda dapat mengubah status kapan saja.</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="mt-4 mb-3">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('perusahaan.index') }}" class="btn btn-light border">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                Simpan Perusahaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
