<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'from',
        'to',
        'is_active',
        'company_id',
        'created_by',
    ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teams()
    {
        return $this->hasMany(CampaignTeam::class);
    }

    public function products()
    {
        return $this->hasMany(CampaignProduct::class);
    }

    public function contacts()
    {
        return $this->hasMany(CampaignContact::class);
    }
}
