<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporatePolicy extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'effective_date' => 'date',
        'next_review_date' => 'date',
        'mandatory_acknowledgment' => 'boolean',
        'acknowledgments_count' => 'integer',
    ];

    public function acknowledgments()
    {
        return $this->hasMany(PolicyAcknowledgment::class);
    }
}
