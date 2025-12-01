<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function create() 
    {
        return view('pages.campaign.create');
    }

    public function active()
    {
        return view('pages.campaign.active');
    }

    public function history()
    {
        return view('pages.campaign.history');
    }

    public function show()
    {
        return view('pages.campaign.show');
    }
}
