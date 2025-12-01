<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamRoleController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        $teamUsers = User::where('company_id', $companyId)
            ->role(['marketing', 'cs'])
            ->get();

        return view('pages.timnrole.index', compact('teamUsers'));
    }

    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:marketing,cs'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->company_id = $companyId;
        $user->save();

        $user->assignRole($data['role']);

        return redirect()->route('teamrole.index')->with('success', 'Akun tim berhasil dibuat');
    }

    public function update(Request $request, User $user)
    {
        $companyId = Auth::user()->company_id;
        if ($user->company_id !== $companyId) {
            return redirect()->route('teamrole.index');
        }

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:150'],
            'email' => ['sometimes', 'email', 'max:150', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['sometimes', 'in:marketing,cs'],
        ]);

        if (array_key_exists('name', $data)) {
            $user->name = $data['name'];
        }
        if (array_key_exists('email', $data)) {
            $user->email = $data['email'];
        }
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        if (array_key_exists('role', $data)) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()->route('teamrole.index')->with('success', 'Akun tim berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $companyId = Auth::user()->company_id;
        if ($user->company_id !== $companyId || Auth::id() === $user->id) {
            return redirect()->route('teamrole.index');
        }

        $user->delete();

        return redirect()->route('teamrole.index')->with('success', 'Akun tim berhasil dihapus');
    }
}
