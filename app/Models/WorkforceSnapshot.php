<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkforceSnapshot extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'female_leadership_pct' => 'decimal:2',
        'turnover_rate_pct' => 'decimal:2',
        'gender_pay_gap_pct' => 'decimal:2',
        'living_wage_compliant' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
