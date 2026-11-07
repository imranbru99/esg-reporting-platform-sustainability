<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssuranceEngagement extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'signed_off_date' => 'date',
        'dataroom_expires_at' => 'datetime',
    ];

    public function workpapers()
    {
        return $this->hasMany(AuditWorkpaper::class);
    }
}
