<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerStageHistory;

class CustomerStageHistorySeeder extends Seeder
{
    public function run(): void
    {
        CustomerStageHistory::create([
            'customer_id'   => 1,
            'company_id'    => 1,
            'from_stage_id' => null,
            'to_stage_id'   => 1, // misal: "New Lead"
            'changed_by'    => 1,
            'note'          => 'Customer pertama kali masuk sistem.',
        ]);

        CustomerStageHistory::create([
            'customer_id'   => 1,
            'company_id'    => 1,
            'from_stage_id' => 1, // New Lead
            'to_stage_id'   => 2, // Qualified
            'changed_by'    => 1,
            'note'          => 'Customer sudah dihubungi dan memenuhi syarat.',
        ]);

        CustomerStageHistory::create([
            'customer_id'   => 1,
            'company_id'    => 1,
            'from_stage_id' => 2,
            'to_stage_id'   => 3, // Proposal
            'changed_by'    => 1,
            'note'          => 'Proposal dikirim via email.',
        ]);
    }
}
