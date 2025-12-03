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

        .card .table,
        .table,
        .table thead,
        .table tbody,
        .table th,
        .table td { background-color: transparent !important; }
        .card .table thead th { background-color: transparent !important; }
        .table-hover tbody tr:hover { background-color: rgba(148,163,184,.08) !important; }
        .input-group-text { display: inline-flex; align-items: center; }
        .bi, .fa, .fa-solid, .fa-regular, .fa-brands { vertical-align: middle; line-height: 1; }
    </style>

    @stack('styles')
    <style>
        html[data-bs-theme="dark"] {
            --bs-primary: #ff9c00;
            --bs-secondary: #03a6e5;
            --bs-body-font-family: 'Nunito Sans', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
            --surface: #0f172a;
            --text-muted: #9ca3af;
            --border: #334155;
        }
        html[data-bs-theme="dark"] .table thead th { color: #cbd5e1 !important; }
        html[data-bs-theme="dark"] .table tbody td { color: #e5e7eb !important; }
        html[data-bs-theme="dark"] .card { background-color: var(--surface) !important; }
        html[data-bs-theme="dark"] .card-header { border-bottom-color: var(--border) !important; background-color: transparent !important; color: #e5e7eb !important; }
        html[data-bs-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(148,163,184,.15) !important; }
        html[data-bs-theme="dark"] .dropdown-menu { background-color: var(--surface) !important; color: #e5e7eb !important; border-color: var(--border) !important; }
        html[data-bs-theme="dark"] .badge-soft { background-color: #334155 !important; color: #e5e7eb !important; }
    </style>
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
    <script>
        // Guard sidebar methods from null elements to prevent errors in compiled app.js
        window.addEventListener('DOMContentLoaded', function() {
            if (window.sidebar) {
                // Override isElementInViewport to tolerate null
                window.sidebar.isElementInViewport = function(el) {
                    if (!el) return true;
                    const rect = el.getBoundingClientRect();
                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                    );
                };
                // Override forceElementVisibility to no-op on null
                window.sidebar.forceElementVisibility = function(el) {
                    if (!el) return;
                    if (!this.isElementInViewport(el)) {
                        el.scrollIntoView(false);
                    }
                };
            }
        });
    </script>

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
