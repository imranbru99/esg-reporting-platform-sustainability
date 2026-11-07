<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceObligation extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'fine_penalty_cents' => 'integer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
