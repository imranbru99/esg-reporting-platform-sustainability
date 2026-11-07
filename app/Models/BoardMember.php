<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_independent' => 'boolean',
        'appointment_date' => 'date',
        'committee_memberships' => 'array',
        'esg_oversight_role' => 'boolean',
        'executive_remuneration_esg_linked' => 'boolean',
        'meeting_attendance_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
