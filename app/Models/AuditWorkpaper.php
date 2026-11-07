<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditWorkpaper extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function engagement()
    {
        return $this->belongsTo(AssuranceEngagement::class, 'assurance_engagement_id');
    }

    public function auditorUser()
    {
        return $this->belongsTo(User::class, 'auditor_user_id');
    }
}
