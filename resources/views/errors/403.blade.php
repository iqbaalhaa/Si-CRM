@extends('layouts.error')

@section('title', '403 Not Found')

@section('content')
    <div class="error-code">403</div>
    <div class="error-message">You are not authorized.</div>

    <a href="{{ auth()->user()->dashboardRoute() }}">Go to Dashboard</a>
@endsection
