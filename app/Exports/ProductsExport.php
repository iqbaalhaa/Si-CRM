<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $companyId;

    /**
     * Terima company_id dari controller
     */
    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Data yang akan diexport
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return Product::query()
            ->where('company_id', $this->companyId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Header kolom di baris pertama
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'name',
            'base_price',
            'photo_path',
            'is_active',
        ];
    }

    /**
     * Mapping tiap row Product -> array sesuai urutan headings
     *
     * @param  \App\Models\Product  $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->name,
            (int) $product->base_price,
            $product->photo_path,
            $product->is_active ? 1 : 0,  // 1 = active, 0 = inactive
        ];
    }
}
