<header class="mb-3 border-bottom py-2">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-4">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <div>
                <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
                <small class="text-muted d-none d-md-inline">Admin: {{ $adminName ?? (Auth::user()->name ?? 'User') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-light position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">Notifikasi</li>
                    <li><span class="dropdown-item-text">Tidak ada notifikasi</span></li>
                </ul>
            </div>
            <div class="dropdown">
                <a class="d-flex align-items-center dropdown-toggle text-decoration-none ms-2" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>
                    <div class="d-none d-md-block">
                        <div class="fw-semibold">{{ Auth::user()->name ?? 'User' }}</div>
                        <small class="text-muted">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ url('/profil') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
