<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteManifest extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_hazardous' => 'boolean',
        'weight_tonnes' => 'decimal:4',
        'circular_recovery_pct' => 'decimal:2',
        'disposal_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
