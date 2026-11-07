<?php

namespace App\Services\ESG;

use App\Models\EmissionFactor;

class CalculationEngine
{
    /**
     * Calculate greenhouse gas emissions with full auditable parameter snapshots.
     *
     * Formula:
     * Emissions (tCO2e) = [ Activity_Quantity_in_Factor_Unit * Emission_Factor_Value * (1 - Reduction_Percent) ] / 1000 (if factor in kg)
     *
     * @param  float  $activityValue  Raw activity quantity entered by site manager
     * @param  string  $activityUnit  Unit of raw activity (e.g. 'm3', 'L', 'kWh', 'MWh', 'km', 'miles', 'ton_us')
     * @param  EmissionFactor  $factor  The authoritative emission factor model
     * @param  float  $reductionPercentage  Verified green tariff, carbon offset or blend reduction (0-100%)
     * @return array<string, mixed> Auditable calculation packet
     */
    public static function calculateEmission(
        float $activityValue,
        string $activityUnit,
        EmissionFactor $factor,
        float $reductionPercentage = 0.0
    ): array {
        // Parse the denominator unit from factor_unit (e.g. 'kgCO2e/L' -> 'L', 'kgCO2e/kWh' -> 'kWh')
        $unitParts = explode('/', $factor->factor_unit);
        $factorNumerator = trim($unitParts[0] ?? 'kgCO2e');
        $factorDenominator = trim($unitParts[1] ?? $activityUnit);

        // Convert raw activity unit into the factor's denominator unit
        $convertedActivity = $activityValue;
        if (strtolower($activityUnit) !== strtolower($factorDenominator)) {
            $convertedActivity = UnitConverter::convert($activityValue, $activityUnit, $factorDenominator);
        }

        // Apply reduction factor if any (e.g. 20% biodiesel blend or certified green tariff)
        $effectiveMultiplier = (1.0 - (max(0.0, min(100.0, $reductionPercentage)) / 100.0));
        $rawEmission = $convertedActivity * (float) $factor->factor_value * $effectiveMultiplier;

        // Normalize emission output to metric tonnes of CO2 equivalent (tCO2e)
        $tco2e = strtolower($factorNumerator) === 'tco2e'
            ? $rawEmission
            : ($rawEmission / 1000.0);

        $kgco2e = $tco2e * 1000.0;

        $formulaAudit = sprintf(
            '%s %s (converted to %s %s) of %s × %s %s [factor: %s %s] = %s tCO2e (±%s%% uncertainty)',
            number_format($activityValue, 4),
            $activityUnit,
            number_format($convertedActivity, 4),
            $factorDenominator,
            $factor->activity_name,
            number_format((float) $factor->factor_value, 6),
            $factor->factor_unit,
            $factor->library->name ?? 'Custom',
            $factor->applicable_year,
            number_format($tco2e, 6),
            $factor->uncertainty_percentage
        );

        return [
            'tco2e' => round($tco2e, 6),
            'kgco2e' => round($kgco2e, 4),
            'converted_activity' => round($convertedActivity, 4),
            'factor_denominator' => $factorDenominator,
            'reduction_percentage' => $reductionPercentage,
            'formula_audit' => $formulaAudit,
            'snapshot' => [
                'factor_id' => $factor->id,
                'library' => $factor->library->name ?? 'Custom',
                'publisher' => $factor->library->publisher ?? 'EPA/DEFRA',
                'applicable_year' => $factor->applicable_year,
                'factor_value' => (float) $factor->factor_value,
                'factor_unit' => $factor->factor_unit,
                'scope' => $factor->scope,
                'uncertainty_percentage' => (float) $factor->uncertainty_percentage,
                'calculated_at' => now()->toIso8601String(),
            ],
        ];
    }

    /**
     * Calculate Year-over-Year (YoY) variance between reporting periods.
     */
    public static function calculateYoY(float $currentPeriod, float $previousPeriod): array
    {
        $diff = $currentPeriod - $previousPeriod;
        $pct = $previousPeriod > 0 ? (($diff / $previousPeriod) * 100.0) : 0.0;

        return [
            'current' => $currentPeriod,
            'previous' => $previousPeriod,
            'absolute_variance' => round($diff, 4),
            'percentage_variance' => round($pct, 2),
            'direction' => $diff > 0.0001 ? 'increase' : ($diff < -0.0001 ? 'decrease' : 'neutral'),
        ];
    }

    /**
     * Calculate Carbon & Energy Intensity ratios.
     *
     * @param  float  $totalEmissions  Total tCO2e
     * @param  float  $normalizerValue  Headcount, square meters, revenue in millions, or units produced
     */
    public static function calculateIntensity(float $totalEmissions, float $normalizerValue): float
    {
        if ($normalizerValue <= 0) {
            return 0.0;
        }

        return round($totalEmissions / $normalizerValue, 4);
    }
}
