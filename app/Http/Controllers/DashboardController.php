<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Perusahaan;
use App\Models\PipelineStage;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function superadmin()
    {
        $totalPerusahaan = Perusahaan::count();
        $totalCustomers  = Contact::count();
        $totalUsers      = User::count();
        $totalSuperAdmin = User::role('super-admin')->count();
        $totalAdmin      = User::role('admin')->count();
        $totalLeadOps    = User::role('lead-operations')->count();

        $canSeeCustomers = Auth::user()?->hasAnyRole(['admin', 'lead-operations']);
        $recentCustomers = $canSeeCustomers
            ? Contact::with(['company'])->latest()->take(10)->get()
            : collect();

        $activeAdmins   = User::role('admin')->where('is_active', true)->count();
        $inactiveAdmins = User::role('admin')->where('is_active', false)->count();

        $recentCompanies = Perusahaan::latest()->take(8)->get();
        $recentAdmins    = User::role('admin')->latest()->take(8)->get();
        $companiesMap    = Perusahaan::pluck('name', 'id');

        $period = CarbonPeriod::create(
            Carbon::now()->startOfYear()->startOfMonth(),
            '1 month',
            Carbon::now()->startOfMonth(),
        );
        $monthlyCountsRaw = Perusahaan::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('created_at', [Carbon::now()->startOfYear()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');
        $companyMonthlyLabels = [];
        $companyMonthlyCounts = [];
        foreach ($period as $month) {
            $ym = $month->format('Y-m');
            $companyMonthlyLabels[] = $month->format('M Y');
            $companyMonthlyCounts[] = (int) ($monthlyCountsRaw[$ym] ?? 0);
        }

        $roleLabels = ['Super Admin', 'Admin', 'Lead Operations'];
        $roleCounts = [$totalSuperAdmin, $totalAdmin, $totalLeadOps];
        $roleTotal  = max(1, array_sum($roleCounts));

        $hour     = (int) now()->format('H');
        $greet    = $hour < 5 ? 'malam' : ($hour < 11 ? 'pagi' : ($hour < 15 ? 'siang' : ($hour < 19 ? 'sore' : 'malam')));
        $userName = Auth::user()->name ?? 'Super Admin';
        $icon     = $hour < 5 || $hour >= 19 ? 'moon-stars' : ($hour < 11 ? 'sunrise' : ($hour < 15 ? 'sun' : 'cloud-sun'));
        $today    = now()->translatedFormat('l, d F Y');

        $userRoles = Auth::user()?->getRoleNames()->toArray() ?? [];

        return view('superadmin.dashboard', compact(
            'totalPerusahaan', 'totalCustomers', 'totalUsers',
            'totalSuperAdmin', 'totalAdmin', 'totalLeadOps',
            'canSeeCustomers', 'recentCustomers',
            'activeAdmins', 'inactiveAdmins',
            'recentCompanies', 'recentAdmins', 'companiesMap',
            'companyMonthlyLabels', 'companyMonthlyCounts',
            'roleLabels', 'roleCounts', 'roleTotal',
            'greet', 'userName', 'icon', 'today', 'userRoles'
        ));
    }

    public function admin()
    {
        $companyId   = Auth::user()->company_id;
        $companyName = optional(Perusahaan::find($companyId))->name;

        $today     = now()->startOfDay();
        $tomorrow  = now()->addDay()->startOfDay();
        $start7    = now()->subDays(6)->startOfDay();
        $startMon  = now()->startOfMonth();
        $endMon    = now()->endOfMonth();

        $qCustomers = $this->queryFor(Contact::class);

        $hasStageIdOnCustomer = false;
        $hasNextFollowUp = $this->schemaHas('contacts', 'next_follow_up_at');
        $hasOwnerId = $this->schemaHas('contacts', 'created_by');

        $kpi = (clone $qCustomers)->selectRaw("
            COUNT(*) as customers_total,
            SUM(created_at >= ? AND created_at < ?) as leads_today,
            SUM(created_at >= ?) as leads_7days,
            SUM(created_at >= ? AND created_at <= ?) as leads_month
        ", [$today, $tomorrow, $start7, $startMon, $endMon])->first();

        $customersTotal = (int) ($kpi->customers_total ?? 0);
        $leadsToday     = (int) ($kpi->leads_today ?? 0);
        $leads7Days     = (int) ($kpi->leads_7days ?? 0);
        $leadsMonth     = (int) ($kpi->leads_month ?? 0);

        $overdueCount       = 0;
        $dueTodayCount      = 0;
        $overdueFollowUps   = collect();
        $dueTodayFollowUps  = collect();

        if ($hasNextFollowUp) {
            $fu = (clone $qCustomers)->selectRaw("
                SUM(next_follow_up_at IS NOT NULL AND next_follow_up_at < ?) as overdue,
                SUM(next_follow_up_at IS NOT NULL AND next_follow_up_at >= ? AND next_follow_up_at < ?) as due_today
            ", [$today, $today, $tomorrow])->first();

            $overdueCount  = (int) ($fu->overdue ?? 0);
            $dueTodayCount = (int) ($fu->due_today ?? 0);

            $cols = ['id','name','type','next_follow_up_at'];
            $overdueFollowUps = (clone $qCustomers)
                ->whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<', $today)
                ->orderBy('next_follow_up_at', 'asc')
                ->limit(8)
                ->get($cols);
            $dueTodayFollowUps = (clone $qCustomers)
                ->whereNotNull('next_follow_up_at')
                ->whereBetween('next_follow_up_at', [$today, $tomorrow])
                ->orderBy('next_follow_up_at', 'asc')
                ->limit(8)
                ->get($cols);
        }

        $hour      = (int) now()->format('H');
        $greet     = $hour < 5 ? 'malam' : ($hour < 11 ? 'pagi' : ($hour < 15 ? 'siang' : ($hour < 19 ? 'sore' : 'malam')));
        $userName  = Auth::user()->name ?? 'Admin';
        $todayText = now()->translatedFormat('l, d F Y');
        $icon      = $hour < 5 || $hour >= 19 ? 'moon-stars' : ($hour < 11 ? 'sunrise' : ($hour < 15 ? 'sun' : 'cloud-sun'));

        $recentCustomers = (clone $qCustomers)->latest()->take(8)->get(['id','name','type','created_at']);

        $dailyAgg = (clone $qCustomers)
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(created_at) d, COUNT(*) c')
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c','d');
        $period14    = CarbonPeriod::create(now()->subDays(13)->startOfDay(), now()->startOfDay());
        $chartLabels = [];
        $chartSeries = [];
        foreach ($period14 as $date) {
            $key = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $chartSeries[] = (int) ($dailyAgg[$key] ?? 0);
        }

        $sourceAgg     = (clone $qCustomers)
            ->selectRaw('COALESCE(NULLIF(type,""), "Unknown") as src, COUNT(*) as c')
            ->groupBy('src')
            ->orderByDesc('c')
            ->take(7)
            ->get();
        $sourceLabels  = $sourceAgg->pluck('src');
        $sourceSeries  = $sourceAgg->pluck('c');

        $pipelineSummary = DB::table('campaign_contacts as cc')
            ->join('campaigns as c', 'c.id', '=', 'cc.campaign_id')
            ->where('c.company_id', $companyId)
            ->selectRaw('COALESCE(NULLIF(cc.status,""), "Unknown") as status, COUNT(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();
        $stagesCount = $pipelineSummary->count();

        $teamPerf = collect();
        if ($hasOwnerId) {
            $teamPerf = DB::table('contacts as c')
                ->join('users as u', 'u.id', '=', 'c.created_by')
                ->where('c.company_id', $companyId)
                ->whereNotNull('c.created_by')
                ->selectRaw('u.name, COUNT(*) as leads')
                ->groupBy('u.name')
                ->orderByDesc('leads')
                ->limit(6)
                ->get();
        }

        $urlCreateLead = url('/contact/create');
        $urlDueToday   = url('/contact?filter=due_today');
        $urlOverdue    = url('/contact?filter=overdue');
        $urlPipeline   = url('/pipeline');

        return view('admin.dashboard', compact(
            'companyName',
            'hasNextFollowUp', 'dueTodayCount', 'overdueCount',
            'overdueFollowUps', 'dueTodayFollowUps',
            'greet', 'userName', 'todayText', 'icon',
            'leadsToday', 'leads7Days', 'leadsMonth', 'customersTotal',
            'stagesCount', 'pipelineSummary', 'hasOwnerId',
            'chartLabels', 'chartSeries',
            'recentCustomers',
            'sourceLabels', 'sourceSeries',
            'teamPerf',
            'urlCreateLead', 'urlDueToday', 'urlOverdue', 'urlPipeline'
        ));
    }

    public function leadOperations()
    {
        $companyId = Auth::user()->company_id;
        $userId    = Auth::id();

        $companyCustomers = $this->queryFor(Contact::class);
        $myAssigned       = $this->queryFor(Contact::class, ['restrict_to_owner' => true]);

        $totalCustomers   = $companyCustomers->count();
        $myAssignedCount  = $myAssigned->count();
        $stagesCount      = PipelineStage::where('company_id', $companyId)->count();
        $last7DaysCount   = Contact::where('company_id', $companyId)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $daily = (clone $myAssigned)
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) d, COUNT(*) c')
            ->groupBy('d')
            ->orderBy('d')
            ->get();
        $chartLabels = $daily->pluck('d')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartSeries = $daily->pluck('c');

        $myRecentCustomers = (clone $myAssigned)->latest()->take(8)->get(['id','name','type','created_at']);

        return view('lead-operations.dashboard', compact(
            'totalCustomers', 'myAssignedCount', 'stagesCount', 'last7DaysCount',
            'chartLabels', 'chartSeries', 'myRecentCustomers'
        ));
    }
}
