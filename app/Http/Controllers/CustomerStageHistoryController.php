<?php

namespace App\Http\Controllers;

use App\Models\CustomerStageHistory;
use Illuminate\Http\Request;

class CustomerStageHistoryController extends Controller
{
    public function index()
    {
        $histories = CustomerStageHistory::with([
            'customer',
            'company',
            'fromStage',
            'toStage',
            'changer'
        ])->latest()->get();

        return response()->json($histories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'company_id'      => 'required|exists:perusahaans,id',
            'from_stage_id'   => 'nullable|exists:pipeline_stages,id',
            'to_stage_id'     => 'required|exists:pipeline_stages,id',
            'changed_by'      => 'required|exists:users,id',
            'note'            => 'nullable|string',
        ]);

        $history = CustomerStageHistory::create($data);

        return response()->json($history, 201);
    }

    public function show($id)
    {
        $history = CustomerStageHistory::with([
            'customer',
            'company',
            'fromStage',
            'toStage',
            'changer'
        ])->findOrFail($id);

        return response()->json($history);
    }

    public function update(Request $request, $id)
    {
        $history = CustomerStageHistory::findOrFail($id);

        $data = $request->validate([
            'from_stage_id' => 'nullable|exists:pipeline_stages,id',
            'to_stage_id'   => 'nullable|exists:pipeline_stages,id',
            'note'          => 'nullable|string',
        ]);

        $history->update($data);

        return response()->json($history);
    }

    public function destroy($id)
    {
        $history = CustomerStageHistory::findOrFail($id);
        $history->delete();

        return response()->json(['message' => 'deleted']);
    }
}