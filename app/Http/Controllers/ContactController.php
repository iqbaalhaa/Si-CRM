<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function create()
    {
        return view('pages.contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:individual,company,organization',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:191',
        ]);

        $user = Auth::user();
        $companyId = $user?->company_id;

        $contact = Contact::create([
            'company_id' => $companyId,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'is_active' => true,
            'created_by' => $user?->id,
        ]);

        if (!empty($validated['email'])) {
            ContactChannel::create([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'label' => 'email',
                'value' => $validated['email'],
                'is_primary' => true,
            ]);
        }

        if (!empty($validated['phone'])) {
            ContactChannel::create([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'label' => 'phone',
                'value' => $validated['phone'],
                'is_primary' => true,
            ]);
        }

        return redirect()->route('contact.index')->with('success', 'Kontak berhasil dibuat');
    }
}

