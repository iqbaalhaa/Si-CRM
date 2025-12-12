@extends('layouts.master')

@push('styles')
<style>
    :root{
        --border:#e5e7eb;
        --muted:#6b7280;
    }
    .page-heading h3{font-weight:700}
    .card{border-radius:16px;border:1px solid var(--border);box-shadow:0 10px 25px rgba(15,23,42,.04)}
    .card-header{border-bottom:1px solid var(--border);font-weight:600;font-size:.9rem;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between}
    .card-body{padding:1rem}
    .kpi-label{font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:.25rem}
    .kpi-value{font-size:1.6rem;font-weight:800;line-height:1}
    .kpi-sub{font-size:.8rem;color:var(--muted)}
    .mini-muted{color:var(--muted);font-size:.85rem}
    .table thead th{font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);border-bottom-color:var(--border)}
    .table tbody td{font-size:.88rem;vertical-align:middle}
    .table tbody tr:hover{background:rgba(148,163,184,.08)}
    .chart-card .card-body{height:clamp(260px,34vh,440px)}
    .chart-container{width:100%;height:100%;min-height:260px}
    .badge{border-radius:999px}
</style>
@endpush

@section('title', 'Dashboard')

@section('content')
@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    $companyId   = Auth::user()->company_id;
    $companyName = optional(\App\Models\Perusahaan::find($companyId))->name;

    $today       = now()->startOfDay();
    $tomorrow    = now()->addDay()->startOfDay();
    $start7      = now()->subDays(6)->startOfDay(); // 7 hari termasuk hari ini
    $start30     = now()->subDays(29)->startOfDay();
    $startMonth  = now()->startOfMonth();
    $endMonth    = now()->endOfMonth();

    $qCustomers = \App\Models\Customer::query()->where('company_id', $companyId);

    // --- KPI dasar (yang pasti ada) ---
    $customersTotal   = (clone $qCustomers)->count();
    $leadsToday       = (clone $qCustomers)->whereBetween('created_at', [$today, $tomorrow])->count();
    $leads7Days       = (clone $qCustomers)->where('created_at', '>=', $start7)->count();
    $leadsMonth       = (clone $qCustomers)->whereBetween('created_at', [$startMonth, $endMonth])->count();

    // Team count (role lead-operations)
    $hasUserCompanyId = Schema::hasColumn('users', 'company_id');
    $teamCount = $hasUserCompanyId
        ? \App\Models\User::where('company_id', $companyId)->whereHas('roles', fn($q)=>$q->where('name','lead-operations'))->count()
        : \App\Models\User::whereHas('roles', fn($q)=>$q->where('name','lead-operations'))->count();

    // Pipeline stages
    $stagesCount = \App\Models\PipelineStage::where('company_id', $companyId)->count();

    // --- Pipeline snapshot (opsional, kalau customers punya pipeline_stage_id) ---
    $hasStageIdOnCustomer = Schema::hasColumn('customers', 'pipeline_stage_id');
    $stageSummary = collect();
    if ($hasStageIdOnCustomer) {
        $stageSummary = \App\Models\PipelineStage::where('company_id', $companyId)
            ->orderBy('sort_order')
            ->get(['id','name'])
            ->map(function($s) use ($qCustomers){
                $count = (clone $qCustomers)->where('pipeline_stage_id', $s->id)->count();
                return (object)[ 'id'=>$s->id, 'name'=>$s->name, 'count'=>$count ];
            });
    }

    // --- Action Needed (opsional: next_follow_up_at + owner_id) ---
    $hasNextFollowUp = Schema::hasColumn('customers', 'next_follow_up_at');
    $hasOwnerId      = Schema::hasColumn('customers', 'owner_id');

    $overdueFollowUps = collect();
    $dueTodayFollowUps = collect();

    if ($hasNextFollowUp) {
        $overdueFollowUps = (clone $qCustomers)
            ->whereNotNull('next_follow_up_at')
            ->where('next_follow_up_at', '<', $today)
            ->latest('next_follow_up_at')
            ->take(5)
            ->get(['id','name','email','source','next_follow_up_at']);

        $dueTodayFollowUps = (clone $qCustomers)
            ->whereNotNull('next_follow_up_at')
            ->whereBetween('next_follow_up_at', [$today, $tomorrow])
            ->latest('next_follow_up_at')
            ->take(5)
            ->get(['id','name','email','source','next_follow_up_at']);
    }
    $overdueCount = $hasNextFollowUp
        ? (clone $qCustomers)->whereNotNull('next_follow_up_at')->where('next_follow_up_at', '<', $today)->count()
        : 0;
    $dueTodayCount = $hasNextFollowUp
        ? (clone $qCustomers)->whereNotNull('next_follow_up_at')->whereBetween('next_follow_up_at', [$today, $tomorrow])->count()
        : 0;

    // --- Recent customers ---
    $recentCustomers = (clone $qCustomers)->latest()->take(6)->get(['name','email','source','created_at']);

    // --- Leads chart 14 hari ---
    $daily = (clone $qCustomers)
        ->where('created_at', '>=', now()->subDays(13)->startOfDay())
        ->selectRaw('DATE(created_at) d, COUNT(*) c')
        ->groupBy('d')
        ->orderBy('d')
        ->get();

    $chartLabels = $daily->pluck('d')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
    $chartSeries = $daily->pluck('c');

    // --- Insight: sumber lead (donut) ---
    $sourceAgg = (clone $qCustomers)
        ->selectRaw('COALESCE(NULLIF(source,""), "Unknown") as src, COUNT(*) as c')
        ->groupBy('src')
        ->orderByDesc('c')
        ->take(7)
        ->get();

    $sourceLabels = $sourceAgg->pluck('src');
    $sourceSeries = $sourceAgg->pluck('c');

    // --- Campaign ringkas (opsional: tabel campaigns) ---
    $hasCampaignsTable = Schema::hasTable('campaigns');
    $campaignActiveCount = null;
    $campaignActiveList = collect();

    if ($hasCampaignsTable) {
        $campaignActiveCount = DB::table('campaigns')
            ->where('company_id', $companyId)
            ->where('is_active', 1)
            ->count();

        $campaignActiveList = DB::table('campaigns')
            ->where('company_id', $companyId)
            ->where('is_active', 1)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['name','created_at']);
    }

    // --- Performa tim (opsional: kalau customers punya owner_id) ---
    $teamPerf = collect();
    if ($hasOwnerId) {
        // leads handled per user (Top 6)
        $teamPerf = DB::table('customers')
            ->selectRaw('owner_id, COUNT(*) as leads')
            ->where('company_id', $companyId)
            ->whereNotNull('owner_id')
            ->groupBy('owner_id')
            ->orderByDesc('leads')
            ->limit(6)
            ->get()
            ->map(function($r){
                $u = \App\Models\User::find($r->owner_id);
                return (object)[
                    'name'  => $u?->name ?? 'User #'.$r->owner_id,
                    'leads' => (int) $r->leads,
                ];
            });
    }

    // --- KPI tambahan (kalau belum ada deal/won/lost, tampilkan placeholder) ---
    $conversionRate = null; // nanti bisa kamu isi jika sudah ada konsep won/lost
