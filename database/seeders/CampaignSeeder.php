<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\CampaignContactHistory;
use App\Models\CampaignProduct;
use App\Models\CampaignProductContact;
use App\Models\CampaignTeam;
use App\Models\Contact;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        // Loop untuk setiap company (1-5)
        for ($companyId = 1; $companyId <= 5; $companyId++) {
            $this->seedCampaignsForCompany($companyId);
        }
    }

    private function seedCampaignsForCompany($companyId)
    {
        $users = User::whereHas('profile', fn ($q) => $q->where('company_id', $companyId))->pluck('id')->all();
        if (empty($users)) {
            $user = User::factory()->create();
            \App\Models\Profile::firstOrCreate(['user_id' => $user->id], ['company_id' => $companyId]);
            $users = [$user->id];
        }

        $products = Product::where('company_id', $companyId)->limit(5)->pluck('id')->all();
        $contacts = Contact::where('company_id', $companyId)->limit(50)->pluck('id')->all();

        if (empty($products) || empty($contacts)) {
            $this->command->warn("Produk atau Contact untuk company $companyId tidak cukup. Dilewati.");
            return;
        }

        // Campaign 1: Summer Promo
        $campaign1 = Campaign::updateOrCreate(
            ['name' => "Summer Promo 2025 - Company $companyId", 'company_id' => $companyId],
            [
                'from' => now()->subMonths(2),
                'to' => now()->addMonths(1),
                'is_active' => true,
                'created_by' => Arr::random($users),
            ]
        );

        foreach (array_slice($users, 0, min(3, count($users))) as $idx => $userId) {
            CampaignTeam::updateOrCreate(
                ['campaign_id' => $campaign1->id, 'user_id' => $userId],
                ['role' => $idx === 0 ? 'leader' : 'member', 'assigned_by' => $users[0]]
            );
        }

        $campaign1Products = array_slice($products, 0, 2);
        foreach ($campaign1Products as $productId) {
            CampaignProduct::updateOrCreate(
                ['campaign_id' => $campaign1->id, 'product_id' => $productId],
                []
            );
        }

        foreach (array_slice($contacts, 0, 15) as $contactId) {
            $cc = CampaignContact::updateOrCreate(
                ['campaign_id' => $campaign1->id, 'contact_id' => $contactId],
                [
                    'created_by' => Arr::random($users),
                    'status' => Arr::random(['pending', 'contacted', 'converted']),
                    'notes' => fake()->optional()->sentence(),
                ]
            );

            CampaignContactHistory::updateOrCreate(
                ['campaign_contact_id' => $cc->id, 'status' => $cc->status],
                ['notes' => 'Initial status', 'changed_by' => Arr::random($users)]
            );

            foreach (CampaignProduct::where('campaign_id', $campaign1->id)->pluck('id') as $cpId) {
                CampaignProductContact::updateOrCreate(
                    ['campaign_product_id' => $cpId, 'contact_id' => $contactId],
                    []
                );
            }
        }

        // Campaign 2: Year-End Sale
        $campaign2 = Campaign::updateOrCreate(
            ['name' => "Year-End Sale 2025 - Company $companyId", 'company_id' => $companyId],
            [
                'from' => now()->addMonths(6),
                'to' => now()->addMonths(8),
                'is_active' => false,
                'created_by' => Arr::random($users),
            ]
        );

        foreach (array_slice($users, 0, min(2, count($users))) as $idx => $userId) {
            CampaignTeam::updateOrCreate(
                ['campaign_id' => $campaign2->id, 'user_id' => $userId],
                ['role' => $idx === 0 ? 'leader' : 'member', 'assigned_by' => $users[0]]
            );
        }

        $campaign2Products = array_slice($products, 2, 3);
        foreach ($campaign2Products as $productId) {
            CampaignProduct::updateOrCreate(
                ['campaign_id' => $campaign2->id, 'product_id' => $productId],
                []
            );
        }

        foreach (array_slice($contacts, 15, 20) as $contactId) {
            $cc = CampaignContact::updateOrCreate(
                ['campaign_id' => $campaign2->id, 'contact_id' => $contactId],
                [
                    'created_by' => Arr::random($users),
                    'status' => Arr::random(['pending', 'contacted']),
                    'notes' => null,
                ]
            );

            CampaignContactHistory::updateOrCreate(
                ['campaign_contact_id' => $cc->id, 'status' => $cc->status],
                ['notes' => 'Initial status', 'changed_by' => Arr::random($users)]
            );

            foreach (CampaignProduct::where('campaign_id', $campaign2->id)->pluck('id') as $cpId) {
                CampaignProductContact::updateOrCreate(
                    ['campaign_product_id' => $cpId, 'contact_id' => $contactId],
                    []
                );
            }
        }

        // Campaign 3: Product Launch
        $campaign3 = Campaign::updateOrCreate(
            ['name' => "Product Launch - CRM Enterprise - Company $companyId", 'company_id' => $companyId],
            [
                'from' => now(),
                'to' => now()->addMonths(3),
                'is_active' => true,
                'created_by' => Arr::random($users),
            ]
        );

        foreach ($users as $idx => $userId) {
            CampaignTeam::updateOrCreate(
                ['campaign_id' => $campaign3->id, 'user_id' => $userId],
                ['role' => $idx === 0 ? 'leader' : 'member', 'assigned_by' => $users[0]]
            );
        }

        foreach ($products as $productId) {
            CampaignProduct::updateOrCreate(
                ['campaign_id' => $campaign3->id, 'product_id' => $productId],
                []
            );
        }

        foreach (array_slice($contacts, 35, 30) as $contactId) {
            $cc = CampaignContact::updateOrCreate(
                ['campaign_id' => $campaign3->id, 'contact_id' => $contactId],
                [
                    'created_by' => Arr::random($users),
                    'status' => Arr::random(['pending', 'contacted', 'converted', 'rejected']),
                    'notes' => fake()->optional()->sentence(),
                ]
            );

            $histories = Arr::random(['pending', 'contacted', 'converted'], rand(1, 2));
            foreach ($histories as $historyStatus) {
                CampaignContactHistory::create([
                    'campaign_contact_id' => $cc->id,
                    'status' => $historyStatus,
                    'notes' => fake()->optional()->sentence(),
                    'changed_by' => Arr::random($users),
                ]);
            }

            foreach (CampaignProduct::where('campaign_id', $campaign3->id)->pluck('id') as $cpId) {
                CampaignProductContact::updateOrCreate(
                    ['campaign_product_id' => $cpId, 'contact_id' => $contactId],
                    []
                );
            }
        }

        $this->command->info("Campaign seeded untuk company $companyId");
    }
}
