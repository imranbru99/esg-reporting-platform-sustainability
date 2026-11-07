<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportNarrativeCitation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function section()
    {
        return $this->belongsTo(ReportSection::class, 'report_section_id');
    }

    public function metricEntry()
    {
        return $this->belongsTo(MetricEntry::class, 'metric_entry_id');
    }

    public function sourceEvidence()
    {
        return $this->belongsTo(EvidenceDocument::class, 'source_evidence_id');
    }

    public function contributor()
    {
        return $this->belongsTo(User::class, 'contributor_user_id');
    }
}
