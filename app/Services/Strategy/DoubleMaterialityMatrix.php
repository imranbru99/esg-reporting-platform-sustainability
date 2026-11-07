<?php

namespace App\Services\Strategy;

use App\Models\MaterialityAssessment;
use App\Models\Organization;

class DoubleMaterialityMatrix
{
    /**
     * Compute and categorize double materiality matrix coordinates for an organization.
     *
     * @return array<string, mixed>
     */
    public static function generateMatrix(Organization $organization, string $reportingYear): array
    {
        $assessments = MaterialityAssessment::with('topic')
            ->where('organization_id', $organization->id)
            ->where('reporting_year', $reportingYear)
            ->get();

        $quadrants = [
            'double_material' => [], // High Impact (>= 3.0) & High Financial (>= 3.0)
            'impact_material' => [], // High Impact (>= 3.0) & Low Financial (< 3.0)
            'financial_material' => [], // Low Impact (< 3.0) & High Financial (>= 3.0)
            'monitoring' => [], // Low Impact & Low Financial
        ];

        $points = [];

        foreach ($assessments as $item) {
            $impact = (float) $item->impact_materiality_score;
            $financial = (float) $item->financial_materiality_score;
            $topicName = $item->topic->name ?? 'Unknown Topic';
            $category = $item->topic->category ?? 'environmental';

            $isHighImpact = $impact >= 3.0;
            $isHighFinancial = $financial >= 3.0;

            if ($isHighImpact && $isHighFinancial) {
                $quadrantKey = 'double_material';
            } elseif ($isHighImpact) {
                $quadrantKey = 'impact_material';
            } elseif ($isHighFinancial) {
                $quadrantKey = 'financial_material';
            } else {
                $quadrantKey = 'monitoring';
            }

            $point = [
                'id' => $item->id,
                'topic' => $topicName,
                'category' => $category,
                'x_impact' => $impact,
                'y_financial' => $financial,
                'quadrant' => $quadrantKey,
                'is_double_material' => $item->is_double_material,
            ];

            $quadrants[$quadrantKey][] = $point;
            $points[] = $point;
        }

        return [
            'reporting_year' => $reportingYear,
            'total_topics' => count($points),
            'double_material_count' => count($quadrants['double_material']),
            'quadrants' => $quadrants,
            'scatter_points' => $points,
        ];
    }
}
