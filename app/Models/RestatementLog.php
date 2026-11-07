<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestatementLog extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'original_value' => 'decimal:4',
        'restated_value' => 'decimal:4',
    ];

    public function metric()
    {
        return $this->belongsTo(Metric::class);
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}
