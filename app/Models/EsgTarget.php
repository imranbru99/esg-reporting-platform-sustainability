<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsgTarget extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'baseline_value' => 'decimal:4',
        'target_value' => 'decimal:4',
        'current_value' => 'decimal:4',
        'progress_percentage' => 'decimal:2',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
