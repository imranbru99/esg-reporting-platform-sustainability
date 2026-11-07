<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirEmission extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'monitoring_date' => 'date',
        'nox_kg' => 'decimal:4',
        'sox_kg' => 'decimal:4',
        'particulate_matter_pm_kg' => 'decimal:4',
        'volatile_organic_compounds_voc_kg' => 'decimal:4',
        'permit_limit_exceeded' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
