<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['company', 'assignedTo', 'stage', 'creator'])
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
            'name'   => 'required|string',
            'phone'  => 'nullable|string|max:30',
            'email'  => 'nullable|email',
            'source' => 'nullable|string',
            'tag'    => 'nullable|string',
            'notes'  => 'nullable|string',
        ]);

        $data['company_id'] = auth()->user()->company_id;
        $data['created_by'] = auth()->id();

        $customer = Customer::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Customer berhasil dibuat.',
            'data'    => $customer,
        ], 201);
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

    public function edit(Customer $customer)
    {
        return view('pages.customers.edit', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['status' => 'deleted']);
    }
}