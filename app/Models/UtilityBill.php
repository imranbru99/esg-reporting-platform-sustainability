<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityBill extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
        'consumption_quantity' => 'decimal:4',
        'ai_extracted_data' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }
}
