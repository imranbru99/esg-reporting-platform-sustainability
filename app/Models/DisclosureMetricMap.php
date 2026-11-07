<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DisclosureMetricMap extends Pivot
{
    protected $table = 'disclosure_metric_maps';

    protected $guarded = ['id'];

    protected $casts = [
        'formula_weight' => 'decimal:2',
    ];
}
