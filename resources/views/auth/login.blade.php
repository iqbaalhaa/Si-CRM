@extends('layouts.auth')

@section('page_title', 'Login — Depati CRM')

@section('heading', 'Masuk ke panel admin')
@section('subheading', 'Gunakan akun Super Admin atau Admin Perusahaan untuk mengakses dashboard.')

@section('form')
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
            </div>
            <div class="position-relative">
                <input id="password" type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required>
                <button class="password-toggle" type="button" id="togglePw">
                    <i class="fa-regular fa-eye"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                <label class="form-check-label" for="remember" style="font-size:13px;">
                    Ingat saya
                </label>
            </div>
            {{-- Kalau nanti mau forgot password:
      <a href="{{ route('password.request') }}" style="font-size:13px;">Lupa password?</a>
      --}}
        </div>

        <button type="submit" class="btn-brand w-100">
            <i class="fa-solid fa-door-open"></i>
            <span>Masuk</span>
        </button>

        <div class="mt-3 text-center" style="font-size:13px; color: var(--muted);">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar sebagai user baru</a>
        </div>
    </form>
@endsection
