<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsrProject extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'budget_cents' => 'integer',
        'actual_spend_cents' => 'integer',
        'beneficiaries_count' => 'integer',
    ];
}
