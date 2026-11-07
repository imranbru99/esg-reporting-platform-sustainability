<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmissionFactor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'factor_value' => 'decimal:8',
        'co2_factor' => 'decimal:8',
        'ch4_factor' => 'decimal:8',
        'n2o_factor' => 'decimal:8',
        'uncertainty_percentage' => 'decimal:2',
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(EmissionFactorLibrary::class, 'library_id');
    }

    public function metricEntries(): HasMany
    {
        return $this->hasMany(MetricEntry::class);
    }
}