@endphp

<div class="page-heading d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Dashboard Admin Perusahaan</h3>
        <div class="text-muted">{{ $companyName ?? 'Perusahaan' }}</div>
    </div>
    <div class="text-muted">{{ now()->format('d M Y') }}</div>
</div>

<div class="page-content">
    <section class="row g-3">

        {{-- KPI CARDS --}}
        <div class="col-12">
            <div class="row g-3 align-items-stretch">
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="kpi-label">Leads Hari Ini</div>
                            <div class="kpi-value">{{ number_format($leadsToday) }}</div>
                            <div class="kpi-sub">Update real-time</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="kpi-label">Leads 7 Hari</div>
                            <div class="kpi-value">{{ number_format($leads7Days) }}</div>
                            <div class="kpi-sub">Termasuk hari ini</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="kpi-label">Leads Bulan Ini</div>
                            <div class="kpi-value">{{ number_format($leadsMonth) }}</div>
                            <div class="kpi-sub">{{ now()->format('M Y') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="kpi-label">Customers</div>
                            <div class="kpi-value">{{ number_format($customersTotal) }}</div>
                            <div class="kpi-sub">Total data</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="kpi-label">Follow Up Hari Ini</div>
                            <div class="kpi-value">{{ $hasNextFollowUp ? number_format($dueTodayCount) : '—' }}</div>
                            <div class="kpi-sub">Jadwal follow up</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="kpi-label">Follow Up Telat</div>
                            <div class="kpi-value">{{ $hasNextFollowUp ? number_format($overdueCount) : '—' }}</div>
                            <div class="kpi-sub">Butuh tindakan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW: Pipeline Snapshot + Action Needed --}}
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span>Pipeline Snapshot</span>
                    <span class="mini-muted">Stages: {{ number_format($stagesCount) }}</span>
                </div>
                <div class="card-body">
                    @if(!$hasStageIdOnCustomer)
                        <div class="alert alert-light border mb-0">
                            <div class="fw-semibold">Belum bisa tampilkan pipeline snapshot.</div>
                            <div class="mini-muted">Tambahkan kolom <code>pipeline_stage_id</code> di tabel <code>customers</code> (atau sesuaikan ke tabel deal/opportunity kamu).</div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Stage</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($stageSummary as $s)
                                    <tr>
                                        <td class="fw-semibold">{{ $s->name }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-light text-dark border">{{ number_format($s->count) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">Stage belum ada</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span>Action Needed</span>
                    <span class="mini-muted">Follow up</span>
                </div>
                <div class="card-body">
                    @if(!$hasNextFollowUp)
                        <div class="alert alert-light border mb-0">
                            <div class="fw-semibold">Belum ada pengingat follow up.</div>
                            <div class="mini-muted">Tambahkan kolom <code>next_follow_up_at</code> di tabel <code>customers</code> untuk fitur due/overdue.</div>
                        </div>
                    @else
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="fw-semibold mb-2">Overdue</div>
                                <ul class="list-group list-group-flush">
                                    @forelse($overdueFollowUps as $c)
                                        <li class="list-group-item px-0 d-flex justify-content-between">
                                            <div>
                                                <div class="fw-semibold">{{ $c->name }}</div>
                                                <div class="mini-muted">{{ $c->email ?? '-' }}</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-danger-subtle text-danger border">Telat</span>
                                                <div class="mini-muted">{{ \Carbon\Carbon::parse($c->next_follow_up_at)->format('d M') }}</div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item px-0 text-muted">Tidak ada</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="fw-semibold mb-2">Due Hari Ini</div>
                                <ul class="list-group list-group-flush">
                                    @forelse($dueTodayFollowUps as $c)
                                        <li class="list-group-item px-0 d-flex justify-content-between">
                                            <div>
                                                <div class="fw-semibold">{{ $c->name }}</div>
                                                <div class="mini-muted">{{ $c->email ?? '-' }}</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning-subtle text-warning border">Hari ini</span>
                                                <div class="mini-muted">{{ \Carbon\Carbon::parse($c->next_follow_up_at)->format('H:i') }}</div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item px-0 text-muted">Tidak ada</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ROW: Leads chart + Campaign / Team --}}
        <div class="col-12 col-lg-8">
            <div class="card chart-card">
                <div class="card-header">
                    <span>Leads (14 Hari)</span>
                    <span class="mini-muted">Trend harian</span>
                </div>
                <div class="card-body">
                    <div id="chart-leads" class="chart-container"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="row g-3">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Performa Tim</div>
                        <div class="card-body">
                            @if(!$hasOwnerId)
                                <div class="text-muted">Tambahkan kolom <code>owner_id</code> di <code>customers</code> untuk ranking performa.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Karyawan</th>
                                                <th class="text-end">Leads</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($teamPerf as $t)
                                                <tr>
                                                    <td class="fw-semibold">{{ $t->name }}</td>
                                                    <td class="text-end"><span class="badge bg-light text-dark border">{{ number_format($t->leads) }}</span></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="2" class="text-muted text-center">Belum ada data</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div class="mt-2 mini-muted">Tip: nanti bisa tambah Won/Lost + conversion per orang.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW: Insights + Aktivitas terakhir --}}
        <div class="col-12 col-lg-6">
            <div class="card chart-card">
                <div class="card-header">
                    <span>Insight Sumber Lead</span>
                    <span class="mini-muted">Top sumber</span>
                </div>
                <div class="card-body">
                    <div id="chart-source" class="chart-container"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Aktivitas Terakhir</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Source</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCustomers as $c)
                                    <tr>
                                        <td class="fw-semibold">{{ $c->name }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $c->source ?: 'Unknown' }}</span>
                                        </td>
                                        <td>{{ $c->created_at?->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted">Tidak ada data</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 mini-muted">Ini bisa diganti jadi “Last Activity” beneran kalau kamu punya tabel log aktivitas.</div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admindash/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script>
(function () {
    // Leads chart
    var leadOptions = {
        chart: { type: 'area', height: '100%', parentHeightOffset: 0, toolbar: { show: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        series: [{ name: 'Leads', data: @json($chartSeries) }],
        xaxis: { categories: @json($chartLabels) },
        grid: { strokeDashArray: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 0.4, opacityFrom: 0.5, opacityTo: 0.1 } }
    };
    new ApexCharts(document.querySelector('#chart-leads'), leadOptions).render();

    // Source donut
    var srcSeries = @json($sourceSeries);
    var srcLabels = @json($sourceLabels);

    var srcOptions = {
        chart: { type: 'donut', height: '100%' },
        series: srcSeries,
        labels: srcLabels,
        legend: { position: 'bottom' },
        dataLabels: { enabled: false }
    };
    new ApexCharts(document.querySelector('#chart-source'), srcOptions).render();
})();
</script>
@endpush
