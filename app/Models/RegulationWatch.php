<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegulationWatch extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'action_recommendations' => 'array',
        'published_at' => 'datetime',
    ];

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}
