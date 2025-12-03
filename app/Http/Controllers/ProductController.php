<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Pastikan user punya profile + company_id
        if (!$user->profile || !$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $products = Product::where('company_id', $user->company_id)
            ->latest()
            ->get();

        return view('pages.products.index', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('pages.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'base_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo_path'  => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['company_id'] = $user->company_id;
        $data['slug']       = Str::slug($data['name']) . '-' . Str::random(5);
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;
        $data['is_active']  = $request->boolean('is_active', true);

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        $user = $request->user();

        if ($product->company_id !== $user->company_id) {
            abort(403, 'Anda tidak berhak mengakses produk ini.');
        }

        $product->load('details');

        return view('pages.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product)
    {
        $user = $request->user();

        if ($product->company_id !== $user->company_id) {
            abort(403, 'Anda tidak berhak mengedit produk ini.');
        }

        $product->load('details');

        return view('pages.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $user = $request->user();

        if ($product->company_id !== $user->company_id) {
            abort(403, 'Anda tidak berhak mengedit produk ini.');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'base_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo_path'  => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['slug']       = $product->slug ?? (Str::slug($data['name']) . '-' . Str::random(5));
        $data['updated_by'] = $user->id;
        $data['is_active']  = $request->boolean('is_active', true);

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();

        if ($product->company_id !== $user->company_id) {
            abort(403, 'Anda tidak berhak menghapus produk ini.');
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * MASS UPDATE
     * - contoh use case: update is_active banyak produk sekaligus
     * - bisa juga extend untuk field lain (misal base_price).
     */
    public function massUpdate(Request $request)
    {
        $user = $request->user();

        if (!$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $data = $request->validate([
            'ids'        => 'required|array',
            'ids.*'      => 'integer|exists:products,id',
            'is_active'  => 'nullable|boolean',
            'base_price' => 'nullable|numeric|min:0',
        ]);

        $ids = $data['ids'];

        $query = Product::where('company_id', $user->company_id)
            ->whereIn('id', $ids);

        $payload = [];
        if ($request->has('is_active')) {
            $payload['is_active'] = $request->boolean('is_active');
        }
        if ($request->filled('base_price')) {
            $payload['base_price'] = $request->input('base_price');
        }

        if (empty($payload)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Tidak ada field yang diupdate.',
                ], 422);
            }

            return back()->with('error', 'Tidak ada field yang diupdate.');
        }

        $payload['updated_by'] = $user->id;

        $affected = $query->update($payload);

        if ($request->expectsJson()) {
            return response()->json([
                'status'   => 'success',
                'message'  => "Berhasil update {$affected} produk.",
                'updated'  => $affected,
            ]);
        }

        return back()->with('success', "Berhasil update {$affected} produk.");
    }

    /**
     * EXPORT CSV dengan delimiter bisa diatur ("," atau ";").
     * Default: ";"
     *
     * Request bisa kirim: ?delimiter=;  atau  ?delimiter=,
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();

        if (!$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $delimiter = $request->input('delimiter', ';');
        if (!in_array($delimiter, [',', ';'])) {
            $delimiter = ';';
        }

        $fileName = 'products_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($user, $delimiter) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 (biar Excel Windows aman)
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($handle, ['name', 'base_price', 'photo_path', 'is_active'], $delimiter);

            Product::where('company_id', $user->company_id)
                ->orderBy('name')
                ->chunk(200, function ($products) use ($handle, $delimiter) {
                    foreach ($products as $product) {
                        fputcsv($handle, [
                            $product->name,
                            $product->base_price,
                            $product->photo_path,
                            $product->is_active ? 1 : 0,
                        ], $delimiter);
                    }
                });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
    /**
     * IMPORT CSV dengan delimiter bisa diatur ("," atau ";").
     *
     * Form:
     *  - input name="file"
     *  - optional: input name="delimiter" value=";" or ","
     */
    public function importCsv(Request $request)
    {
        $user = $request->user();

        if (!$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $request->validate([
            'file'      => 'required|file|mimes:csv,txt',
            'delimiter' => 'nullable|in:;,',
        ]);

        $delimiter = $request->input('delimiter', ';');

        $path   = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Tidak dapat membuka file.');
        }

        // Baca header
        $header = fgetcsv($handle, 0, $delimiter);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'Header CSV tidak terbaca.');
        }

        // Normalisasi header ke lowercase
        $header = array_map(fn ($h) => strtolower(trim($h)), $header);

        $nameIndex       = array_search('name', $header);
        $priceIndex      = array_search('base_price', $header);
        $photoPathIndex  = array_search('photo_path', $header);
        $activeIndex     = array_search('is_active', $header);

        if ($nameIndex === false || $priceIndex === false) {
            fclose($handle);
            return back()->with('error', 'Header CSV minimal harus berisi: name, base_price.');
        }

        $count = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $name  = $row[$nameIndex]  ?? null;
            $price = $row[$priceIndex] ?? null;

            if (!$name || !is_numeric($price)) {
                continue; // skip baris invalid
            }

            $photoPath = $photoPathIndex !== false ? ($row[$photoPathIndex] ?? null) : null;
            $isActiveRaw = $activeIndex !== false ? ($row[$activeIndex] ?? 1) : 1;

            // Normalisasi is_active (boleh 1/0, true/false, yes/no, dll)
            $isActiveRaw = strtolower(trim((string) $isActiveRaw));
            $isActive = match ($isActiveRaw) {
                '1', 'true', 'yes', 'y', 'aktif', 'active' => true,
                '0', 'false', 'no', 'n', 'nonaktif', 'inactive' => false,
                default => true,
            };

            Product::create([
                'company_id'  => $user->company_id,
                'name'        => $name,
                'base_price'  => (float) $price,
                'photo_path'  => $photoPath ?: null,
                'is_active'   => $isActive,
                'created_by'  => $user->id,
            ]);

            $count++;
        }

        fclose($handle);

        return redirect()
            ->route('products.index')
            ->with('success', "Import CSV selesai. {$count} produk ditambahkan.");
    }
    /**
     * EXPORT XLSX (pakai maatwebsite/excel).
     */
    public function exportXlsx(Request $request)
    {
        $user = $request->user();

        if (!$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $fileName = 'products_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ProductsExport($user->company_id), $fileName);
    }

    /**
     * IMPORT XLSX (pakai maatwebsite/excel).
     */
    public function importXlsx(Request $request)
    {
        $user = $request->user();

        if (!$user->company_id) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(
            new ProductsImport($user->company_id, $user->id),
            $request->file('file')
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Import XLSX selesai.');
    }
}