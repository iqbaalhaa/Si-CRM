<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Mazer Admin')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('admindash/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="data:image/png;base64,iVBORw0K..." type="image/png">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('admindash/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('admindash/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('admindash/assets/compiled/css/iconly.css') }}">
    <style>
        :root,
        [data-bs-theme="light"] {
            --bs-primary: #ff9c00;
            --bs-secondary: #03a6e5;
        }
        html[data-bs-theme="dark"] {
            --bs-primary: #ff9c00;
            --bs-secondary: #03a6e5;
        }
        .text-primary { color: var(--bs-primary) !important; }
        .bg-primary { background-color: var(--bs-primary) !important; }
        .text-secondary { color: var(--bs-secondary) !important; }
        .bg-secondary { background-color: var(--bs-secondary) !important; }
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #e68c00;
            border-color: #e68c00;
        }
    </style>

    @stack('styles')
</head>

<body>
    <script src="{{ asset('admindash/assets/static/js/initTheme.js') }}"></script>

    <div id="app">
        <div id="sidebar">
            @include('partials.sidebar')
        </div>

        <div id="main">
            {{-- Header (burger button) --}}
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

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

    @stack('scripts')

</body>
</html>
