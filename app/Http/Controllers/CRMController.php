<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    public function assigned_index() 
    {
        return view('pages.CRM.assigned');
    }

    public function stages_index() 
    {
        return view('pages.CRM.stages');
    }
    
    public function stages_single_index() 
    {
        return view('pages.CRM.stages-single');
    }
}
