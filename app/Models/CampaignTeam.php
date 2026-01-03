<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignTeam extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'role',
        'assigned_by',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
