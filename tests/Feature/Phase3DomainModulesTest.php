<?php

use App\Models\BoardMember;
use App\Models\Branch;
use App\Models\ComplianceObligation;
use App\Models\CorporatePolicy;
use App\Models\OhsIncident;
use App\Models\Organization;
use App\Models\PolicyAcknowledgment;
use App\Models\ProductFootprint;
use App\Models\User;
use App\Models\UtilityBill;
use App\Models\WasteManifest;
use App\Models\WaterRecord;
use App\Models\WhistleblowerCase;
use App\Models\WorkforceSnapshot;
use App\Services\AI\UtilityBillExtractor;
use App\Services\Attendance\AttendanceSyncService;
use App\Services\Tenancy\TenantContext;
use Database\Seeders\ModuleRegistrySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->seed(ModuleRegistrySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
});

test('attendance sync service pulls punches and monitors overtime compliance', function () {
    $org = Organization::create(['name' => 'Knitwear Factory Ltd', 'industry' => 'textiles_apparel']);
    $branch = Branch::create(['organization_id' => $org->id, 'name' => 'Gazipur Plant 1', 'facility_type' => 'factory']);

    $service = new AttendanceSyncService;
    $result = $service->syncBranchAttendance($branch);

    expect($result['synced_records'])->toBeGreaterThan(0)
        ->and($result['branch'])->toBe('Gazipur Plant 1');

    $this->assertDatabaseHas('attendance_summary_logs', [
        'organization_id' => $org->id,
        'branch_id' => $branch->id,
        'employee_code' => 'EMP-1001',
    ]);
});

test('ai utility bill extractor parses consumption data into structured bill record', function () {
    $org = Organization::create(['name' => 'Green Bank HQ', 'industry' => 'banking_finance']);
    $bill = UtilityBill::create([
        'organization_id' => $org->id,
        'utility_type' => 'electricity',
        'consumption_quantity' => 0,
        'consumption_unit' => 'kWh',
        'billing_period_start' => now()->startOfMonth(),
        'billing_period_end' => now()->endOfMonth(),
        'ocr_status' => 'pending',
    ]);

    $rawInvoiceText = 'ELECTRICITY UTILITY INVOICE #INV-9821. Billing: 2024-05-01 to 2024-05-31. Consumed: 18,450 kWh. Total: $2,450.00 USD.';

    $extractor = new UtilityBillExtractor;
    $data = $extractor->extractFromText($rawInvoiceText, $bill);

    $bill->refresh();
    expect($bill->ocr_status)->toBe('processed')
        ->and($bill->consumption_quantity)->toBeGreaterThan(0)
        ->and($bill->ai_extracted_data)->toBeArray();
});

test('environmental water, waste and product DPP footprints record correctly', function () {
    $org = Organization::create(['name' => 'Eco Textiles Corp', 'industry' => 'textiles_apparel']);
    TenantContext::setTenant($org);

    // Water
    $water = WaterRecord::create([
        'organization_id' => $org->id,
        'period_start' => '2024-01-01',
        'period_end' => '2024-01-31',
        'withdrawal_source' => 'groundwater',
        'withdrawal_volume_m3' => 15000.0,
        'discharge_volume_m3' => 12000.0,
        'recycled_volume_m3' => 3000.0,
        'water_stress_level' => 'high',
    ]);
    expect($water->withdrawal_volume_m3)->toEqual(15000.0);

    // Waste
    $waste = WasteManifest::create([
        'organization_id' => $org->id,
        'waste_stream' => 'Cotton Fabric Off-cuts',
        'is_hazardous' => false,
        'weight_tonnes' => 45.5,
        'disposal_route' => 'recycling',
        'circular_recovery_pct' => 85.0,
        'disposal_date' => '2024-01-20',
    ]);
    expect($waste->circular_recovery_pct)->toEqual(85.0);

    // Product DPP
    $product = ProductFootprint::create([
        'organization_id' => $org->id,
        'product_name' => 'Organic Cotton Polo Shirt',
        'sku' => 'POLO-ECO-01',
        'dpp_token' => 'DPP-'.Str::random(12),
        'carbon_footprint_kg_co2e' => 4.25,
        'recycled_content_percentage' => 30.0,
        'repairability_score' => 8.5,
        'has_epd' => true,
    ]);
    expect($product->carbon_footprint_kg_co2e)->toEqual(4.25);

    TenantContext::clear();
});

test('social workforce, OHS incidents and whistleblower case tracking work as expected', function () {
    $org = Organization::create(['name' => 'Apex Manufacturing', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);

    // Workforce
    $wf = WorkforceSnapshot::create([
        'organization_id' => $org->id,
        'reporting_year' => '2024',
        'total_headcount' => 1200,
        'female_headcount' => 720,
        'female_leadership_pct' => 45.0,
        'living_wage_compliant' => true,
    ]);
    expect($wf->total_headcount)->toBe(1200);

    // OHS
    $incident = OhsIncident::create([
        'organization_id' => $org->id,
        'incident_number' => 'INC-2024-001',
        'incident_date' => '2024-02-14',
        'incident_type' => 'near_miss',
        'severity' => 'low',
        'description' => 'Loose electrical cord reported near wet processing floor.',
        'lost_work_days' => 0,
        'status' => 'resolved',
    ]);
    expect($incident->incident_number)->toBe('INC-2024-001');

    // Whistleblower
    $token = 'WHISTLE-'.Str::random(16);
    $case = WhistleblowerCase::create([
        'organization_id' => $org->id,
        'case_token' => $token,
        'category' => 'safety_violation',
        'subject' => 'Fire door blocked in storage aisle 4',
        'report_details' => 'Emergency exit was temporarily locked during inventory restock.',
        'priority' => 'urgent',
    ]);
    expect($case->case_token)->toBe($token)
        ->and($case->status)->toBe('submitted');

    TenantContext::clear();
});

test('governance board independence, policies and compliance permits are enforced', function () {
    $org = Organization::create(['name' => 'First Dhaka Bank PLC', 'industry' => 'banking_finance']);
    TenantContext::setTenant($org);

    // Board Member
    $board = BoardMember::create([
        'organization_id' => $org->id,
        'name' => 'Prof. Dr. Ayesha Rahman',
        'designation' => 'Independent Director',
        'is_independent' => true,
        'gender' => 'female',
        'esg_oversight_role' => true,
        'meeting_attendance_percentage' => 100.0,
    ]);
    expect($board->is_independent)->toBeTrue();

    // Policy & Acknowledgment
    $policy = CorporatePolicy::create([
        'organization_id' => $org->id,
        'title' => 'Anti-Bribery and Corruption Code',
        'policy_type' => 'anti_bribery',
        'version' => '2.0',
        'effective_date' => '2024-01-01',
    ]);

    $user = User::create([
        'name' => 'Officer Kamal',
        'email' => 'kamal@bank.test',
        'password' => 'secret123',
        'organization_id' => $org->id,
    ]);

    $ack = PolicyAcknowledgment::create([
        'corporate_policy_id' => $policy->id,
        'user_id' => $user->id,
        'acknowledged_at' => now(),
        'ip_address' => '127.0.0.1',
    ]);
    expect($policy->acknowledgments()->count())->toBe(1);

    // Compliance
    $permit = ComplianceObligation::create([
        'organization_id' => $org->id,
        'title' => 'Banking Environmental & Social Risk License',
        'regulatory_authority' => 'Bangladesh Bank',
        'permit_number' => 'BB-SFD-902',
        'expiry_date' => now()->addYear(),
        'status' => 'active',
    ]);
    expect($permit->status)->toBe('active');

    TenantContext::clear();
});
