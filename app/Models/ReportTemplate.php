<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'template_name',
        'type',
        'content',
    ];

    public function company()
    {
        return $this->belongsTo(Perusahaan::class, 'company_id');
    }
}
