@extends('layouts.auth')

@section('page_title', 'Daftar — Depati CRM')

@section('heading', 'Buat akun Depati CRM')
@section('subheading', 'Daftarkan akun untuk mulai mengelola lead, customer, dan aktivitas tim.')

@section('form')
    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama lengkap</label>
            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kalau nanti mau pilih company/id perusahaan, bisa taruh di sini --}}
        {{-- 
    <div class="mb-3">
      <label for="company_id" class="form-label">Perusahaan</label>
      <select name="company_id" id="company_id" class="form-select">
        <option value="">Pilih perusahaan</option>
        @foreach ($companies as $company)
          <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
            {{ $company->name }}
          </option>
        @endforeach
      </select>
    </div>
    --}}

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
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

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn-brand w-100">
            <i class="fa-solid fa-user-plus"></i>
            <span>Daftar</span>
        </button>

        <div class="mt-3 text-center" style="font-size:13px; color: var(--muted);">
            Sudah punya akun?
            <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </form>
@endsection
