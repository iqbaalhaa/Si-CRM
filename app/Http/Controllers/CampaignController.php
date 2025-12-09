<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function create()
    {
        $companyId = Auth::user()->profile->company_id;
        $products = Product::where('company_id', $companyId)->get();

        return view('pages.campaign.create', compact('products'));
    }

    public function store(Request $request)
    {
        $companyId = Auth::user()->profile->company_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'nullable|string',
            'channel' => 'nullable|string',
            'audience' => 'nullable|string',
            'owner' => 'nullable|string',
            'target_contacts' => 'nullable|integer|min:0',
            'goal' => 'nullable|string',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
        ]);

        $campaign = Campaign::create([
            'name' => $validated['name'],
            'from' => $validated['start_date'],
            'to' => $validated['end_date'],
            'is_active' => true,
            'company_id' => $companyId,
            'created_by' => Auth::id(),
        ]);

        // Attach products to campaign
        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $productName) {
                $product = Product::where('company_id', $companyId)
                    ->where('name', $productName)
                    ->first();

                if ($product) {
                    CampaignProduct::create([
                        'campaign_id' => $campaign->id,
                        'product_id' => $product->id,
                    ]);
                }
            }
        }

        return redirect()->route('campaign.show', $campaign->id)
            ->with('success', 'Campaign berhasil dibuat');
    }

    public function active()
    {
        $companyId = Auth::user()->profile->company_id;

        $campaigns = Campaign::where('company_id', $companyId)
            ->where('is_active', true)
            ->with([
                'creator',
                'teams.user',
                'products.product',
                'productContacts.contact',
                'contacts'
            ])
            ->orderBy('from', 'desc')
            ->get();

        $stats = [
            'total_active' => $campaigns->count(),
            'total_contacts' => $campaigns->sum(function ($campaign) {
                return $campaign->contacts->count();
            }),
            'avg_closing_rate' => 18,
        ];

        return view('pages.campaign.active', compact('campaigns', 'stats'));
    }

    public function history()
    {
        $companyId = Auth::user()->profile->company_id;

        $campaigns = Campaign::where('company_id', $companyId)
            ->where('is_active', false)
            ->with([
                'creator',
                'teams.user',
                'products.product',
                'contacts'
            ])
            ->orderBy('to', 'desc')
            ->get();

        return view('pages.campaign.history', compact('campaigns'));
    }

    public function show($id)
    {
        $companyId = Auth::user()->profile->company_id;

        $campaign = Campaign::where('company_id', $companyId)
            ->with([
                'creator',
                'teams.user',
                'products.product',
                'contacts.histories.changer',
                'productContacts.campaignProduct.product',
                'productContacts.contact'
            ])
            ->findOrFail($id);

        return view('pages.campaign.show', compact('campaign'));
    }

    public function edit($id)
    {
        $companyId = Auth::user()->profile->company_id;

        $campaign = Campaign::where('company_id', $companyId)->findOrFail($id);
        $products = Product::where('company_id', $companyId)->get();
        $campaignProducts = $campaign->products->pluck('product_id')->toArray();

        return view('pages.campaign.edit', compact('campaign', 'products', 'campaignProducts'));
    }

    public function update(Request $request, $id)
    {
        $companyId = Auth::user()->profile->company_id;

        $campaign = Campaign::where('company_id', $companyId)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
            'products' => 'nullable|array',
        ]);

        $campaign->update([
            'name' => $validated['name'],
            'from' => $validated['start_date'],
            'to' => $validated['end_date'],
            'is_active' => $validated['is_active'] ?? $campaign->is_active,
        ]);

        // Sync products
        $campaign->products()->delete();

        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $productId) {
                CampaignProduct::create([
                    'campaign_id' => $campaign->id,
                    'product_id' => $productId,
                ]);
            }
        }

        return redirect()->route('campaign.show', $campaign->id)
            ->with('success', 'Campaign berhasil diperbarui');
    }

    public function destroy($id)
    {
        $companyId = Auth::user()->profile->company_id;

        $campaign = Campaign::where('company_id', $companyId)->findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaign.active')
            ->with('success', 'Campaign berhasil dihapus');
    }
}
