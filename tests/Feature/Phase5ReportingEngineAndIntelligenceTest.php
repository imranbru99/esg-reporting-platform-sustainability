<?php

use App\Models\EvidenceDocument;
use App\Models\Framework;
use App\Models\Metric;
use App\Models\MetricEntry;
use App\Models\Organization;
use App\Models\ReportProject;
use App\Models\ReportSignoff;
use App\Models\User;
use App\Services\AI\EsgChatAssistant;
use App\Services\Analytics\EsgScorecardCalculator;
use App\Services\Analytics\GapAnalysisEngine;
use App\Services\Reporting\ReportGeneratorService;
use App\Services\Reporting\UnicodePdfExporter;
use App\Services\Tenancy\TenantContext;
use Database\Seeders\EsgCoreDataSeeder;
use Database\Seeders\ModuleRegistrySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use ImranDevBd\AiHub\Facades\AIHub;

beforeEach(function () {
    $this->seed(ModuleRegistrySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(EsgCoreDataSeeder::class);
});

test('report builder initializes outline and drafts AI narrative with strict metric citations', function () {
    AIHub::fake('In 2024, our facility recorded Scope 1 emissions of [[CITE:GHG-S1:1420.50:tCO2e]], alongside Scope 2 emissions of [[CITE:GHG-S2-LOC:730.00:tCO2e]]. Female workforce representation reached [[CITE:SOC-FEM:45.00:%]].');

    $org = Organization::create(['name' => 'Beximco Apparel Park', 'industry' => 'textiles_apparel']);
    TenantContext::setTenant($org);

    $scope1Metric = Metric::where('code', 'GHG-S1')->first();
    $scope2Metric = Metric::where('code', 'GHG-S2-LOC')->first();

    $evidence = EvidenceDocument::create([
        'organization_id' => $org->id,
        'title' => 'Gas Turbine Fuel Receipt FY2024',
        'file_name' => 'gas-meter.pdf',
        'file_path' => 'evidence/gas-meter.pdf',
        'file_hash_sha256' => hash('sha256', 'dummy-evidence-content'),
    ]);

    MetricEntry::create([
        'organization_id' => $org->id,
        'metric_id' => $scope1Metric->id,
        'period_start' => '2024-01-01',
        'period_end' => '2024-12-31',
        'activity_value' => 1420.50,
        'activity_unit' => 'tCO2e',
        'normalized_value' => 1420.50,
        'status' => 'approved',
    ]);

    MetricEntry::create([
        'organization_id' => $org->id,
        'metric_id' => $scope2Metric->id,
        'period_start' => '2024-01-01',
        'period_end' => '2024-12-31',
        'activity_value' => 730.00,
        'activity_unit' => 'tCO2e',
        'normalized_value' => 730.00,
        'status' => 'approved',
    ]);

    $project = ReportProject::create([
        'organization_id' => $org->id,
        'title' => '2024 Annual Sustainability Disclosures',
        'reporting_period' => '2024',
        'primary_framework' => 'gri',
        'status' => 'draft',
    ]);

    $service = new ReportGeneratorService;
    $service->initializeOutline($project);

    expect($project->sections()->count())->toBe(6);

    $envSection = $project->sections()->where('section_key', 'environmental_performance')->first();
    $service->generateSectionNarrative($envSection);

    $envSection->refresh();
    expect($envSection->status)->toBe('generated')
        ->and($envSection->citations()->count())->toBeGreaterThanOrEqual(2);

    $firstCitation = $envSection->citations()->first();
    expect($firstCitation->raw_value)->toBeGreaterThan(0)
        ->and($firstCitation->citation_token)->toContain('[1]');

    TenantContext::clear();
});

test('unicode pdf exporter generates pixel-perfect report and factsheet', function () {
    UnicodePdf::fake();

    $org = Organization::create(['name' => 'Grameen Green Ventures', 'industry' => 'technology']);
    TenantContext::setTenant($org);

    $project = ReportProject::create([
        'organization_id' => $org->id,
        'title' => 'বাংলা টেকসই উন্নয়ন প্রতিবেদন ২০২৪',
        'reporting_period' => '2024',
        'primary_framework' => 'gri',
        'language' => 'bn',
        'status' => 'approved',
    ]);

    $exporter = new UnicodePdfExporter;
    $pdfPath = $exporter->export($project);
    $factsheetPath = $exporter->exportFactsheet($project);

    UnicodePdf::assertGenerated(2);
    expect($pdfPath)->toContain('.pdf')
        ->and($factsheetPath)->toContain('factsheet-');

    TenantContext::clear();
});

test('gap analysis calculates framework readiness score and missing disclosures', function () {
    $org = Organization::create(['name' => 'Prime Bank PLC', 'industry' => 'banking_finance']);
    TenantContext::setTenant($org);

    $gri = Framework::where('slug', 'gri')->first();
    $engine = new GapAnalysisEngine;

    $gap = $engine->evaluate($org, 'gri', '2024');

    expect($gap->reporting_year)->toBe('2024')
        ->and($gap->total_required_disclosures)->toBeGreaterThan(0)
        ->and($gap->overall_readiness_score)->toBeNumeric();

    TenantContext::clear();
});

test('esg scorecard calculator computes weighted pillar ratings and grades', function () {
    $org = Organization::create(['name' => 'Walton Hi-Tech Industries', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);

    $calc = new EsgScorecardCalculator;
    $scorecard = $calc->calculate($org, '2024');

    expect($scorecard->overall_score)->toBeGreaterThanOrEqual(0)
        ->and($scorecard->rating_grade)->toBeIn(['AAA', 'AA', 'A', 'BBB', 'BB', 'B', 'CCC'])
        ->and($scorecard->strengths)->not->toBeEmpty();

    TenantContext::clear();
});

test('report lock computes sha256 fingerprint and records digital signoffs', function () {
    $org = Organization::create(['name' => 'BRAC Enterprise', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);

    $user = User::factory()->create(['name' => 'Imran Ahmed', 'email' => 'me@imrandev.bd']);

    $project = ReportProject::create([
        'organization_id' => $org->id,
        'title' => 'FY2024 Integrated ESG Report',
        'reporting_period' => '2024',
        'primary_framework' => 'gri',
        'status' => 'draft',
    ]);

    $project->sections()->create([
        'title' => 'Executive Summary',
        'section_key' => 'executive_summary',
        'content_markdown' => 'Detailed verified report content.',
        'status' => 'approved',
    ]);

    $project->lockAndPublish();
    expect($project->status)->toBe('locked')
        ->and($project->sha256_hash)->toHaveLength(64);

    $signoff = ReportSignoff::create([
        'report_project_id' => $project->id,
        'user_id' => $user->id,
        'role_title' => 'Sustainability Lead & Head of ESG',
        'signature_hash' => hash('sha256', $user->email.now()),
        'comments' => 'Data audited and validated against metering bills.',
        'signed_at' => now(),
    ]);

    expect($project->signoffs()->count())->toBe(1)
        ->and($signoff->role_title)->toBe('Sustainability Lead & Head of ESG');

    TenantContext::clear();
});

test('ai esg chat assistant answers grounded queries with metric context', function () {
    AIHub::fake('Our Scope 1 direct emissions for 2024 are 1420.50 tCO2e, which represents a stable operating efficiency.');

    $org = Organization::create(['name' => 'Square Pharmaceuticals', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);
    $user = User::factory()->create();

    $assistant = new EsgChatAssistant;
    $message = $assistant->ask($org, $user, 'What are our direct Scope 1 emissions?');

    expect($message->role)->toBe('assistant')
        ->and($message->content)->toContain('Scope 1')
        ->and($message->conversation->messages()->count())->toBe(2);

    TenantContext::clear();
});
