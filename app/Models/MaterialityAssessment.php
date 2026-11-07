<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialityAssessment extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'impact_materiality_score' => 'decimal:2',
        'financial_materiality_score' => 'decimal:2',
        'is_double_material' => 'boolean',
    ];

    public function topic()
    {
        return $this->belongsTo(MaterialityTopic::class, 'materiality_topic_id');
    }
}
