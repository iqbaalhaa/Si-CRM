<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamRoleController extends Controller
{
    public function index()
    {
        $authUser  = Auth::user();
        $companyId = optional($authUser->profile)->company_id;

        if (!$companyId) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        // Ambil user tim (marketing & cs) yang profile-nya terhubung ke company yang sama
        $teamUsers = User::role(['admin', 'lead-operations'])
            ->whereHas('profile', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->with('profile')
            ->get();

        return view('pages.timnrole.index', compact('teamUsers'));
    }

    public function store(Request $request)
    {
        $authUser  = Auth::user();
        $companyId = optional($authUser->profile)->company_id;

        if (!$companyId) {
            abort(403, 'User belum terhubung ke perusahaan.');
        }

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'role'      => ['required', 'in:lead-operations'],
            'job_title' => ['nullable', 'string', 'max:150'],
        ]);

        // Buat user baru
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            // auto di-hash karena casts('password' => 'hashed') di model User
            'password' => $data['password'],
        ]);

        // Buat profile untuk user baru, kaitkan ke company yang sama
        $user->profile()->create([
            'company_id' => $companyId,
            'job_title'  => $data['job_title'] ?? ucfirst($data['role']),
            'photo'      => null,
        ]);

        // Assign role (Spatie)
        $user->assignRole($data['role']);

        return redirect()
            ->route('teamrole.index')
            ->with('success', 'Akun tim berhasil dibuat');
    }

    public function update(Request $request, User $user)
    {
        $authUserCompanyId = optional(Auth::user()->profile)->company_id;
        $targetCompanyId   = optional($user->profile)->company_id;

        // Pastikan user yang diubah masih di company yang sama
        if (!$authUserCompanyId || $authUserCompanyId !== $targetCompanyId) {
            return redirect()->route('teamrole.index');
        }

        $data = $request->validate([
            'name'      => ['sometimes', 'string', 'max:150'],
            'email'     => ['sometimes', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', 'string', 'min:8'],
            'role'      => ['sometimes', 'in:lead-operations'],
            'job_title' => ['nullable', 'string', 'max:150'],
        ]);

        // Update data user
        if (array_key_exists('name', $data)) {
            $user->name = $data['name'];
        }

        if (array_key_exists('email', $data)) {
            $user->email = $data['email'];
        }

        if (!empty($data['password'])) {
            // Akan otomatis di-hash oleh casts('password' => 'hashed')
            $user->password = $data['password'];
        }

        $user->save();

        // Update / create profile kalau perlu
        if (array_key_exists('job_title', $data)) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_id' => $targetCompanyId ?? $authUserCompanyId,
                    'job_title'  => $data['job_title'],
                ]
            );
        }

        // Update role kalau dikirim
        if (array_key_exists('role', $data)) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('teamrole.index')
            ->with('success', 'Akun tim berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $authUserCompanyId = optional(Auth::user()->profile)->company_id;
        $targetCompanyId   = optional($user->profile)->company_id;

        // Cegah: beda company atau menghapus diri sendiri
        if (!$authUserCompanyId || $authUserCompanyId !== $targetCompanyId || Auth::id() === $user->id) {
            return redirect()->route('teamrole.index');
        }

        $user->delete();

        return redirect()
            ->route('teamrole.index')
            ->with('success', 'Akun tim berhasil dihapus');
    }
}