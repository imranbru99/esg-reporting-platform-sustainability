<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CybersecurityAudit extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'audit_date' => 'date',
        'vulnerabilities_identified' => 'integer',
        'critical_severity_count' => 'integer',
        'certificate_expiry_date' => 'date',
    ];
}
