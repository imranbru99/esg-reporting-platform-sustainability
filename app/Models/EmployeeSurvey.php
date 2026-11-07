<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSurvey extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'launch_date' => 'date',
        'close_date' => 'date',
        'total_responses' => 'integer',
        'enps_score' => 'integer',
        'satisfaction_rating' => 'decimal:2',
    ];
}
