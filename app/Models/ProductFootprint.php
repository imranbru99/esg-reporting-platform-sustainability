<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFootprint extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'carbon_footprint_kg_co2e' => 'decimal:4',
        'recycled_content_percentage' => 'decimal:2',
        'repairability_score' => 'decimal:2',
        'has_epd' => 'boolean',
        'lifecycle_stage_breakdown' => 'array',
    ];
}
