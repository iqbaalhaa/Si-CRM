<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>


    <link rel="icon" href="{{ asset('crmlogo.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('crmlogo.ico') }}" type="image/x-icon">

    {{-- CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admindash/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('admindash/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('admindash/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('admindash/assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <style>
        :root,
        [data-bs-theme="light"] {
            --bs-primary: #ff9c00;
            --bs-secondary: #03a6e5;
            --bs-body-font-family: 'Nunito Sans', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
        }

        html[data-bs-theme="dark"] {
            --bs-primary: #ff9c00;
            --bs-secondary: #03a6e5;
            --bs-body-font-family: 'Nunito Sans', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
        }

        body {
            font-family: var(--bs-body-font-family) !important;
        }

        .text-primary {
            color: var(--bs-primary) !important;
        }

        .bg-primary {
            background-color: var(--bs-primary) !important;
        }

        .text-secondary {
            color: var(--bs-secondary) !important;
        }

        .bg-secondary {
            background-color: var(--bs-secondary) !important;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #e68c00;
            border-color: #e68c00;
        }

        .sidebar-link {
            color: var(--bs-secondary) !important;
        }

        .sidebar-title {
            color: var(--bs-secondary) !important;
        }

        .sidebar-item.active>.sidebar-link,
        .sidebar-link:hover {
            background-color: var(--bs-primary) !important;
            color: #fff !important;
        }

        .nav-link.active,
        .nav-pills .nav-link.active {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
            color: #fff !important;
        }

        .page-item.active .page-link {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }

        .badge.bg-primary,
        .progress-bar,
        .list-group-item.active {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }
    </style>

    @stack('styles')
    @if(session('force_dark'))
    <script>
        localStorage.setItem('theme', 'dark');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
    </script>
    @endif
</head>

<body>
    <script src="{{ asset('admindash/assets/static/js/initTheme.js') }}"></script>

    <div id="app">
        <div id="sidebar">
            @include('partials.sidebar')
        </div>

        <div id="main">
            {{-- Header (burger button) --}}
            @php
                $adminName =
                    \App\Models\User::role('admin')->first()?->name ??
                    (\App\Models\User::role('super-admin')->first()?->name ?? (Auth::user()->name ?? 'User'));
            @endphp
            @include('partials.header', ['adminName' => $adminName])

            {{-- ISI HALAMAN --}}
            @yield('content')

            {{-- FOOTER --}}
            @include('partials.footer')
        </div>
    </div>

    {{-- JS --}}
    <script src="{{ asset('admindash/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/compiled/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: {!! json_encode(session('success')) !!}
                });
            });
        </script>
    @endif
    <script src="{{ asset('admindash/assets/static/js/pages/sweetalert2.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>


    @stack('scripts')

</body>

</html>
