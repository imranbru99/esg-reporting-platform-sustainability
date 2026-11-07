<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSummaryLog extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'work_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'rest_day_provided' => 'boolean',
        'excessive_ot_alert' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
