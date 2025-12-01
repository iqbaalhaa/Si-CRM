<header class="mb-3 border-bottom py-2">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-4">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <div>
                <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
                <small class="text-muted d-none d-md-inline">Admin:
                    {{ $adminName ?? (Auth::user()->name ?? 'User') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                @php
                    $unread = auth()->user()->unreadNotifications;
                    $count = $unread->count();
                @endphp

                <button type="button" class="position-relative border-0 bg-transparent p-0" data-bs-toggle="dropdown"
                    aria-expanded="false" style="box-shadow: none;">
                    <i class="bi {{ $count > 0 ? 'bi-bell-fill' : 'bi-bell' }} fs-5"></i>

                    @if ($count > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $count }}
                        </span>
                    @endif
                </button>


                <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 330px;">

                    <li class="dropdown-header d-flex justify-content-between align-items-center fw-semibold">
                        <span>Notifikasi</span>

                        @if ($count > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button class="btn btn-sm btn-link p-0 text-decoration-none">Tandai semua
                                    terbaca</button>
                            </form>
                        @endif
                    </li>

                    <li>
                        <hr>
                    </li>

                    @forelse ($unread as $notif)
                        @php
                            $type = class_basename($notif->type);

                            $icon = match ($type) {
                                'NewCustomerNotification' => 'bi-person-plus',
                                default => 'bi-info-circle',
                            };
                        @endphp

                        <li>
                            <a href="{{ route('notifications.read', $notif->id) }}"
                                class="dropdown-item d-flex gap-3 align-items-start py-2">


                                <i class="bi {{ $icon }} fs-4 text-primary"></i>

                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-wrap">{{ $notif->data['title'] }}</div>
                                    <div class="small text-muted text-wrap">{{ $notif->data['message'] }}</div>
                                    <div class="small text-muted">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>

                            </a>
                        </li>

                    @empty
                        <li>
                            <span class="dropdown-item-text text-muted">Tidak ada notifikasi</span>
                        </li>
                    @endforelse

                </ul>
            </div>


            <div class="dropdown">
                <a class="d-flex align-items-center dropdown-toggle text-decoration-none ms-2" href="#"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>
                    <div class="d-none d-md-block">
                        <div class="fw-semibold">{{ Auth::user()->name ?? 'User' }}</div>
                        <small class="text-muted">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ url('/profil') }}"><i
                                class="bi bi-person me-2"></i>Profil</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
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
