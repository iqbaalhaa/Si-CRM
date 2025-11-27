<?php

namespace App\Http\Controllers;

use App\Models\PipelineStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;


class PipelineStageController extends Controller
{
    /**
     * Tampilkan halaman pipeline (form + table).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil stages milik company user yang login
        $stages = PipelineStage::where('company_id', $user->company_id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.pipeline.index', compact('stages'));
    }

    /**
     * (Opsional) Kalau nanti mau halaman create terpisah.
     */
    public function create()
    {
        return view('pages.pipeline.create');
    }

    /**
     * Simpan pipeline stage baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'type'       => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_default' => 'boolean',
        ]);

        $user = $request->user();

        $data = array_merge($validated, [
            'company_id' => $user->company_id,
        ]);

        try {
            PipelineStage::create($data);

            return redirect()
                ->route('pipeline-stages.index')
                ->with('success', 'Pipeline stage berhasil dibuat.');
        } catch (\Throwable $e) {
            Log::error('Gagal membuat pipeline stage', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pipeline stage.');
        }
    }

    /**
     * (Opsional) Show, kalau benar-benar perlu detail JSON.
     */
    public function show(PipelineStage $pipelineStage)
    {
        return $pipelineStage;
    }

    /**
     * Tampilkan form edit.
     */
    public function edit(PipelineStage $pipelineStage)
    {
        return view('pages.pipeline.edit', compact('pipelineStage'));
    }

    /**
     * Update pipeline stage.
     */
    public function update(Request $request, PipelineStage $pipelineStage)
    {
        $data = $request->validate([
            'sort_order' => 'required|integer',
        ]);

        try {
            $pipelineStage->update([
                'sort_order' => $data['sort_order'],
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Sort order updated.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal update sort_order pipeline stage', [
                'id'    => $pipelineStage->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengupdate urutan.',
            ], 500);
        }
    }

    /**
     * Hapus pipeline stage.
     */
    public function destroy(PipelineStage $pipelineStage)
    {
        try {
            $pipelineStage->delete();

            return redirect()
                ->route('pipeline-stages.index')
                ->with('success', 'Pipeline stage berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus pipeline stage', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan saat menghapus pipeline stage.');
        }
    }
}