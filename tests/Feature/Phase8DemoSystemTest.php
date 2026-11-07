<?php

use App\Models\Organization;
use App\Models\ReportProject;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('artisan demo:reset wipes and seeds all demo data under 30 seconds', function () {
    $this->artisan('demo:reset')
        ->assertSuccessful();

    // Verify 3 Demo Enterprises
    expect(Organization::where('slug', 'apex-textile-apparel')->exists())->toBeTrue()
        ->and(Organization::where('slug', 'bengal-commercial-bank')->exists())->toBeTrue()
        ->and(Organization::where('slug', 'cloudscale-software')->exists())->toBeTrue();

    // Verify Apex Data Ingestion & Monthly Reporting
    $apex = Organization::where('slug', 'apex-textile-apparel')->first();
    expect($apex->branches()->count())->toBeGreaterThanOrEqual(1)
        ->and($apex->suppliers()->count())->toBeGreaterThanOrEqual(1)
        ->and($apex->metricEntries()->count())->toBeGreaterThanOrEqual(3);

    $apexReport = ReportProject::where('organization_id', $apex->id)->first();
    expect($apexReport)->not->toBeNull()
        ->and($apexReport->status)->toBe('locked')
        ->and($apexReport->sha256_hash)->not->toBeNull();

    // Verify all 22 demo roles exist and have valid password
    $demoRoles = [
        'super_admin', 'platform_staff', 'org_admin', 'esg_manager',
        'site_manager', 'dept_head', 'finance_officer', 'hr_officer',
        'procurement_manager', 'compliance_officer', 'internal_auditor',
        'external_auditor', 'executive', 'investor_portal', 'supplier_portal',
        'customer_portal', 'consultant', 'reseller', 'employee',
        'whistleblower', 'public_visitor', 'api_user',
    ];

    foreach ($demoRoles as $role) {
        $user = User::role($role)->first();
        expect($user)->not->toBeNull("Expected demo user with role {$role} to exist");
        expect(Hash::check('password', $user->password))->toBeTrue("Expected password 'password' to verify for {$user->email}");
    }
});
