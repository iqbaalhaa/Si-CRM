<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'is_active',
        'created_by',
    ];

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function channels()
    {
        return $this->hasMany(ContactChannel::class);
    }

    public function details()
    {
        return $this->hasMany(ContactDetail::class);
    }
}

