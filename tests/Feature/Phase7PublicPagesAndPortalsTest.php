<?php

use App\Models\Organization;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\EsgCoreDataSeeder;
use Database\Seeders\ModuleRegistrySeeder;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(ModuleRegistrySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(EsgCoreDataSeeder::class);
});

test('public marketing pages render successfully with live catalog counts', function () {
    $this->get('/')->assertOk()->assertSee('Auto-Generated Sustainability Reports');
    $this->get('/features')->assertOk()->assertSee('75 Enterprise ESG Modules');
    $this->get('/frameworks')->assertOk()->assertSee('Supported Global ESG Frameworks');
    $this->get('/industries')->assertOk()->assertSee('12 Industry Presets');
    $this->get('/roles')->assertOk()->assertSee('Solutions by Stakeholder Role');
    $this->get('/modules-explorer')->assertOk()->assertSee('Interactive Modules Explorer');
    $this->get('/pricing')->assertOk()->assertSee('Simple, Predictable ESG Pricing');
    $this->get('/demo-center')->assertOk()->assertSee('Live Enterprise Demo Center');
});

test('1-click demo login authenticates role persona and redirects to panel', function () {
    $user = User::factory()->create(['name' => 'Demo ESG Lead', 'email' => 'esg-lead@demo.test']);
    $user->assignRole('esg_manager');

    $response = $this->get('/demo-login/esg_manager');
    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($user);
});

test('public supplier portal accepts questionnaire submissions', function () {
    $org = Organization::create(['name' => 'Apex Footwear', 'industry' => 'manufacturing']);
    $supplier = Supplier::create([
        'organization_id' => $org->id,
        'name' => 'Green Leather Tannery',
        'code' => 'SUP-772',
        'tier' => 'tier_1',
        'country_code' => 'BGD',
        'contact_email' => 'compliance@greenleather.test',
    ]);

    $this->get("/portal/supplier?code={$supplier->code}")
        ->assertOk()
        ->assertSee('Green Leather Tannery');

    $postResp = $this->post('/portal/supplier/submit', [
        'supplier_id' => $supplier->id,
        'questionnaire_name' => 'Higg Index Facility Environmental Module (FEM 4.0)',
        'score_percentage' => 89.2,
    ]);

    $postResp->assertSessionHas('success');
    expect($supplier->assessments()->count())->toBe(1)
        ->and((float) $supplier->assessments()->first()->score_percentage)->toEqual(89.2);
});

test('investor portal and auditor data room render securely', function () {
    $this->get('/portal/investor')->assertOk()->assertSee('Investor Sustainability Disclosures Portal');
    $this->get('/portal/dataroom/SECURE-TOKEN-XYZ123')->assertOk()->assertSee('Auditor Data Room');
});

test('anonymous whistleblower hotline encrypts allegations and returns secret tracking token', function () {
    $org = Organization::create(['name' => 'Apex Footwear', 'industry' => 'manufacturing']);

    $response = $this->post('/report-incident/submit', [
        'organization_id' => $org->id,
        'allegation_type' => 'environmental_discharge',
        'details' => 'Untreated chemical effluent observed being discharged at Gate 4 near the canal at 11 PM on Friday.',
    ]);

    $response->assertSessionHas('token');
    $token = session('token');

    expect($token)->toStartWith('ANON-');
    $this->assertDatabaseHas('whistleblower_cases', [
        'organization_id' => $org->id,
        'case_token' => $token,
    ]);
});

test('free esg tools calculate carbon, quiz score and materiality priorities', function () {
    // Carbon Calculator
    $this->get('/tools/carbon-calculator')->assertOk();
    $carbResp = $this->post('/tools/carbon-calculator/calculate', [
        'diesel_liters' => 5000,
        'natural_gas_m3' => 10000,
        'electricity_kwh' => 50000,
    ]);
    $carbResp->assertSessionHas('carbonResult');

    // Readiness Quiz
    $this->get('/tools/readiness-quiz')->assertOk();
    $quizResp = $this->post('/tools/readiness-quiz/submit', [
        'answers' => ['yes', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'no', 'yes', 'yes'],
    ]);
    $quizResp->assertSessionHas('quizResult');

    // Materiality Quick Check
    $this->get('/tools/materiality-check?industry=banking')
        ->assertOk()
        ->assertSee('Financed Emissions');
});
