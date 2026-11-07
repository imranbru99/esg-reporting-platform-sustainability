<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FrameworkDisclosure extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class);
    }

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'disclosure_metric_maps')
            ->withPivot('notes', 'formula_weight')
            ->withTimestamps();
    }
}
