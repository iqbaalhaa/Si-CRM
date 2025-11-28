@extends('layouts.error')

@section('title', '505 Not Found')

@section('content')
    <div class="error-code">505</div>
    <div class="error-message">Server error.</div>

    @php
        $user = auth()->user();
        $dashboardUrl = $user ? $user->dashboardRoute() : route('login');
    @endphp

    <a href="{{ $dashboardUrl }}">Go to Dashboard</a>
@endsection
