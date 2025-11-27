@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    @php
        $companyId = Auth::user()->company_id;
        $companyName = optional(\App\Models\Perusahaan::find($companyId))->name;
        $customersCount = \App\Models\Customer::where('company_id', $companyId)->count();
        $teamCount = \App\Models\User::where('company_id', $companyId)->whereHas('roles', function($q){ $q->whereIn('name', ['marketing','cs']); })->count();
        $stagesCount = \App\Models\PipelineStage::where('company_id', $companyId)->count();
        $estimatedSum = \App\Models\Customer::where('company_id', $companyId)->sum('estimated_value');
        $recentCustomers = \App\Models\Customer::where('company_id', $companyId)->latest()->take(5)->get(['name','email','source','created_at']);
        $daily = \App\Models\Customer::where('company_id', $companyId)
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) d, COUNT(*) c')
            ->groupBy('d')
            ->orderBy('d')
            ->get();
        $chartLabels = $daily->pluck('d')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
        $chartSeries = $daily->pluck('c');
    @endphp

    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Dashboard Admin Perusahaan</h3>
            <div class="text-muted">{{ $companyName ?? 'Perusahaan' }}</div>
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
                                        <div class="text-muted">Customers</div>
                                        <div class="h4 mb-0">{{ number_format($customersCount) }}</div>
                                    </div>
                                    <div class="stats-icon blue"><i class="bi bi-people"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted">Team</div>
                                        <div class="h4 mb-0">{{ number_format($teamCount) }}</div>
                                    </div>
                                    <div class="stats-icon purple"><i class="bi bi-person-badge"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted">Stages</div>
                                        <div class="h4 mb-0">{{ number_format($stagesCount) }}</div>
                                    </div>
                                    <div class="stats-icon green"><i class="bi bi-kanban"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted">Est. Value</div>
                                        <div class="h4 mb-0">{{ number_format($estimatedSum, 0) }}</div>
                                    </div>
                                    <div class="stats-icon orange"><i class="bi bi-currency-dollar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">Leads 7 Hari</div>
                    <div class="card-body">
                        <div id="chart-leads" style="height:260px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">Aktivitas Terakhir</div>
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
                                    @forelse($recentCustomers as $c)
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
                chart: { type: 'area', height: 260, toolbar: { show: false } },
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
