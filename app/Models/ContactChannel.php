<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactChannel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'contact_id',
        'label',
        'value',
        'is_primary',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }
}

