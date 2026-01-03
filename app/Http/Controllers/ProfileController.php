<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }

    public function editSelf(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        return view('pages.settings.profile', compact('user', 'profile'));
    }

    public function updateSelf(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'company_logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:3072'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['job_title' => $data['job_title'] ?? null]
        );

        if ($request->hasFile('company_logo')) {
            $companyId = $user->company_id;
            if ($companyId) {
                $file = $request->file('company_logo');
                $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
                $dir = 'company-logos';
                $disk = \Illuminate\Support\Facades\Storage::disk('public');
                foreach (['png','jpg','jpeg','svg'] as $e) {
                    $oldPath = $dir . '/' . $companyId . '.' . $e;
                    if ($disk->exists($oldPath)) {
                        $disk->delete($oldPath);
                    }
                }
                $disk->putFileAs($dir, $file, $companyId . '.' . $ext);
            }
        }

        return redirect()->to('/setting-menu')->with('status', 'Profil berhasil diperbarui');
    }
}
