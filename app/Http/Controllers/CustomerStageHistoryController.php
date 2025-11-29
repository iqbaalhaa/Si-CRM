<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerStageHistory;
use Illuminate\Http\Request;

class CustomerStageHistoryController extends Controller
{
    /**
     * Halaman index: ringkasan stage + daftar customer dan stage sekarang.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        // Base query: semua customer milik company user
        $query = Customer::with(['company', 'stage', 'assignedTo'])
            ->where('company_id', $user->company_id);

        // Kalau role marketing / cs → hanya lihat customer yang di-assign ke dia
        if ($user->hasRole(['marketing', 'cs'])) {
            $query->where('assigned_to_id', $user->id);
        }

        $customers = $query->latest()->get();

        // Hitung jumlah per stage (berdasarkan nama stage) dari hasil query di atas
        $stageCounts = [
            'New'       => $customers->filter(fn ($c) => optional($c->stage)->name === 'New')->count(),
            'Contact'   => $customers->filter(fn ($c) => optional($c->stage)->name === 'Contact')->count(),
            'Hold'      => $customers->filter(fn ($c) => optional($c->stage)->name === 'Hold')->count(),
            'No Respon' => $customers->filter(fn ($c) => optional($c->stage)->name === 'No Respon')->count(),
            'Loss'      => $customers->filter(fn ($c) => optional($c->stage)->name === 'Loss')->count(),
            'Close'     => $customers->filter(fn ($c) => optional($c->stage)->name === 'Close')->count(),
        ];

        return view('pages.crm.index', compact('customers', 'stageCounts'));
    }


    /**
     * Detail progres 1 customer (timeline history).
     */
    public function show(Customer $customer)
    {
        $customer->load(['company', 'stage']);

        $histories = CustomerStageHistory::with(['fromStage', 'toStage', 'changer'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.crm.show', compact('customer', 'histories'));
    }
}