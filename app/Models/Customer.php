<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'phone',
        'email',
        'source',
        'tag',
        'assigned_to_id',
        'current_stage_id',
        'created_by',
        'notes',
        'estimated_value',
        'last_contact_at',
    ];

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function stage()
    {
        return $this->belongsTo(PipelineStage::class, 'current_stage_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
