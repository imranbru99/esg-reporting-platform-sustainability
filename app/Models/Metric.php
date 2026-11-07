<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Metric extends Model
{
    use HasFactory, HasUniversalSlug;

    protected $guarded = ['id'];

    protected $casts = [
        'is_standard' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->skipIfFilled();
    }

    public function disclosures(): BelongsToMany
    {
        return $this->belongsToMany(FrameworkDisclosure::class, 'disclosure_metric_maps')
            ->withPivot('notes', 'formula_weight')
            ->withTimestamps();
    }

    public function entries(): HasMany
    {
        return $this->hasMany(MetricEntry::class);
    }
}
