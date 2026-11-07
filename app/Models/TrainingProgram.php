<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'duration_hours' => 'decimal:2',
        'conducted_date' => 'date',
        'attendee_count' => 'integer',
        'attendance_synced_via_hub' => 'boolean',
    ];
}
