@extends('layouts.master')

@section('title', 'Setting Profile')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Setting Profile</h3>
        <p class="text-muted mb-0">
            Perbarui informasi akun dan detail profil Anda.
        </p>
    </div>
    <div></div>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-12 col-xl-12">

            {{-- Alert sukses --}}
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            {{-- Alert error validasi --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-1">Terjadi kesalahan:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px;">
                                <span class="text-white fw-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Profil Akun</h5>
                            <small class="text-muted">
                                Pastikan data sudah sesuai sebelum menyimpan.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/setting-menu') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">
                                Email digunakan untuk login dan notifikasi.
                            </small>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                placeholder="Kosongkan jika tidak ingin mengganti password"
                            >
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">
                                Minimal 8 karakter. Biarkan kosong jika tidak ada perubahan.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input
                                type="text"
                                class="form-control @error('job_title') is-invalid @enderror"
                                name="job_title"
                                value="{{ old('job_title', optional($profile)->job_title) }}"
                                placeholder="Misal: Staf Keuangan, Admin Sistem"
                            >
                            @error('job_title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div> {{-- col --}}
    </div> {{-- row --}}
</div>
@endsection
