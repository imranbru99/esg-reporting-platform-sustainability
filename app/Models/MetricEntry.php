<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetricEntry extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'activity_value' => 'decimal:4',
        'normalized_value' => 'decimal:4',
        'calculated_co2e' => 'decimal:6',
        'factor_snapshot' => 'array',
        'is_anomaly' => 'boolean',
    ];

    public function metric(): BelongsTo
    {
        return $this->belongsTo(Metric::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function collectionRequest(): BelongsTo
    {
        return $this->belongsTo(DataCollectionRequest::class, 'collection_request_id');
    }

    public function emissionFactor(): BelongsTo
    {
        return $this->belongsTo(EmissionFactor::class);
    }

    public function getValueAttribute()
    {
        return $this->attributes['normalized_value'] ?? $this->attributes['activity_value'] ?? 0;
    }

    public function getReportingPeriodAttribute()
    {
        return $this->period_start ? $this->period_start->format('Y') : null;
    }

    public function evidence()
    {
        return $this->belongsTo(EvidenceDocument::class, 'evidence_id');
    }

    public function evidenceDocuments(): HasMany
    {
        return $this->hasMany(EvidenceDocument::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by_user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}
