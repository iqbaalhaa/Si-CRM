@extends('layouts.error')

@section('title', '505 Not Found')

@section('content')
    <div class="error-code">505</div>
    <div class="error-message">Server error.</div>

    <a href="{{ auth()->user()->dashboardRoute() }}">Go to Dashboard</a>
@endsection
