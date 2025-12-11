<header class="mb-3 border-bottom">
    <div class="d-flex align-items-center justify-content-between py-2 px-1 px-md-2">

        {{-- LEFT: Title + admin info --}}
        <div class="d-flex align-items-center gap-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <div>
                <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
                <small class="text-muted d-none d-md-inline">
                    Admin: {{ $adminName ?? (Auth::user()->name ?? 'User') }}
                </small>
            </div>
        </div>

        {{-- RIGHT: Notifications + User menu --}}
        <div class="d-flex align-items-center gap-3">

            {{-- NOTIFICATION DROPDOWN --}}
            <div class="dropdown">
                @php
                    $unread = auth()->user()->unreadNotifications;
                    $count  = $unread->count();
                @endphp

                <button
                    type="button"
                    class="position-relative border-0 bg-transparent p-0"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="Notifikasi"
                    style="box-shadow: none;"
                >
                    <i class="bi {{ $count > 0 ? 'bi-bell-fill' : 'bi-bell' }} fs-5"></i>

                    @if ($count > 0)
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $count }}
                        </span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm p-0" style="min-width: 340px;">
                    {{-- Header --}}
                    <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Notifikasi</span>

                        @if ($count > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button class="btn btn-sm btn-link p-0 text-decoration-none">
                                    Tandai semua terbaca
                                </button>
                            </form>
                        @endif
                    </li>

                    {{-- List --}}
                    <li>
                        <div style="max-height: 320px; overflow-y:auto;">
                            @forelse ($unread as $notif)
                                @php
                                    $type = class_basename($notif->type);
                                    $icon = match ($type) {
                                        'NewCustomerNotification' => 'bi-person-plus',
                                        default => 'bi-info-circle',
                                    };
                                @endphp

                                <a href="{{ route('notifications.read', $notif->id) }}"
                                   class="dropdown-item d-flex gap-3 align-items-start py-2">
                                    <div class="pt-1">
                                        <i class="bi {{ $icon }} fs-4 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-semibold text-truncate">
                                            {{ $notif->data['title'] ?? 'Notifikasi' }}
                                        </div>
                                        <div class="small text-muted text-wrap">
                                            {{ $notif->data['message'] ?? '-' }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-3 py-3 text-center text-muted small">
                                    <i class="bi bi-bell-slash fs-4 d-block mb-1"></i>
                                    Tidak ada notifikasi
                                </div>
                            @endforelse
                        </div>
                    </li>
                </ul>
            </div>

            {{-- USER DROPDOWN --}}
            <div class="dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
                   href="#" data-bs-toggle="dropdown" aria-expanded="false">

                    {{-- Avatar huruf depan nama --}}
                    <div
                        class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2"
                        style="width: 32px; height: 32px;">
                        <span class="small fw-semibold">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </span>
                    </div>

                    <div class="d-none d-md-block text-start">
                        <div class="fw-semibold">
                            {{ Auth::user()->name ?? 'User' }}
                        </div>
                        <small class="text-muted text-capitalize">
                            {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                        </small>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="{{ url('/profil') }}">
                            <i class="bi bi-person me-2"></i>Profil
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</header>
