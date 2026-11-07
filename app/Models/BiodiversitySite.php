<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodiversitySite extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_near_protected_area' => 'boolean',
        'distance_to_protected_area_km' => 'decimal:2',
        'total_site_hectares' => 'decimal:2',
        'restored_habitat_hectares' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
