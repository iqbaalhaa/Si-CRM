<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerStageHistory;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // opsional: kalau user belum terhubung ke company, tolak akses
        if (! $user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan mana pun.');
        }

        $customers = Customer::with(['company', 'assignedTo', 'stage', 'creator'])
            ->where('company_id', $user->company_id) // hanya customer di company user
            ->latest()
            ->get();

        return view('pages.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('pages.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'source' => 'nullable|string',
            'tag' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['company_id'] = auth()->user()->company_id;
        $data['created_by'] = auth()->id();

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dibuat.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'company_id' => 'nullable|exists:perusahaan,id',
            'name' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'source' => 'nullable|string',
            'tag' => 'nullable|string',
            'assigned_to_id' => 'nullable|exists:users,id',
            'current_stage_id' => 'nullable|exists:pipeline_stages,id',
            'created_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'estimated_value' => 'nullable|numeric',
            'last_contact_at' => 'nullable|date',
        ]);

        $customer->update($data);

        return response()->json($customer);
    }

    public function edit(Customer $customer)
    {
        return view('pages.customers.edit', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }

    public function show(Request $request, Customer $customer)
    {
        $user = $request->user();

        if ($customer->company_id !== $user->company_id) {
            abort(403, 'Anda tidak berhak melihat customer ini.');
        }

        $histories = CustomerStageHistory::with(['fromStage', 'toStage', 'changer'])
            ->where('customer_id', $customer->id)
            ->where('company_id', $customer->company_id)
            ->orderByDesc('created_at')
            ->get();

        $stages = PipelineStage::where('company_id', $customer->company_id)
            ->orderBy('id')
            ->get();

        return view('pages.crm.show', compact('customer', 'histories', 'stages'));
    }

    // Update stage + simpan history
    public function updateStage(Request $request, Customer $customer)
    {
        $user = $request->user();

        if ($customer->company_id !== $user->company_id) {
            abort(403, 'Anda tidak berhak mengubah customer ini.');
        }

        $validated = $request->validate([
            'to_stage_id' => 'required|exists:pipeline_stages,id',
            'note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($customer, $user, $validated) {
            // kalau belum ada stage sebelumnya, pakai default 1
            $fromStageId = $customer->current_stage_id ?? 1;

            // update customer
            $customer->current_stage_id = $validated['to_stage_id'];
            $customer->save();

            // insert history
            CustomerStageHistory::create([
                'customer_id' => $customer->id,
                'company_id' => $customer->company_id,
                'from_stage_id' => $fromStageId,
                'to_stage_id' => $validated['to_stage_id'],
                'changed_by' => $user->id,
                'note' => $validated['note'] ?? 'Update stage via detail progression.',
            ]);
        });

        return redirect()
            ->route('crm.show', $customer) // ⬅️ ganti ini
            ->with('success', 'Stage customer berhasil diperbarui.');
    }

    public function assign(Request $request)
    {
        $user = $request->user();

        // Kalau user belum punya company_id, tolak akses
        if (! $user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan mana pun.');
        }

        // Customer hanya yang 1 company dengan user
        $customers = Customer::with(['company', 'assignedTo', 'stage', 'creator'])
            ->where('company_id', $user->company_id)
            ->latest()
            ->get();

        // CS / Marketing juga difilter berdasarkan company yang sama
        $assignableUsers = User::where('company_id', $user->company_id)
            ->role(['cs', 'marketing'])
            ->orderBy('name')
            ->get();

        return view('pages.assign.index', compact('customers', 'assignableUsers'));
    }

    public function assignTo(Request $request, Customer $customer)
    {
        $user = $request->user();

        // Pastikan customer masih di company yang sama dengan user
        if ($customer->company_id !== $user->company_id) {
            abort(403, 'Anda tidak berhak mengubah customer ini.');
        }

        $request->validate([
            'assigned_to_id' => 'nullable|exists:users,id',
            'note' => 'nullable|string|max:500', // kalau mau kasih catatan tambahan
        ]);

        // Kalau diisi, pastikan user yang dipilih juga 1 company & punya role cs/marketing
        $assignedUser = null;
        if ($request->filled('assigned_to_id')) {
            $assignedUser = User::where('company_id', $user->company_id)
                ->role(['cs', 'marketing'])
                ->findOrFail($request->assigned_to_id);
        }

        DB::transaction(function () use ($customer, $user, $assignedUser, $request) {

            // === HANDLE DEFAULT STAGE ===
            // kalau customer belum punya stage, set default ke 1
            if (is_null($customer->current_stage_id)) {
                $customer->current_stage_id = 1; // default stage id
            }

            $oldAssigned = $customer->assignedTo;          // sebelum diubah
            $oldStageId = $customer->current_stage_id;    // stage sekarang (sudah pasti >= 1)
            $oldAssignedName = $oldAssigned?->name;
            $newAssignedName = $assignedUser?->name;

            // 1) UPDATE customer (bukan insert)
            $customer->assigned_to_id = $assignedUser?->id;
            $customer->save();

            // 2) INSERT history baru ke customer_stage_histories
            $noteParts = [];

            if (! $oldAssignedName && $newAssignedName) {
                $noteParts[] = "Assign ke {$newAssignedName}";
            } elseif ($oldAssignedName && ! $newAssignedName) {
                $noteParts[] = "Unassign dari {$oldAssignedName}";
            } elseif ($oldAssignedName !== $newAssignedName) {
                $noteParts[] = "Pindah assign dari {$oldAssignedName} ke {$newAssignedName}";
            } else {
                $noteParts[] = 'Update assign (PIC tidak berubah)';
            }

            if ($request->filled('note')) {
                $noteParts[] = $request->note;
            }

            CustomerStageHistory::create([
                'customer_id' => $customer->id,
                'company_id' => $customer->company_id,
                'from_stage_id' => $oldStageId,
                'to_stage_id' => $customer->current_stage_id, // sekarang udah pasti 1 kalau awalnya null
                'changed_by' => $user->id,
                'note' => implode(' | ', $noteParts),
            ]);
        });

        return redirect()
            ->route('assign.index')
            ->with('success', 'Customer berhasil di-assign ke tim & history tercatat.');
    }
}
