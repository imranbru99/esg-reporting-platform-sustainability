<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntiCorruptionTraining extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'eligible_headcount' => 'integer',
        'completed_headcount' => 'integer',
        'completion_rate_pct' => 'decimal:2',
        'confirmed_corruption_incidents' => 'integer',
        'political_contributions_cents' => 'integer',
    ];
}
