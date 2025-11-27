<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerStageHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'company_id',
        'from_stage_id',
        'to_stage_id',
        'changed_by',
        'note',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }

    public function fromStage()
    {
        return $this->belongsTo(PipelineStage::class, 'from_stage_id');
    }

    public function toStage()
    {
        return $this->belongsTo(PipelineStage::class, 'to_stage_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}