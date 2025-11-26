<?php

namespace App\Http\Controllers;

use App\Models\PipelineStage;
use Illuminate\Http\Request;

class PipelineStageController extends Controller
{
    public function index()
    {
        return PipelineStage::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:perusahaan,id',
            'name' => 'required|string',
            'type' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_default' => 'boolean',
        ]);

        return PipelineStage::create($data);
    }

    public function show(PipelineStage $pipelineStage)
    {
        return $pipelineStage;
    }

    public function update(Request $request, PipelineStage $pipelineStage)
    {
        $data = $request->validate([
            'company_id' => 'nullable|exists:perusahaan,id',
            'name' => 'nullable|string',
            'type' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_default' => 'boolean',
        ]);

        $pipelineStage->update($data);

        return $pipelineStage;
    }

    public function destroy(PipelineStage $pipelineStage)
    {
        $pipelineStage->delete();

        return response()->json(['status' => 'deleted']);
    }
}