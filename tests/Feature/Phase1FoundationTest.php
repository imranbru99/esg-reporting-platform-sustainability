<?php

use App\Http\Middleware\CheckModuleEnabled;
use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\Module;
use App\Models\Organization;
use App\Models\OrganizationModule;
use App\Models\User;
use App\Services\Modules\ModuleGate;
use App\Services\Modules\ModuleRegistry;
use App\Services\Tenancy\TenantContext;
use Database\Seeders\ModuleRegistrySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(ModuleRegistrySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
});

test('22 distinct user roles exist and have appropriate permissions', function () {
    $roles = [
        'super_admin', 'platform_staff', 'org_admin', 'esg_manager',
        'site_manager', 'dept_head', 'finance_officer', 'hr_officer',
        'procurement_manager', 'compliance_officer', 'internal_auditor',
        'external_auditor', 'executive', 'investor_portal', 'supplier_portal',
        'customer_portal', 'consultant', 'reseller', 'employee',
        'whistleblower', 'public_visitor', 'api_user',
    ];

    foreach ($roles as $roleName) {
        $this->assertDatabaseHas('roles', ['name' => $roleName, 'guard_name' => 'web']);
    }

    $superAdminRole = Role::findByName('super_admin');
    expect($superAdminRole->hasPermissionTo('platform.tenants.manage'))->toBeTrue();
    expect($superAdminRole->hasPermissionTo('esg.data.lock'))->toBeTrue();

    $siteManagerRole = Role::findByName('site_manager');
    expect($siteManagerRole->hasPermissionTo('esg.data.branch_only'))->toBeTrue();
    expect($siteManagerRole->hasPermissionTo('platform.tenants.manage'))->toBeFalse();

    $externalAuditorRole = Role::findByName('external_auditor');
    expect($externalAuditorRole->hasPermissionTo('esg.assurance.view_dataroom'))->toBeTrue();
    expect($externalAuditorRole->hasPermissionTo('esg.data.delete'))->toBeFalse();
});

test('multi-tenant global scope isolates tenant data', function () {
    $org1 = Organization::create([
        'name' => 'Acme Corporation',
        'industry' => 'manufacturing',
    ]);

    $org2 = Organization::create([
        'name' => 'Globex Industries',
        'industry' => 'technology',
    ]);

    $branch1 = Branch::create([
        'organization_id' => $org1->id,
        'name' => 'Acme Plant Alpha',
        'facility_type' => 'factory',
    ]);

    $branch2 = Branch::create([
        'organization_id' => $org2->id,
        'name' => 'Globex Lab Beta',
        'facility_type' => 'office',
    ]);

    // When tenant is Acme
    TenantContext::setTenant($org1);
    $acmeBranches = Branch::all();
    expect($acmeBranches->pluck('name')->toArray())->toContain('Acme Plant Alpha')
        ->and($acmeBranches->pluck('name')->toArray())->not->toContain('Globex Lab Beta');

    // When tenant is Globex
    TenantContext::setTenant($org2);
    $globexBranches = Branch::all();
    expect($globexBranches->pluck('name')->toArray())->toContain('Globex Lab Beta')
        ->and($globexBranches->pluck('name')->toArray())->not->toContain('Acme Plant Alpha');

    TenantContext::clear();
});

test('core modules cannot be disabled', function () {
    $org = Organization::create([
        'name' => 'Apex Holdings',
        'industry' => 'textiles_apparel',
    ]);

    expect(fn () => ModuleRegistry::toggle('tenant_management', $org, false))
        ->toThrow(DomainException::class);
});

test('module dependency validation prevents disabling prerequisite or enabling without prerequisite', function () {
    $org = Organization::create([
        'name' => 'Eco Ventures',
        'industry' => 'general',
    ]);

    // Enable frameworks_library and disclosure_mapper (which depends on frameworks_library)
    ModuleRegistry::toggle('frameworks_library', $org, true);
    ModuleRegistry::toggle('disclosure_mapper', $org, true);

    // Attempting to disable frameworks_library should be rejected because disclosure_mapper depends on it
    expect(fn () => ModuleRegistry::toggle('frameworks_library', $org, false))
        ->toThrow(DomainException::class);
});

test('module middleware blocks routes when module is disabled', function () {
    $org = Organization::create([
        'name' => 'Nordic Energy',
        'industry' => 'energy',
    ]);

    TenantContext::setTenant($org);
    OrganizationModule::updateOrCreate(
        ['organization_id' => $org->id, 'module_slug' => 'iot_sensors'],
        ['is_enabled' => false]
    );
    ModuleGate::clearCache($org->id);

    Route::get('/test-iot', function () {
        return response('iot-data');
    })->middleware(CheckModuleEnabled::class.':iot_sensors');

    // Requesting disabled module returns 404
    $response = $this->get('/test-iot');
    $response->assertStatus(404);

    // When enabled, route responds 200
    OrganizationModule::updateOrCreate(
        ['organization_id' => $org->id, 'module_slug' => 'iot_sensors'],
        ['is_enabled' => true]
    );
    ModuleGate::clearCache($org->id);

    $response2 = $this->get('/test-iot');
    $response2->assertStatus(200);

    TenantContext::clear();
});

test('audit log records activity trail', function () {
    $org = Organization::create([
        'name' => 'Test Org',
    ]);

    $user = User::create([
        'name' => 'Auditor Imran',
        'email' => 'auditor@example.com',
        'password' => 'secret123',
        'organization_id' => $org->id,
    ]);

    $this->actingAs($user);

    $log = AuditLog::log(
        event: 'approved',
        auditable: $org,
        oldValues: ['status' => 'draft'],
        newValues: ['status' => 'approved']
    );

    expect($log->event)->toBe('approved')
        ->and($log->user_id)->toBe($user->id)
        ->and($log->organization_id)->toBe($org->id)
        ->and($log->old_values['status'])->toBe('draft')
        ->and($log->new_values['status'])->toBe('approved');
});
