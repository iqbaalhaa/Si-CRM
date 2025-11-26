<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['company', 'assignedTo', 'stage', 'creator'])->get();

        // build rows
        $rows = $customers->map(function ($c, $index) {
            return [
                $index + 1,                 // No
                $c->name,
                optional($c->company)->code,
                optional($c->company)->name,
                $c->phone,
                $c->email,
                optional($c->stage)->name ?? '-',
                '<a href="' . route('customers.edit', $c->id) . '" class="btn btn-sm btn-primary">Edit</a>',
            ];
        });

        return view('pages.customers.index', [
            'tableRows' => $rows,
        ]);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id'        => 'required|exists:perusahaan,id',
            'name'              => 'required|string',
            'phone'             => 'nullable|string|max:30',
            'email'             => 'nullable|email',
            'source'            => 'nullable|string',
            'tag'               => 'nullable|string',
            'assigned_to_id'    => 'nullable|exists:users,id',
            'current_stage_id'  => 'nullable|exists:pipeline_stages,id',
            'created_by'        => 'nullable|exists:users,id',
            'notes'             => 'nullable|string',
            'estimated_value'   => 'nullable|numeric',
            'last_contact_at'   => 'nullable|date',
        ]);

        $customer = Customer::create($data);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return $customer->load(['company', 'assignedTo', 'stage', 'creator']);
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'company_id'        => 'nullable|exists:perusahaan,id',
            'name'              => 'nullable|string',
            'phone'             => 'nullable|string|max:30',
            'email'             => 'nullable|email',
            'source'            => 'nullable|string',
            'tag'               => 'nullable|string',
            'assigned_to_id'    => 'nullable|exists:users,id',
            'current_stage_id'  => 'nullable|exists:pipeline_stages,id',
            'created_by'        => 'nullable|exists:users,id',
            'notes'             => 'nullable|string',
            'estimated_value'   => 'nullable|numeric',
            'last_contact_at'   => 'nullable|date',
        ]);

        $customer->update($data);

        return response()->json($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['status' => 'deleted']);
    }
}