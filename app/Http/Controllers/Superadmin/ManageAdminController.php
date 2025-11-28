<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;

class ManageAdminController extends Controller
{
    public function index()
    {
        $perusahaans = Perusahaan::query()->orderBy('name')->get();
        $admins = User::role('admin')->get();

        return view('superadmin.manageadmin.index', compact('perusahaans', 'admins'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:perusahaan,id'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->company_id = $data['company_id'];
        $user->save();

        $user->assignRole('admin');

        return redirect()->route('manageadmin.index')->with('success', 'Admin perusahaan berhasil didaftarkan');
    }

    public function update(Request $request, User $user)
    {
        if (! $user->hasRole('admin')) {
            return redirect()->route('manageadmin.index');
        }

        $data = $request->validate([
            'company_id' => ['sometimes', 'exists:perusahaan,id'],
            'name' => ['sometimes', 'string', 'max:150'],
            'email' => ['sometimes', 'email', 'max:150', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (array_key_exists('company_id', $data)) {
            $user->company_id = $data['company_id'];
        }
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

        return redirect()->route('manageadmin.index')->with('success', 'Admin perusahaan berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if (! $user->hasRole('admin')) {
            return redirect()->route('manageadmin.index');
        }

        $user->delete();

        return redirect()->route('manageadmin.index')->with('success', 'Admin perusahaan berhasil dihapus');
    }

    public function updateActive(Request $request, User $user)
    {
        if (! $user->hasRole('admin')) {
            return response()->json(['message' => 'Invalid user'], 400);
        }

        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user->is_active = (bool) $data['is_active'];
        $user->save();

        if (! $user->is_active) {
            $affected = User::query()
                ->where('company_id', $user->company_id)
                ->where('id', '!=', $user->id)
                ->get();

            foreach ($affected as $u) {
                if (! $u->hasRole('superadmin') && ! $u->hasRole('admin')) {
                    $u->is_active = false;
                    $u->save();
                }
            }
        } else {
            $affected = User::query()
                ->where('company_id', $user->company_id)
                ->where('id', '!=', $user->id)
                ->get();

            foreach ($affected as $u) {
                if (! $u->hasRole('superadmin')) {
                    $u->is_active = true;
                    $u->save();
                }
            }
        }

        return response()->json(['success' => true, 'is_active' => $user->is_active]);
    }
}
