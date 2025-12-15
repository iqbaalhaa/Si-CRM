<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsSelectedExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $companyId;
    protected array $ids;

    public function __construct(int $companyId, array $ids)
    {
        $this->companyId = $companyId;
        $this->ids = $ids;
    }

    public function collection(): Collection
    {
        return Product::query()
            ->where('company_id', $this->companyId)
            ->whereIn('id', $this->ids)
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'base_price',
            'photo_path',
            'is_active',
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            (int) $product->base_price,
            $product->photo_path,
            $product->is_active ? 1 : 0,
        ];
    }
}
