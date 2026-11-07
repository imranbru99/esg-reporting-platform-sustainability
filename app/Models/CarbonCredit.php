<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonCredit extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tonnes_purchased' => 'decimal:4',
        'tonnes_retired' => 'decimal:4',
        'cost_per_tonne_cents' => 'integer',
        'retirement_date' => 'date',
    ];
}
