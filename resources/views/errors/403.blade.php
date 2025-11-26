@extends('layouts.error')

@section('title', '404 Not Found')

@section('content')
    <div class="error-code">404</div>
    <div class="error-message">Halaman tidak ditemukan.</div>

    <a href="{{ auth()->user()->dashboardRoute() }}">Go to Dashboard</a>
@endsection
