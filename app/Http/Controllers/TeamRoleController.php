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
}
