<?php

use App\Models\EmissionFactor;
use App\Models\Metric;
use App\Models\RegulationWatch;
use App\Services\ESG\CalculationEngine;
use App\Services\ESG\UnitConverter;
use Database\Seeders\EsgCoreDataSeeder;
use Database\Seeders\ModuleRegistrySeeder;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(ModuleRegistrySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(EsgCoreDataSeeder::class);
});

test('unit converter accurately converts across physical dimensions', function () {
    // Mass: 5,000 kg = 5 metric tonnes
    expect(UnitConverter::convert(5000, 'kg', 't'))->toEqual(5.0);
    // Mass: 1 metric tonne = 1000 kg
    expect(UnitConverter::convert(1, 't', 'kg'))->toEqual(1000.0);

    // Energy: 1 MWh = 1,000 kWh
    expect(UnitConverter::convert(1, 'MWh', 'kWh'))->toEqual(1000.0);

    // Volume: 2 m³ = 2,000 L
    expect(UnitConverter::convert(2, 'm3', 'L'))->toEqual(2000.0);

    // Incompatible dimension throws exception
    expect(fn () => UnitConverter::convert(10, 'kg', 'kWh'))
        ->toThrow(InvalidArgumentException::class);
});

test('calculation engine accurately computes scope 1 diesel emissions with auditable snapshot', function () {
    $factor = EmissionFactor::where('activity_name', 'like', '%Diesel%')->firstOrFail();

    // 1,000 Litres of diesel at 2.68787 kgCO2e/L = 2.68787 tCO2e
    $result = CalculationEngine::calculateEmission(1000.0, 'L', $factor);

    expect($result['tco2e'])->toEqual(2.68787)
        ->and($result['kgco2e'])->toEqual(2687.87)
        ->and($result['converted_activity'])->toEqual(1000.0)
        ->and($result['snapshot']['applicable_year'])->toEqual('2024')
        ->and($result['formula_audit'])->toContain('Diesel');
});

test('calculation engine automatically handles unit conversions during calculation', function () {
    $factor = EmissionFactor::where('activity_name', 'like', '%Diesel%')->firstOrFail();

    // Input in cubic meters (m³) where 1 m³ = 1,000 Litres
    $result = CalculationEngine::calculateEmission(2.5, 'm3', $factor);

    // 2.5 m³ = 2,500 L * 2.68787 kgCO2e / 1000 = 6.719675 tCO2e
    expect($result['converted_activity'])->toEqual(2500.0)
        ->and($result['tco2e'])->toEqual(6.719675);
});

test('calculation engine calculates scope 2 electricity and green tariff reduction', function () {
    $factor = EmissionFactor::where('activity_name', 'like', '%Bangladesh%')->firstOrFail();

    // 10,000 kWh of grid electricity without reduction
    $standard = CalculationEngine::calculateEmission(10000.0, 'kWh', $factor, 0.0);
    // 10,000 * 0.5842 / 1000 = 5.842 tCO2e
    expect($standard['tco2e'])->toEqual(5.842);

    // 10,000 kWh with certified 30% solar on-site offset / green tariff
    $reduced = CalculationEngine::calculateEmission(10000.0, 'kWh', $factor, 30.0);
    // 5.842 * (1 - 0.30) = 4.0894 tCO2e
    expect($reduced['tco2e'])->toEqual(4.0894);
});

test('calculation engine computes YoY variance and intensity metrics', function () {
    $yoy = CalculationEngine::calculateYoY(currentPeriod: 120.0, previousPeriod: 150.0);
    expect($yoy['absolute_variance'])->toEqual(-30.0)
        ->and($yoy['percentage_variance'])->toEqual(-20.0)
        ->and($yoy['direction'])->toBe('decrease');

    // 500 tCO2e produced by 250 employees = 2.0 tCO2e/employee
    $intensity = CalculationEngine::calculateIntensity(500.0, 250.0);
    expect($intensity)->toEqual(2.0);
});

test('cross-framework mapping links a single metric to multiple international standards', function () {
    $scope1Metric = Metric::where('code', 'GHG-S1')->firstOrFail();
    $disclosures = $scope1Metric->disclosures;

    $codes = $disclosures->pluck('code')->toArray();

    // Single Scope 1 metric satisfies GRI, ISSB, and CSRD simultaneously
    expect($codes)->toContain('GRI 305-1')
        ->and($codes)->toContain('IFRS S2 para 29a')
        ->and($codes)->toContain('ESRS E1-6');
});

test('regulation watch logs and tracks framework amendments in review queue', function () {
    $feed = RegulationWatch::where('framework_slug', 'csrd_esrs')->firstOrFail();

    expect($feed->status)->toBe('pending_review')
        ->and($feed->impact_level)->toBe('high')
        ->and($feed->action_recommendations)->toBeArray()
        ->and(count($feed->action_recommendations))->toBeGreaterThan(0);
});
