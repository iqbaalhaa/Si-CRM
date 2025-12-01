@extends('layouts.error')

@section('title', '404 Not Found')

@section('content')
    <div class="error-code">404</div>
    <div class="error-message">Halaman tidak ditemukan.</div>

    @php
        $user = auth()->user();
        $dashboardUrl = $user ? $user->dashboardRoute() : route('login');
    @endphp

    <a href="{{ $dashboardUrl }}">Go to Dashboard</a>
@endsection
