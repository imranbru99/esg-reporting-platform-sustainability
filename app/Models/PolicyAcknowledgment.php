<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyAcknowledgment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    public function policy()
    {
        return $this->belongsTo(CorporatePolicy::class, 'corporate_policy_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
