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
            'name'       => ['required', 'string', 'max:150'],
            'email'      => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8'],
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
}

