@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>

    @php
        use App\Models\Perusahaan;
        use App\Models\Customer;
        use App\Models\User;
        $totalPerusahaan = Perusahaan::count();
        $totalCustomers = Customer::count();
        $totalUsers = User::count();
        $totalSuperAdmin = User::role('super-admin')->count();
        $totalAdmin = User::role('admin')->count();
        $totalMarketing = User::role('marketing')->count();
        $totalCs = User::role('cs')->count();
        $recentCustomers = Customer::with(['company','stage'])->latest()->take(10)->get();
    @endphp

    <div class="page-content">
        <section class="row g-3">
            <div class="col-12 col-xl-9">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Perusahaan</div>
                                    <div class="fs-4 fw-semibold">{{ $totalPerusahaan }}</div>
                                </div>
                                <i class="bi bi-building fs-2 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Customers</div>
                                    <div class="fs-4 fw-semibold">{{ $totalCustomers }}</div>
                                </div>
                                <i class="bi bi-people fs-2 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Pengguna</div>
                                    <div class="fs-4 fw-semibold">{{ $totalUsers }}</div>
                                </div>
                                <i class="bi bi-person-badge fs-2 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Roles</div>
                                    <div class="fs-6">SA {{ $totalSuperAdmin }} • A {{ $totalAdmin }} • M {{ $totalMarketing }} • CS {{ $totalCs }}</div>
                                </div>
                                <i class="bi bi-shield-lock fs-2 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">Distribusi Role Pengguna</div>
                            <div class="card-body">
                                <div id="roleChart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">Ringkasan Customers Terbaru</div>
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
            </div>

            <div class="col-12 col-xl-3">
                <div class="card">
                    <div class="card-header">Aksi Cepat</div>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('perusahaan.create') }}" class="btn btn-primary">Tambah Perusahaan</a>
                        <a href="{{ route('customers.create') }}" class="btn btn-secondary">Tambah Customer</a>
                        <a href="{{ route('perusahaan.index') }}" class="btn btn-outline-primary">Lihat Perusahaan</a>
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Lihat Customers</a>
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
        const roleChartEl = document.querySelector('#roleChart');
        if (roleChartEl) {
            const roleChart = new ApexCharts(roleChartEl, {
                chart: { type: 'donut', height: 300 },
                labels: ['Super Admin', 'Admin', 'Marketing', 'CS'],
                series: [{{ $totalSuperAdmin }}, {{ $totalAdmin }}, {{ $totalMarketing }}, {{ $totalCs }}],
                colors: ['#ff9c00', '#03a6e5', '#00c9a7', '#8e44ad'],
                legend: { position: 'bottom' }
            });
            roleChart.render();
        }

        $(function(){
            $('#recent-customers').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25],
                order: [[0, 'asc']]
            });
        });
    </script>
@endpush
