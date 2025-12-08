<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'base_price',
        'photo_path',
        'description',
        'created_by',
        'updated_by',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    // We no longer append photo_url; use photo_path and build URLs in views: asset('storage') . '/' . photo_path

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }

    public function details()
    {
        return $this->hasMany(ProductDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
