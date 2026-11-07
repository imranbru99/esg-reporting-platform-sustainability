<?php

namespace App\Services\Analytics;

use App\Models\Framework;
use App\Models\FrameworkDisclosure;
use App\Models\GapAnalysis;
use App\Models\MetricEntry;
use App\Models\Organization;

class GapAnalysisEngine
{
    /**
     * Compute framework readiness and gap report for an organization and reporting year.
     */
    public function evaluate(Organization $org, string $frameworkSlug, string $reportingYear): GapAnalysis
    {
        $framework = Framework::where('slug', $frameworkSlug)->first();
        if (! $framework) {
            $framework = Framework::first();
            $frameworkSlug = $framework?->slug ?? 'gri';
        }

        $disclosures = FrameworkDisclosure::where('framework_id', $framework?->id)->get();
        $totalRequired = $disclosures->count();

        // If no disclosures exist yet, assign baseline 10
        if ($totalRequired === 0) {
            $totalRequired = 10;
        }

        // Fetch metrics that have verified entries for this organization and year
        $verifiedMetricIds = MetricEntry::query()
            ->where('organization_id', $org->id)
            ->where(function ($q) use ($reportingYear) {
                $q->whereYear('period_start', $reportingYear)
                    ->orWhere('period_start', 'like', $reportingYear.'%');
            })
            ->whereIn('status', ['verified', 'approved', 'locked'])
            ->pluck('metric_id')
            ->unique();

        $completedCount = 0;
        $missing = [];

        foreach ($disclosures as $disclosure) {
            // Check if disclosure has mapped metrics with entries
            $hasData = $disclosure->metrics()
                ->whereIn('metrics.id', $verifiedMetricIds)
                ->exists();

            if ($hasData) {
                $completedCount++;
            } else {
                $missing[] = [
                    'code' => $disclosure->code,
                    'title' => $disclosure->title,
                    'mandatory' => $disclosure->is_mandatory,
                    'remediation_step' => "Collect activity data or evidence for {$disclosure->code} under {$framework->name}.",
                ];
            }
        }

        // Calculate score
        $readinessScore = $totalRequired > 0
            ? round(($completedCount / $totalRequired) * 100, 2)
            : 0.0;

        return GapAnalysis::updateOrCreate(
            [
                'organization_id' => $org->id,
                'framework_slug' => $frameworkSlug,
                'reporting_year' => $reportingYear,
            ],
            [
                'overall_readiness_score' => $readinessScore,
                'completed_disclosures' => $completedCount,
                'total_required_disclosures' => $totalRequired,
                'missing_disclosures' => $missing,
                'details_json' => [
                    'framework_name' => $framework?->name ?? strtoupper($frameworkSlug),
                    'framework_version' => $framework?->version ?? '2024',
                    'remediation_priority_count' => count(array_filter($missing, fn ($m) => $m['mandatory'] ?? false)),
                ],
                'generated_at' => now(),
            ]
        );
    }
}
