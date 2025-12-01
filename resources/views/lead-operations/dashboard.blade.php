@extends('layouts.master')

@push('styles')
<style>
    :root { --primary-500:#4f46e5; --secondary-500:#0ea5e9; --surface:#ffffff; --text-muted:#6b7280; --border:#e5e7eb; }
    .page-heading h3{font-weight:700;letter-spacing:.2px}
    .card{border-radius:16px;border:1px solid var(--border);box-shadow:0 10px 25px rgba(15,23,42,.04)}
    .card-header{border-bottom:1px solid var(--border);font-weight:600;font-size:.9rem;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between}
    .card-body{padding:.95rem 1rem}
    .table thead th{background:#f9fafb;border-bottom-color:var(--border);font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280}
    .table tbody td{vertical-align:middle;font-size:.84rem}
    .table tbody tr:hover{background:#f3f4f6}
    .badge{border-radius:999px}
    .stat-label{font-size:.8rem;font-weight:500;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.15rem}
    .stat-value{font-size:1.55rem;font-weight:700;line-height:1.1}
    .stats-icon{width:42px;height:42px;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;background:#fff;border:1px solid rgba(148,163,184,.45)}
    .stats-icon i{background:linear-gradient(135deg,var(--primary-500),var(--secondary-500));-webkit-background-clip:text;background-clip:text;color:transparent;font-size:1.3rem}
    .chart-card .card-body{height:clamp(280px,34vh,480px)}
    .chart-container{width:100%;height:100%;min-height:280px}
</style>
@endpush

@section('title', 'Dashboard')

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
        use App\Models\Customer;
        use App\Models\PipelineStage;
        $companyId = Auth::user()->company_id;
        $userId = Auth::id();
        $companyCustomers = Customer::where('company_id', $companyId);
        $myAssigned = Customer::where('company_id', $companyId)->where('assigned_to_id', $userId);
        $totalCustomers = $companyCustomers->count();
        $myAssignedCount = $myAssigned->count();
        $stagesCount = PipelineStage::where('company_id', $companyId)->count();
        $last7DaysCount = Customer::where('company_id', $companyId)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        $daily = $myAssigned->clone()
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) d, COUNT(*) c')
            ->groupBy('d')
            ->orderBy('d')
            ->get();
        $chartLabels = $daily->pluck('d')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
        $chartSeries = $daily->pluck('c');
        $myRecentCustomers = $myAssigned->latest()->take(8)->get(['name','email','source','created_at']);
    @endphp

    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Dashboard Marketing</h3>
            <div class="text-muted">{{ Auth::user()->name }}</div>
        </div>
        <div class="text-muted">{{ now()->format('d M Y') }}</div>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-label">Leads Saya</div>
                                        <div class="stat-value">{{ number_format($myAssignedCount) }}</div>
                                    </div>
                                    <div class="stats-icon"><i class="bi bi-person-lines-fill"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-label">Leads 7 Hari</div>
                                        <div class="stat-value">{{ number_format($last7DaysCount) }}</div>
                                    </div>
                                    <div class="stats-icon"><i class="bi bi-graph-up"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-label">Customers</div>
                                        <div class="stat-value">{{ number_format($totalCustomers) }}</div>
                                    </div>
                                    <div class="stats-icon"><i class="bi bi-people"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-label">Stages</div>
                                        <div class="stat-value">{{ number_format($stagesCount) }}</div>
                                    </div>
                                    <div class="stats-icon"><i class="bi bi-kanban"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card chart-card">
                    <div class="card-header">Leads Saya 7 Hari</div>
                    <div class="card-body">
                        <div id="chart-leads" class="chart-container"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">Leads Terbaru</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myRecentCustomers as $c)
                                        <tr>
                                            <td>{{ $c->name }}</td>
                                            <td>{{ $c->email }}</td>
                                            <td>{{ $c->created_at?->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center">Tidak ada data</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        (function(){
            var options = {
                chart: { type: 'area', height: '100%', parentHeightOffset: 0, toolbar: { show: false }, fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif' },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                series: [{ name: 'Leads', data: @json($chartSeries) }],
                xaxis: { categories: @json($chartLabels) },
                colors: ['#ff9c00'],
                grid: { strokeDashArray: 3 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 0.4, opacityFrom: 0.5, opacityTo: 0.1 } }
            };
            var chart = new ApexCharts(document.querySelector('#chart-leads'), options);
            chart.render();
        })();
    </script>
@endpush
