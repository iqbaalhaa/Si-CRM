<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignProductContact extends Model
{
    protected $fillable = [
        'campaign_product_id',
        'contact_id',
    ];

    public function campaignProduct()
    {
        return $this->belongsTo(CampaignProduct::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
