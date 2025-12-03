<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    protected int $companyId;
    protected int $userId;

    /**
     * Terima company_id & user_id dari controller.
     */
    public function __construct(int $companyId, int $userId)
    {
        $this->companyId = $companyId;
        $this->userId    = $userId;
    }

    /**
     * Mapping tiap baris ke model Product.
     *
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     */
    public function model(array $row)
    {
        // Kalau baris kosong / tidak ada nama, skip
        if (!isset($row['name']) || $row['name'] === null || $row['name'] === '') {
            return null;
        }

        // Normalisasi is_active dari berbagai kemungkinan isi
        $rawActive = isset($row['is_active']) ? strtolower(trim((string) $row['is_active'])) : null;

        $isActive = match ($rawActive) {
            '1', 'true', 'yes', 'y', 'aktif', 'active' => true,
            '0', 'false', 'no', 'n', 'nonaktif', 'inactive' => false,
            default => true, // default aktif kalau kosong
        };

        return new Product([
            'company_id' => $this->companyId,
            'name'       => $row['name'],
            'base_price' => isset($row['base_price']) && is_numeric($row['base_price'])
                ? (int) $row['base_price']
                : 0,
            'photo_path' => $row['photo_path'] ?? null,
            'is_active'  => $isActive,
            'created_by' => $this->userId,
        ]);
    }

    /**
     * Kalau header bukan di baris pertama, misal header di baris ke-2:
     * public function headingRow(): int { return 2; }
     */
}