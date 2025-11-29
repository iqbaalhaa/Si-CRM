<?php

namespace Database\Seeders;

use App\Models\PipelineStage;
use Illuminate\Database\Seeder;

class PipelineStageSeeder extends Seeder
{
    public function run(): void
    {
        PipelineStage::create([
            'company_id' => 1,
            'name' => 'New Lead',
            'type' => 'lead',
            'sort_order' => 1,
            'is_default' => true,
        ]);

        PipelineStage::create([
            'company_id' => 1,
            'name' => 'Qualified',
            'type' => 'lead',
            'sort_order' => 2,
            'is_default' => false,
        ]);

        PipelineStage::create([
            'company_id' => 1,
            'name' => 'Proposal',
            'type' => 'deal',
            'sort_order' => 3,
            'is_default' => false,
        ]);
    }
}
