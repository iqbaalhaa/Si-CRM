@extends('layouts.master')

@push('styles')
<style>
    :root {
        --primary-500: #4f46e5;
        --primary-600: #4338ca;
        --secondary-500: #0ea5e9;
        --success-500: #10b981;
        --danger-500: #ef4444;
        --warning-500: #f59e0b;
        --surface: #ffffff;
        --surface-soft: #f9fafb;
        --text-muted: #6b7280;
        --border: #e5e7eb;
    }

    .page-heading h3 {
        font-weight: 700;
        letter-spacing: .2px;
    }

    /* HERO CARD */
    .hero-card {
        color: #fff;
        background: radial-gradient(circle at top left, #a855f7 0, #4f46e5 35%, #0ea5e9 100%);
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .35);
        position: relative;
        overflow: hidden;
    }

    .hero-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
                radial-gradient(circle at 10% 20%, rgba(255,255,255,.18) 0, transparent 55%),
                radial-gradient(circle at 80% 0%, rgba(56,189,248,.16) 0, transparent 45%);
        opacity: .9;
        pointer-events: none;
    }

    .hero-card .card-body {
        padding: 1.6rem 1.75rem;
        position: relative;
        z-index: 1;
    }

    .hero-card .small {
        color: rgba(255,255,255,.85);
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .2rem .7rem;
        border-radius: 999px;
        background-color: rgba(15,23,42,.2);
        font-size: .75rem;
        backdrop-filter: blur(10px);
    }

    .chip-dot {
        width: .5rem;
        height: .5rem;
        border-radius: 999px;
        background: #22c55e;
    }

    /* STAT CARDS (TOP STATS) */
    .stat-card {
        position: relative;
        border-radius: 16px;
        background: radial-gradient(circle at top left, rgba(79,70,229,.06) 0, transparent 55%) var(--surface);
        border: 1px solid rgba(148,163,184,.35);
        box-shadow: 0 12px 30px rgba(15,23,42,.08);
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        min-height: 125px;   /* FIX HEIGHT */
        height: 100%;        /* Biar sejajar di satu row */
    }

    .stat-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(79,70,229,.18), rgba(14,165,233,.05));
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px rgba(15,23,42,.14);
        border-color: rgba(79,70,229,.45);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card .card-body {
        position: relative;
        z-index: 1;
        padding: 1rem 1.1rem;
    }

    .stat-label {
        font-size: .8rem;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: .15rem;
    }

    .stat-value {
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1.1;
    }

    .stat-sub {
        font-size: .78rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .stat-delta {
        font-size: .78rem;
        font-weight: 600;
        padding: .1rem .5rem;
        border-radius: 999px;
        background: rgba(34,197,94,.12);
        color: #16a34a;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, rgba(255,255,255,.35) 0, transparent 55%);
        border: 1px solid rgba(148,163,184,.45);
    }

    .stat-icon i {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 1.3rem;
    }

    /* CARD UMUM & TABLES */
    .card {
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 25px rgba(15,23,42,.04);
    }

    .card-header {
        border-bottom: 1px solid var(--border);
        font-weight: 600;
        font-size: .9rem;
        padding: .75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header .sub-text {
        font-size: .78rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    .card-body {
        padding: .95rem 1rem;
    }

    .section-title {
        font-size: .85rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: .4rem;
    }

    .table thead th {
        background: #f9fafb;
        border-bottom-color: var(--border);
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #6b7280;
    }

    .table tbody td {
        vertical-align: middle;
        font-size: .84rem;
    }

    .table tbody tr:hover {
        background: #f3f4f6;
    }

    .badge {
        border-radius: 999px;
    }

    .badge-soft {
        background-color: #e5e7eb;
        color: #374151;
    }

    /* ROLE SUMMARY */
    .role-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5rem;
        padding: .35rem 0;
        font-size: .83rem;
    }

    .role-label {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-weight: 500;
    }

    .role-dot {
        width: .6rem;
        height: .6rem;
        border-radius: 999px;
    }

    .role-bar {
        flex: 1;
        margin: 0 .6rem;
        height: .4rem;
        border-radius: 999px;
        background: #e5e7eb;
        overflow: hidden;
    }

    .role-bar-fill {
        height: 100%;
        border-radius: inherit;
    }

    .role-superadmin { background: #4f46e5; }
    .role-admin { background: #0ea5e9; }
    .role-marketing { background: #f59e0b; }
    .role-cs { background: #10b981; }

    .page-content {
        padding-top: .4rem;
    }

    .chart-card .card-body {
        height: clamp(280px, 34vh, 480px);
    }

    .chart-container {
        width: 100%;
        height: 100%;
        min-height: 280px;
    }
    .chart-container {
        width: 100%;
        min-height: 300px;
    }

    @media (max-width: 991.98px) {
        .hero-card .card-body {
            padding: 1.2rem 1.3rem;
        }
    }
</style>
@endpush

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading mb-2">
        <h3>Dashboard</h3>
    </div>

    @php
        use App\Models\Perusahaan;
        use App\Models\Customer;
        use App\Models\User;
        use Illuminate\Support\Facades\Auth;
        use Carbon\Carbon;
        use Carbon\CarbonPeriod;

        $totalPerusahaan = Perusahaan::count();
        $totalCustomers  = Customer::count();
        $totalUsers      = User::count();
        $totalSuperAdmin = User::role('super-admin')->count();
        $totalAdmin      = User::role('admin')->count();
        $totalMarketing  = User::role('marketing')->count();
        $totalCs         = User::role('cs')->count();

        $canSeeCustomers = Auth::user()?->hasAnyRole(['admin','marketing','cs']);
        $recentCustomers = $canSeeCustomers ? Customer::with(['company','stage'])->latest()->take(10)->get() : collect();

        $activeAdmins   = User::role('admin')->where('is_active', true)->count();
        $inactiveAdmins = User::role('admin')->where('is_active', false)->count();

        $recentCompanies = Perusahaan::latest()->take(8)->get();
        $recentAdmins    = User::role('admin')->latest()->take(8)->get();
        $companiesMap    = Perusahaan::pluck('name', 'id');

        // Perusahaan per bulan (tahun berjalan)
        $period = CarbonPeriod::create(Carbon::now()->startOfYear()->startOfMonth(), '1 month', Carbon::now()->startOfMonth());
        $monthlyCountsRaw = Perusahaan::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('created_at', [Carbon::now()->startOfYear()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('ym')->orderBy('ym')->pluck('total', 'ym');
        $companyMonthlyLabels = [];
        $companyMonthlyCounts = [];
        foreach ($period as $month) {
            $ym = $month->format('Y-m');
            $companyMonthlyLabels[] = $month->format('M Y');
            $companyMonthlyCounts[] = (int)($monthlyCountsRaw[$ym] ?? 0);
        }

        // Role distribution
        $roleLabels = ['Super Admin', 'Admin', 'Marketing', 'CS'];
        $roleCounts = [$totalSuperAdmin, $totalAdmin, $totalMarketing, $totalCs];
        $roleTotal  = max(1, array_sum($roleCounts));

        $hour    = (int) now()->format('H');
        $greet   = $hour < 5 ? 'malam' : ($hour < 11 ? 'pagi' : ($hour < 15 ? 'siang' : ($hour < 19 ? 'sore' : 'malam')));
        $userName = Auth::user()->name ?? 'Super Admin';
        $icon    = $hour < 5 || $hour >= 19 ? 'moon-stars' : ($hour < 11 ? 'sunrise' : ($hour < 15 ? 'sun' : 'cloud-sun'));
        $today   = now()->translatedFormat('l, d F Y');

        $userRoles = Auth::user()?->getRoleNames()->toArray() ?? [];
    @endphp

    <div class="page-content">
        {{-- HERO GREETING --}}
        <div class="card hero-card border-0 mb-3">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex flex-column gap-1">
                    <div class="chip mb-1">
                        <span class="chip-dot"></span>
                        <span>CRM Super Admin Area</span>
                    </div>
                    <div class="small opacity-75">Halo, {{ $userName }}</div>
                    <div class="fs-4 fw-semibold">Selamat {{ $greet }}</div>
                    <div class="small opacity-75 d-flex flex-wrap gap-2 align-items-center">
                        <span>{{ $today }}</span>
                        @if($userRoles)
                            <span>&middot;</span>
                            <span>Role:
                                @foreach($userRoles as $idx => $role)
                                    <span class="badge badge-soft bg-white bg-opacity-10 text-white border-0">
                                        {{ ucfirst($role) }}
                                    </span>
                                @endforeach
                            </span>
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="stat-icon mb-1">
                        <i class="bi bi-{{ $icon }}"></i>
                    </div>
                    <div class="small opacity-75 text-end">
                        Pantau performa perusahaan, user, dan aktivitas CRM di satu tempat.
                    </div>
                </div>
            </div>
        </div>

        <section class="row g-3">
            <div class="col-12">
                {{-- TOP STATS (FULL WIDTH, CARD SEJAJAR) --}}
                <div class="row g-3 mb-1">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="stat-label">Perusahaan</div>
                                    <div class="stat-value">{{ $totalPerusahaan }}</div>
                                    <div class="stat-sub">
                                        <span>Total perusahaan terdaftar</span>
                                    </div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="stat-label">Customers</div>
                                    <div class="stat-value">{{ $totalCustomers }}</div>
                                    <div class="stat-sub">
                                        <span>Data customer di sistem</span>
                                    </div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="stat-label">Pengguna Sistem</div>
                                    <div class="stat-value">{{ $totalUsers }}</div>
                                    <div class="stat-sub">
                                        <span>Semua role di CRM</span>
                                    </div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="stat-label">Admin</div>
                                    <div class="stat-value">{{ $activeAdmins }}</div>
                                    <div class="stat-sub">
                                        <span class="me-1">Aktif</span>
                                        <span class="stat-delta">Nonaktif: {{ $inactiveAdmins }}</span>
                                    </div>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-person-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ROW: CHART PERUSAHAAN & USER PER ROLE (SEBELAH-SEBELAH) --}}
                <div class="row g-3 mt-1">
                    <div class="col-12 col-lg-7">
                        <div class="card h-100 chart-card">
                            <div class="card-header">
                                <span>Pendaftaran Perusahaan per Bulan</span>
                                <span class="sub-text">Tahun {{ now()->year }}</span>
                            </div>
                            <div class="card-body">
                                <div id="companyMonthlyChart" class="chart-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="card h-100">
                            <div class="card-header">
                                <span>Ringkasan Role User</span>
                                <span class="sub-text">Distribusi role sistem</span>
                            </div>
                            <div class="card-body">
                                <div id="userRoleChart" style="min-height:240px;"></div>
                                <hr class="my-2">
                                <div class="section-title">Detail</div>

                                <div class="role-row">
                                    <div class="role-label">
                                        <span class="role-dot role-superadmin"></span>
                                        <span>Super Admin</span>
                                    </div>
                                    <div class="role-bar">
                                        <div class="role-bar-fill role-superadmin" style="width: {{ $totalSuperAdmin / $roleTotal * 100 }}%"></div>
                                    </div>
                                    <div class="fw-semibold">{{ $totalSuperAdmin }}</div>
                                </div>

                                <div class="role-row">
                                    <div class="role-label">
                                        <span class="role-dot role-admin"></span>
                                        <span>Admin</span>
                                    </div>
                                    <div class="role-bar">
                                        <div class="role-bar-fill role-admin" style="width: {{ $totalAdmin / $roleTotal * 100 }}%"></div>
                                    </div>
                                    <div class="fw-semibold">{{ $totalAdmin }}</div>
                                </div>

                                <div class="role-row">
                                    <div class="role-label">
                                        <span class="role-dot role-marketing"></span>
                                        <span>Marketing</span>
                                    </div>
                                    <div class="role-bar">
                                        <div class="role-bar-fill role-marketing" style="width: {{ $totalMarketing / $roleTotal * 100 }}%"></div>
                                    </div>
                                    <div class="fw-semibold">{{ $totalMarketing }}</div>
                                </div>

                                <div class="role-row">
                                    <div class="role-label">
                                        <span class="role-dot role-cs"></span>
                                        <span>CS</span>
                                    </div>
                                    <div class="role-bar">
                                        <div class="role-bar-fill role-cs" style="width: {{ $totalCs / $roleTotal * 100 }}%"></div>
                                    </div>
                                    <div class="fw-semibold">{{ $totalCs }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CUSTOMERS TERBARU (FULL WIDTH DI BAWAHNYA) --}}
                @if($canSeeCustomers)
                    <div class="row g-3 mt-1">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <span>Customers Terbaru</span>
                                    <span class="sub-text">10 data terakhir</span>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="recent-customers">
                                            <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Perusahaan</th>
                                                <th>Stage</th>
                                                <th>Email</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($recentCustomers as $c)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $c->name }}</td>
                                                    <td>{{ optional($c->company)->name ?? '-' }}</td>
                                                    <td>{{ optional($c->stage)->name ?? '-' }}</td>
                                                    <td>{{ $c->email }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Belum ada data</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TABLES: PERUSAHAAN & ADMIN TERBARU --}}
                <div class="row g-3 mt-1">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <span>Perusahaan Terbaru</span>
                                <span class="sub-text">8 data terakhir</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="recent-companies">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kode</th>
                                            <th>Email</th>
                                            <th>Tanggal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($recentCompanies as $p)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $p->name }}</td>
                                                <td>{{ $p->code }}</td>
                                                <td>{{ $p->email }}</td>
                                                <td>{{ $p->created_at?->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada data</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <span>Admin Perusahaan Terbaru</span>
                                <span class="sub-text">8 data terakhir</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="recent-admins">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Perusahaan</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($recentAdmins as $u)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $u->name }}</td>
                                                <td>{{ $companiesMap[$u->company_id] ?? '-' }}</td>
                                                <td>{{ $u->email }}</td>
                                                <td>
                                                    <span class="badge {{ $u->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada data</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/static/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        // CHART: PERUSAHAAN PER BULAN
        const companyChartEl = document.querySelector('#companyMonthlyChart');
        if (companyChartEl) {
            const labels = @json($companyMonthlyLabels);
            const data   = @json($companyMonthlyCounts);
            const chart = new ApexCharts(companyChartEl, {
                chart: {
                    type: 'area',
                    height: '100%',
                    toolbar: { show: false },
                    parentHeightOffset: 0,
                    fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif'
                },
                series: [{ name: 'Perusahaan', data: data }],
                xaxis: {
                    categories: labels,
                    labels: { rotate: -45 }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) { return Math.round(val); }
                    },
                    min: 0,
                    forceNiceScale: true
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.7,
                        opacityFrom: 0.4,
                        opacityTo: 0.05
                    }
                },
                colors: ['#4f46e5'],
                grid: { strokeDashArray: 4 },
                tooltip: {
                    y: { formatter: function(val){ return val + ' perusahaan'; } }
                }
            });
            chart.render();
        }

        // CHART: DISTRIBUSI ROLE USER
        const roleChartEl = document.querySelector('#userRoleChart');
        if (roleChartEl) {
            const roleLabels = @json($roleLabels);
            const roleCounts = @json($roleCounts);
            const roleChart = new ApexCharts(roleChartEl, {
                chart: {
                    type: 'donut',
                    height: 260,
                    toolbar: { show: false }
                },
                labels: roleLabels,
                series: roleCounts,
                colors: ['#4f46e5', '#0ea5e9', '#f59e0b', '#10b981'],
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '12px' },
                                value: {
                                    show: true,
                                    fontSize: '16px',
                                    formatter: function (val) { return parseInt(val); }
                                },
                                total: {
                                    show: true,
                                    label: 'Total User',
                                    formatter: function () {
                                        return {{ $totalUsers }};
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + ' user';
                        }
                    }
                }
            });
            roleChart.render();
        }

        // DATATABLES
        $(function () {
            var datatableOpts = {
                pageLength: 5,
                lengthMenu: [5, 10, 25],
                order: [[0, 'asc']],
                dom: '<"row align-items-center mb-2"<"col-sm-6"l><"col-sm-6"f>>t<"row align-items-center mt-2"<"col-sm-6"i><"col-sm-6"p>>',
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampil _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
                }
            };

            if ($('#recent-customers').length) {
                $('#recent-customers').DataTable(datatableOpts);
            }
            if ($('#recent-companies').length) {
                $('#recent-companies').DataTable(datatableOpts);
            }
            if ($('#recent-admins').length) {
                $('#recent-admins').DataTable(datatableOpts);
            }
        });
    </script>
@endpush
