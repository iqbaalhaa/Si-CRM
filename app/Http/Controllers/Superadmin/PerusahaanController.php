<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    public function index()
    {
        $perusahaans = Perusahaan::query()->latest()->paginate(10);
        return view('superadmin.perusahaan.index', compact('perusahaans'));
    }
}

