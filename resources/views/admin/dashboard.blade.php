@extends('layouts.master')

@push('styles')
<style>
    :root{
        --primary-500:#4f46e5;
        --primary-600:#4338ca;
        --secondary-500:#0ea5e9;
        --success-500:#10b981;
        --danger-500:#ef4444;
        --warning-500:#f59e0b;
        --surface:#ffffff;
        --surface-soft:#f9fafb;
        --text-muted:#6b7280;
        --border:#e5e7eb;
    }

    .page-heading h3{
        font-weight:700;
        letter-spacing:.2px;
    }
    .page-content{ padding-top:.4rem; }

    /* HERO CARD */
    .hero-card{
        color:#fff;
        background:radial-gradient(circle at top left,#a855f7 0,#4f46e5 35%,#0ea5e9 100%);
        border-radius:18px;
        box-shadow:0 18px 40px rgba(15,23,42,.35);
        position:relative;
        overflow:hidden;
    }
    .hero-card::before{
        content:"";
        position:absolute;
        inset:0;
        background-image:
            radial-gradient(circle at 10% 20%,rgba(255,255,255,.18) 0,transparent 55%),
            radial-gradient(circle at 80% 0%,rgba(56,189,248,.16) 0,transparent 45%);
        opacity:.9;
        pointer-events:none;
    }
    .hero-card .card-body{
        padding:1.6rem 1.75rem;
        position:relative;
        z-index:1;
    }
    .hero-card .small{ color:rgba(255,255,255,.85); }

    .chip{
        display:inline-flex;
        align-items:center;
        gap:.35rem;
        padding:.2rem .7rem;
        border-radius:999px;
        background-color:rgba(15,23,42,.2);
        font-size:.75rem;
        backdrop-filter:blur(10px);
    }
    .chip-dot{
        width:.5rem;height:.5rem;border-radius:999px;
        background:#22c55e;
    }

    /* CARD UMUM */
    .card{
        border-radius:16px;
        border:1px solid var(--border);
        box-shadow:0 10px 25px rgba(15,23,42,.04);
    }
    .card-header{
        border-bottom:1px solid var(--border);
        font-weight:600;
        font-size:.9rem;
        padding:.75rem 1rem;
        display:flex;
        align-items:center;
        justify-content:space-between;
    }
    .card-body{ padding:.95rem 1rem; }

    .mini-muted{
        color:var(--text-muted);
        font-size:.85rem;
    }

    /* KPI Typography */
    .kpi-label{
        font-size:.8rem;
        font-weight:500;
        color:var(--text-muted);
        text-transform:uppercase;
        letter-spacing:.08em;
        margin-bottom:.15rem;
    }
    .kpi-value{
        font-size:1.55rem;
        font-weight:800;
        line-height:1.1;
    }
    .kpi-sub{
        font-size:.78rem;
        color:var(--text-muted);
        display:flex;
        align-items:center;
        gap:.3rem;
    }

    /* TABLES */
    .table thead th{
        background:transparent;
        border-bottom-color:var(--border);
        font-size:.78rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#6b7280;
    }
    .table tbody td{
        vertical-align:middle;
        font-size:.84rem;
    }
    .table tbody tr:hover{
        background:rgba(148,163,184,.08);
    }
    .badge{ border-radius:999px; }

    /* Charts */
    .chart-card .card-body{
        height:clamp(280px, 34vh, 480px);
    }
    .chart-container{
        width:100%;
        height:100%;
        min-height:280px;
    }
    .side-stats{ padding:.2rem .3rem; }
    .side-stats .list-group-item{ padding:.45rem .6rem; }
    .side-stats .badge{ min-width:2.5rem; }

    /* Equal height helper */
    .card-equal{ display:flex; flex-direction:column; }
    .card-equal .card-body{ flex:1; height:clamp(280px, 34vh, 480px); }
    .card-equal .table-responsive{ max-height:100%; }

    /* Nav pills small */
    .nav-pills.nav-sm .nav-link{
        padding:.35rem .6rem;
        font-size:.85rem;
        border-radius:999px;
    }
    .nav-pills .nav-link{ background:rgba(148,163,184,.10); }
    .nav-pills .nav-link.active{
        background:rgba(99,102,241,.18);
        color:#111827;
    }

    /* Empty state */
    .empty-box{
        border:1px dashed rgba(148,163,184,.45);
        border-radius:12px;
        padding:1rem;
        background:rgba(148,163,184,.08);
    }

    @media (max-width: 991.98px){
        .hero-card .card-body{ padding:1.2rem 1.3rem; }
    }

    .stat-card{
        position:relative;
        border-radius:16px;
        background:radial-gradient(circle at top left, rgba(79,70,229,.06) 0, transparent 55%) var(--surface);
        border:1px solid rgba(148,163,184,.35);
        box-shadow:0 12px 30px rgba(15,23,42,.08);
        overflow:hidden;
        min-height:125px;
        transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .stat-card::before{
        content:"";
        position:absolute;
        inset:0;
        background:linear-gradient(135deg, rgba(79,70,229,.18), rgba(14,165,233,.05));
        opacity:0;
        pointer-events:none;
        transition:opacity .18s ease;
    }
    .stat-card:hover{
        transform:translateY(-2px);
        box-shadow:0 18px 40px rgba(15,23,42,.14);
        border-color:rgba(79,70,229,.45);
    }
    .stat-card:hover::before{ opacity:1; }
    .stat-card .card-body{
        position:relative;
        z-index:1;
        padding:1rem 1.1rem;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:1rem;
    }
    .stat-icon{
        width:42px;height:42px;border-radius:14px;
        display:inline-flex;align-items:center;justify-content:center;
        background:radial-gradient(circle at top left, rgba(255,255,255,.35) 0, transparent 55%);
        border:1px solid rgba(148,163,184,.45);
    }
    .stat-icon i{
        background:linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        -webkit-background-clip:text;background-clip:text;color:transparent;
        font-size:1.15rem;
        line-height:1;
    }
    .stat-label{
        font-size:.8rem;font-weight:500;color:var(--text-muted);
        text-transform:uppercase;letter-spacing:.08em;margin-bottom:.15rem;
    }
    .stat-value{ font-size:1.55rem;font-weight:800;line-height:1.1; }
    .stat-sub{ font-size:.78rem;color:var(--text-muted);display:flex;align-items:center;gap:.3rem; }


    /* overdue card tetap tegas */
    .page-content .row.g-3.align-items-stretch .card.h-100.border-danger{
        border-color: rgba(239,68,68,.55) !important;
    }
    .page-content .row.g-3.align-items-stretch .card.h-100.border-danger:hover{
        border-color: rgba(239,68,68,.8) !important;
    }

    @media (max-width: 575.98px){
        .page-content .row.g-3.align-items-stretch .card.h-100{ min-height: 110px; }
        .page-content .row.g-3.align-items-stretch .kpi-value{ font-size: 1.45rem; }
    }
</style>
@endpush


@section('title', 'Dashboard')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Dashboard Admin Perusahaan</h3>
        <div class="text-muted">{{ $companyName ?? 'Perusahaan' }}</div>
    </div>
    <div class="text-muted">{{ now()->format('d M Y') }}</div>
</div>

<div class="page-content">

    {{-- HERO / COMMAND BAR --}}
    <div class="card hero-card border-0 mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h4 class="mb-0">Selamat {{ $greet }}, {{ $userName }}</h4>
                        <span class="chip"><span class="chip-dot"></span>{{ $todayText }}</span>
                        <span class="chip"><span class="chip-dot"></span>{{ $companyName ?? 'Perusahaan' }}</span>
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <span class="chip"><span class="chip-dot"></span>Follow Up Hari Ini: {{ $hasNextFollowUp ? number_format($dueTodayCount) : '—' }}</span>
                        <span class="chip"><span class="chip-dot"></span>Overdue: {{ $hasNextFollowUp ? number_format($overdueCount) : '—' }}</span>
                    </div>

                    {{-- QUICK ACTIONS --}}
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <a href="{{ $urlCreateLead }}" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Lead
                        </a>
                        <a href="{{ $urlDueToday }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-calendar2-check me-1"></i> Follow Up Hari Ini
                        </a>
                        <a href="{{ $urlOverdue }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-exclamation-triangle me-1"></i> Overdue
                        </a>
                        <a href="{{ $urlPipeline }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-diagram-3 me-1"></i> Pipeline
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="rounded-circle bg-white bg-opacity-10 p-3">
                        <i class="bi bi-{{ $icon }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="row g-3">

        {{-- KPI CARDS --}}
        <div class="col-12">
            <div class="row g-3 align-items-stretch">

                {{-- Leads Today --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-label">Leads Hari Ini</div>
                                <div class="stat-value">{{ number_format($leadsToday) }}</div>
                            </div>
                            <div class="stat-icon"><i class="bi bi-calendar2-check"></i></div>
                        </div>
                    </div>
                </div>

                {{-- Leads 7 Days --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-label">Leads 7 Hari</div>
                                <div class="stat-value">{{ number_format($leads7Days) }}</div>
                            </div>
                            <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
                        </div>
                    </div>
                </div>

                {{-- Leads Month --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-label">Leads Bulan Ini</div>
                                <div class="stat-value">{{ number_format($leadsMonth) }}</div>
                            </div>
                            <div class="stat-icon"><i class="bi bi-calendar2-month"></i></div>
                        </div>
                    </div>
                </div>

                {{-- Customers Total --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-label">Total Customer</div>
                                <div class="stat-value">{{ number_format($customersTotal) }}</div>
                            </div>
                            <div class="stat-icon"><i class="bi bi-people"></i></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Campaign Snapshot --}}
        <div class="col-12">
            <div class="row g-3 align-items-stretch">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-label">Campaign Aktif</div>
                                <div class="stat-value">{{ number_format($campaignActiveCount ?? 0) }}</div>
                            </div>
                            <div class="stat-icon"><i class="bi bi-bullseye"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-label">Kontak di Campaign</div>
                                <div class="stat-value">{{ number_format($campaignActiveContacts ?? 0) }}</div>
                            </div>
                            <div class="stat-icon"><i class="bi bi-people"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-label">Closing Rate</div>
                                <div class="stat-value">{{ number_format($campaignActiveClosingRate ?? 0) }}%</div>
                            </div>
                            <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pipeline Snapshot --}}
        <div class="col-12 col-lg-6">
            <div class="card card-equal">
                <div class="card-header">
                    <span><i class="bi bi-diagram-3 me-1"></i> Ringkasan Pipeline</span>
                </div>
                <div class="card-body">
                    @if(empty($pipelineSummary) || $pipelineSummary->isEmpty())
                        <div class="empty-box">
                            <div class="fw-semibold mb-1">Belum ada data pipeline</div>
                            <div class="mini-muted mb-2">Mulai hubungkan kontak ke campaign untuk memantau status.</div>
                            <a href="{{ $urlPipeline }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-diagram-3 me-1"></i> Buka Pipeline
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pipelineSummary as $row)
                                        <tr>
                                            @php
                                                $s = strtolower($row->status ?? '');
                                                $color = match ($s) {
                                                    'pending' => 'secondary',
                                                    'contacted' => 'info',
                                                    'new' => 'primary',
                                                    'converted' => 'success',
                                                    'rejected' => 'danger',
                                                    'followup', 'follow up' => 'warning',
                                                    default => 'dark',
                                                };
                                            @endphp
                                            <td class="fw-semibold"><i class="bi bi-dot text-{{ $color }} me-1"></i>{{ $row->status }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-light text-dark border">{{ number_format($row->total) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Leads chart + Team --}}
        <div class="col-12 col-lg-6">
            <div class="card chart-card card-equal">
                <div class="card-header">
                    <span><i class="bi bi-graph-up-arrow me-1"></i> Tren Leads (14 Hari)</span>
                </div>
                <div class="card-body">
                    <div id="chart-leads" class="chart-container"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-equal">
                <div class="card-header">
                    <span><i class="bi bi-people me-1"></i> Performa Tim</span>
                </div>
                <div class="card-body">
                    @if(!$hasOwnerId)
                        <div class="empty-box">
                            <div class="fw-semibold mb-1">Belum bisa ranking performa</div>
                            <div class="mini-muted mb-2">Tambahkan kolom <code>owner_id</code> di <code>customers</code> untuk mapping lead ke tim.</div>
                            <a href="{{ url('/tim-role') }}" class="btn btn-primary btn-sm">Atur Tim</a>
                        </div>
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
                </div>
            </div>
        </div>

        {{-- Source + Recent --}}
        <div class="col-12 col-lg-6">
            <div class="card chart-card card-equal">
                <div class="card-header">
                    <span><i class="bi bi-pie-chart me-1"></i> Distribusi Jenis Kontak</span>
                </div>
                <div class="card-body">
                    <div class="row g-2 align-items-stretch">
                        <div class="col-12 col-md-7">
                            <div id="chart-source" class="chart-container"></div>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="side-stats">
                                @php
                                    $labels = is_array($sourceLabels ?? null) ? $sourceLabels : [];
                                    $series = is_array($sourceSeries ?? null) ? $sourceSeries : [];
                                @endphp
                                @if(!empty($labels))
                                    <ul class="list-group list-group-flush">
                                        @foreach($labels as $idx => $label)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $label }}</span>
                                                <span class="badge bg-light text-dark border">{{ number_format($series[$idx] ?? 0) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="mini-muted">Belum ada data sumber.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-clock-history me-1"></i> Aktivitas Terakhir</span>
                    <span class="mini-muted">Lead terbaru</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCustomers as $c)
                                    <tr>
                                        <td class="fw-semibold">{{ $c->name }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $c->type ?: 'Unknown' }}</span>
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
                    <div class="mt-2 mini-muted">Bisa diganti jadi “Last Activity” beneran kalau kamu punya tabel log aktivitas.</div>
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
    var srcOptions = {
        chart: { type: 'donut', height: '100%' },
        series: @json($sourceSeries),
        labels: @json($sourceLabels),
        legend: { position: 'bottom' },
        dataLabels: { enabled: false }
    };
    new ApexCharts(document.querySelector('#chart-source'), srcOptions).render();
})();
</script>
@endpush
