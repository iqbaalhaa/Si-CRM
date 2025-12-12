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
            'products.*' => 'integer',
        ]);

        $campaign = Campaign::create([
            'name' => $validated['name'],
            'from' => $validated['start_date'],
            'to' => $validated['end_date'],
            'is_active' => true,
            'company_id' => $companyId,
            'created_by' => Auth::id(),
        ]);

        // Default leader = creator
        \App\Models\CampaignTeam::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'role' => 'leader',
            'assigned_by' => Auth::id(),
        ]);

        // Attach products to campaign (expect product IDs)
        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $productId) {
                $product = Product::where('company_id', $companyId)
                    ->where('id', $productId)
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

        // Company users for team management
        $companyUsers = \App\Models\User::whereHas('profile', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->orderBy('name')
            ->get();

        $availableContacts = \App\Models\Contact::where('company_id', $companyId)
            ->where('created_by', Auth::id())
            ->latest()
            ->take(200)
            ->get();

        return view('pages.campaign.show', compact('campaign', 'companyUsers', 'availableContacts'));
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

    public function preview(Request $request)
    {
        $companyId = Auth::user()->profile->company_id;

        $name = $request->input('name');
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $productIds = $request->input('products', []);

        $productNames = [];
        if (is_array($productIds) && !empty($productIds)) {
            $productNames = Product::where('company_id', $companyId)
                ->whereIn('id', $productIds)
                ->pluck('name')
                ->values()
                ->toArray();
        }

        $dates = 'Tanggal belum diatur';
        if ($start && $end) {
            $dates = $start . ' - ' . $end;
        } elseif ($start) {
            $dates = 'Mulai: ' . $start;
        }

        return response()->json([
            'name' => $name ?: 'Nama Campaign',
            'dates' => $dates,
            'products' => $productNames,
        ]);
    }

    public function updateContactStage(Request $request, $campaignId, $campaignContactId)
    {
        $companyId = Auth::user()->profile->company_id;

        $campaign = Campaign::where('company_id', $companyId)->findOrFail($campaignId);
        $cc = \App\Models\CampaignContact::where('campaign_id', $campaign->id)->findOrFail($campaignContactId);

        $data = $request->validate([
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $cc->status = $data['status'];
        $cc->save();

        \App\Models\CampaignContactHistory::create([
            'campaign_contact_id' => $cc->id,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
            'changed_by' => Auth::id(),
        ]);

        return response()->json(['ok' => true, 'status' => $cc->status]);
    }

    public function updateContactProducts(Request $request, $campaignId, $contactId)
    {
        $companyId = Auth::user()->profile->company_id;

        $campaign = Campaign::where('company_id', $companyId)->findOrFail($campaignId);

        // Ensure contact belongs to same company
        $contact = \App\Models\Contact::where('company_id', $companyId)->findOrFail($contactId);

        $data = $request->validate([
            'products' => 'array',
            'products.*' => 'integer',
        ]);

        $selectedProductIds = $data['products'] ?? [];

        // Map product_id -> campaign_product_id for this campaign
        $cpMap = \App\Models\CampaignProduct::where('campaign_id', $campaign->id)
            ->pluck('id', 'product_id');

        // Existing CPC records for this contact within this campaign
        $existingCpIds = \App\Models\CampaignProductContact::whereIn('campaign_product_id', $cpMap->values())
            ->where('contact_id', $contact->id)
            ->pluck('campaign_product_id')
            ->all();

        $selectedCpIds = [];
        foreach ($selectedProductIds as $pid) {
            if (isset($cpMap[$pid])) {
                $selectedCpIds[] = $cpMap[$pid];
            }
        }

        // Delete unselected
        \App\Models\CampaignProductContact::where('contact_id', $contact->id)
            ->whereIn('campaign_product_id', $cpMap->values())
            ->whereNotIn('campaign_product_id', $selectedCpIds)
            ->delete();

        // Add missing
        foreach (array_diff($selectedCpIds, $existingCpIds) as $cpId) {
            \App\Models\CampaignProductContact::create([
                'campaign_product_id' => $cpId,
                'contact_id' => $contact->id,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function updateTeam(Request $request, $campaignId)
    {
        $companyId = Auth::user()->profile->company_id;
        $campaign = Campaign::where('company_id', $companyId)->findOrFail($campaignId);

        $data = $request->validate([
            'leader_id' => 'required|integer',
            'members' => 'array',
            'members.*' => 'integer',
        ]);

        $userIds = collect(array_merge([$data['leader_id']], $data['members'] ?? []))
            ->unique()
            ->values();

        // Ensure users are from same company
        $validUserIds = \App\Models\User::whereHas('profile', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->whereIn('id', $userIds)
            ->pluck('id')
            ->all();

        // Replace team
        \App\Models\CampaignTeam::where('campaign_id', $campaign->id)->delete();

        // Leader
        if (in_array($data['leader_id'], $validUserIds, true)) {
            \App\Models\CampaignTeam::create([
                'campaign_id' => $campaign->id,
                'user_id' => $data['leader_id'],
                'role' => 'leader',
                'assigned_by' => Auth::id(),
            ]);
        }

        // Members
        foreach (($data['members'] ?? []) as $uid) {
            if (!in_array($uid, $validUserIds, true)) continue;
            if ($uid === $data['leader_id']) continue;
            \App\Models\CampaignTeam::create([
                'campaign_id' => $campaign->id,
                'user_id' => $uid,
                'role' => 'member',
                'assigned_by' => Auth::id(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function assignContacts(Request $request, $campaignId)
    {
        $companyId = Auth::user()->profile->company_id;
        $campaign = Campaign::where('company_id', $companyId)->findOrFail($campaignId);

        $data = $request->validate([
            'contacts' => 'array',
            'contacts.*' => 'integer',
        ]);

        $contactIds = $data['contacts'] ?? [];
        if (empty($contactIds)) {
            return response()->json(['ok' => true]);
        }

        $validIds = \App\Models\Contact::where('company_id', $companyId)
            ->where('created_by', Auth::id())
            ->whereIn('id', $contactIds)
            ->pluck('id')
            ->all();

        $existing = \App\Models\CampaignContact::where('campaign_id', $campaign->id)
            ->whereIn('contact_id', $validIds)
            ->pluck('contact_id')
            ->all();

        foreach (array_diff($validIds, $existing) as $cid) {
            \App\Models\CampaignContact::create([
                'campaign_id' => $campaign->id,
                'contact_id' => $cid,
                'created_by' => Auth::id(),
                'status' => 'New',
                'notes' => null,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function pipeline($campaignId, $ccId)
    {
        $companyId = Auth::user()->profile->company_id;

        $campaign = Campaign::where('company_id', $companyId)
            ->with(['creator'])
            ->findOrFail($campaignId);

        $cc = \App\Models\CampaignContact::where('campaign_id', $campaign->id)
            ->with(['contact', 'histories.changer'])
            ->findOrFail($ccId);

        $histories = $cc->histories->sortBy('id')->values();

        return view('pages.campaign.pipeline', [
            'campaign' => $campaign,
            'cc' => $cc,
            'histories' => $histories,
        ]);
    }
}
