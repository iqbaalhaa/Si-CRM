<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignContactHistory extends Model
{
    protected $fillable = [
        'campaign_contact_id',
        'status',
        'notes',
        'changed_by',
    ];

    public function campaignContact()
    {
        return $this->belongsTo(CampaignContact::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
