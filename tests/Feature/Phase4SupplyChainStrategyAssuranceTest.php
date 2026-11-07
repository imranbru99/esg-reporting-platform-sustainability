<?php

use App\Models\AssuranceEngagement;
use App\Models\AuditWorkpaper;
use App\Models\MaterialityAssessment;
use App\Models\MaterialityTopic;
use App\Models\Metric;
use App\Models\Organization;
use App\Models\RestatementLog;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use App\Models\SupplierScope3Request;
use App\Services\Scraper\RegulationCatalogScraper;
use App\Services\Strategy\DoubleMaterialityMatrix;
use App\Services\Tenancy\TenantContext;
use Database\Seeders\EsgCoreDataSeeder;
use Database\Seeders\ModuleRegistrySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Http;
use ImranDevBd\AiHub\Facades\AIHub;

beforeEach(function () {
    $this->seed(ModuleRegistrySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(EsgCoreDataSeeder::class);
});

test('double materiality matrix evaluates topics and categorizes into quadrants', function () {
    $org = Organization::create(['name' => 'Apex Footwear Ltd', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);

    $topic1 = MaterialityTopic::create([
        'organization_id' => $org->id,
        'name' => 'Wastewater Discharge & Chemical Treatment',
        'category' => 'environmental',
    ]);

    $topic2 = MaterialityTopic::create([
        'organization_id' => $org->id,
        'name' => 'Corporate Philanthropy',
        'category' => 'social',
    ]);

    // High Impact & High Financial = Double Material
    MaterialityAssessment::create([
        'organization_id' => $org->id,
        'materiality_topic_id' => $topic1->id,
        'reporting_year' => '2024',
        'impact_materiality_score' => 4.5,
        'financial_materiality_score' => 4.2,
        'is_double_material' => true,
    ]);

    // Low Impact & Low Financial = Monitoring
    MaterialityAssessment::create([
        'organization_id' => $org->id,
        'materiality_topic_id' => $topic2->id,
        'reporting_year' => '2024',
        'impact_materiality_score' => 1.8,
        'financial_materiality_score' => 1.5,
        'is_double_material' => false,
    ]);

    $matrix = DoubleMaterialityMatrix::generateMatrix($org, '2024');

    expect($matrix['total_topics'])->toBe(2)
        ->and($matrix['double_material_count'])->toBe(1)
        ->and($matrix['quadrants']['double_material'][0]['topic'])->toBe('Wastewater Discharge & Chemical Treatment')
        ->and($matrix['quadrants']['monitoring'][0]['topic'])->toBe('Corporate Philanthropy');

    TenantContext::clear();
});

test('supplier esg assessment and scope 3 data request flow smoothly', function () {
    $org = Organization::create(['name' => 'Square Textiles PLC', 'industry' => 'textiles_apparel']);
    TenantContext::setTenant($org);

    $supplier = Supplier::create([
        'organization_id' => $org->id,
        'name' => 'Cotton Mills Delta',
        'code' => 'SUP-981',
        'tier' => 'tier_2',
        'country_code' => 'BGD',
        'contact_email' => 'compliance@cottondelta.test',
        'esg_risk_rating' => 'low',
        'portal_access_enabled' => true,
    ]);

    $assessment = SupplierAssessment::create([
        'organization_id' => $org->id,
        'supplier_id' => $supplier->id,
        'questionnaire_name' => 'Tier 2 Higg Index FEM Verification',
        'score_percentage' => 88.5,
        'status' => 'approved',
    ]);

    $scope3Req = SupplierScope3Request::create([
        'organization_id' => $org->id,
        'supplier_id' => $supplier->id,
        'reporting_year' => '2024',
        'spend_amount_cents' => 50000000, // $500,000 USD
        'allocated_emissions_tco2e' => 1245.8,
        'calculation_method' => 'spend_based',
        'status' => 'verified',
    ]);

    expect($supplier->assessments()->count())->toBe(1)
        ->and($assessment->score_percentage)->toEqual(88.5)
        ->and($scope3Req->allocated_emissions_tco2e)->toEqual(1245.8);

    TenantContext::clear();
});

test('assurance workspace records audit engagements, workpapers and restatement change log', function () {
    $org = Organization::create(['name' => 'Bengal Commercial Bank', 'industry' => 'banking_finance']);
    TenantContext::setTenant($org);

    $engagement = AssuranceEngagement::create([
        'organization_id' => $org->id,
        'title' => 'FY2024 Independent Sustainability Assurance',
        'audit_firm' => 'KPMG Global',
        'standard' => 'ISAE 3000',
        'assurance_level' => 'limited',
        'reporting_period_covered' => '2024',
        'status' => 'signed_off',
        'signed_off_date' => now()->toDateString(),
    ]);

    $workpaper = AuditWorkpaper::create([
        'assurance_engagement_id' => $engagement->id,
        'disclosure_code' => 'GRI 305-1',
        'title' => 'Verification of Direct Scope 1 Fuel Meter Receipts',
        'audit_procedure_description' => 'Sample tested 15 diesel invoices against physical generator logbooks.',
        'finding_type' => 'no_exception',
        'auditor_notes' => 'All activity quantities reconciled with 100% accuracy.',
    ]);

    expect($engagement->workpapers()->count())->toBe(1)
        ->and($workpaper->finding_type)->toBe('no_exception');

    $metric = Metric::first();
    $restatement = RestatementLog::create([
        'organization_id' => $org->id,
        'metric_id' => $metric->id,
        'reporting_year' => '2023',
        'original_value' => 1500.0,
        'restated_value' => 1650.0,
        'unit' => 'tCO2e',
        'justification_reason' => 'Subsequent inclusion of regional warehouse generator data not captured in baseline.',
        'impact_summary' => '+10% Scope 1 baseline adjustment under GHG Protocol recalculation policy.',
    ]);

    expect($restatement->restated_value)->toEqual(1650.0);

    TenantContext::clear();
});

test('online regulation catalog scraper populates review queue', function () {
    Http::fake([
        'https://example.com/*' => Http::response('<html><head><title>CSRD EFRAG Standards Update</title></head><body><h1>CSRD Standards</h1><p>Full European sustainability reporting standards text and guidance.</p></body></html>', 200),
    ]);
    AIHub::fake('{"title": "CSRD Guidance Update", "short_description": "Summary", "long_description": "Details"}');

    $scraper = new RegulationCatalogScraper;
    $watch = $scraper->scrapeRegulationUpdate(
        'https://example.com/csrd-amendments',
        'csrd_esrs'
    );

    expect($watch->status)->toBe('pending_review')
        ->and($watch->framework_slug)->toBe('csrd_esrs')
        ->and($watch->headline)->not->toBeEmpty();
});
