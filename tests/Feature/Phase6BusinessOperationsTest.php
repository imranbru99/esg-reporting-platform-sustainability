<?php

use App\Models\AcademyCourse;
use App\Models\CrmLead;
use App\Models\GeneralLedgerEntry;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\ProjectEngagement;
use App\Models\ProjectTimesheet;
use App\Models\SupportTicket;
use App\Models\SustainabilityCertificate;
use App\Models\User;
use App\Services\Tenancy\TenantContext;
use Database\Seeders\ModuleRegistrySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

beforeEach(function () {
    $this->seed(ModuleRegistrySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
});

test('crm pipeline tracks leads through stages with financial estimates', function () {
    $org = Organization::create(['name' => 'Veritas Sustainability Consulting', 'industry' => 'consultancy_audit']);
    TenantContext::setTenant($org);

    $lead = CrmLead::create([
        'organization_id' => $org->id,
        'contact_name' => 'Tariq Rahman',
        'company_name' => 'Dhaka Textile Group',
        'email' => 'tariq@dhakatextile.test',
        'stage' => 'proposal',
        'estimated_value_cents' => 2500000, // $25,000 USD
        'currency' => 'USD',
    ]);

    expect($lead->stage)->toBe('proposal')
        ->and($lead->estimated_value_cents)->toBe(2500000);

    $lead->update(['stage' => 'won']);
    expect($lead->fresh()->stage)->toBe('won');

    TenantContext::clear();
});

test('project engagements manage client milestones and billable timesheets', function () {
    $org = Organization::create(['name' => 'Veritas Sustainability Consulting', 'industry' => 'consultancy_audit']);
    TenantContext::setTenant($org);

    $user = User::factory()->create(['name' => 'ESG Consultant Sarah']);

    $engagement = ProjectEngagement::create([
        'organization_id' => $org->id,
        'title' => 'CSRD Double Materiality & Carbon Accounting FY24',
        'client_name' => 'Apex Footwear',
        'engagement_type' => 'consultancy',
        'budget_amount_cents' => 1500000,
        'start_date' => now()->toDateString(),
    ]);

    $timesheet = ProjectTimesheet::create([
        'project_engagement_id' => $engagement->id,
        'user_id' => $user->id,
        'work_date' => now()->toDateString(),
        'hours_spent' => 4.5,
        'hourly_rate_cents' => 15000, // $150/hr
        'task_description' => 'Conducted stakeholder materiality interviews for Scope 3 emissions.',
        'is_billable' => true,
    ]);

    expect($engagement->timesheets()->count())->toBe(1)
        ->and((float) $timesheet->hours_spent)->toEqual(4.5);

    TenantContext::clear();
});

test('invoicing generates multilingual unicode pdf with itemized ledger', function () {
    UnicodePdf::fake();

    $org = Organization::create(['name' => 'Veritas Sustainability Consulting', 'industry' => 'consultancy_audit']);
    TenantContext::setTenant($org);

    $invoice = Invoice::create([
        'organization_id' => $org->id,
        'invoice_number' => 'INV-2024-001',
        'client_name' => 'Shanta Holdings Ltd',
        'billing_email' => 'finance@shanta.test',
        'subtotal_cents' => 1000000,
        'tax_amount_cents' => 150000,
        'total_amount_cents' => 1150000,
        'currency' => 'BDT',
        'status' => 'sent',
        'due_date' => now()->addDays(30)->toDateString(),
    ]);

    $invoice->items()->create([
        'description' => 'GHG Scope 1 & 2 Verification and Report Generation',
        'quantity' => 1,
        'unit_price_cents' => 1000000,
        'line_total_cents' => 1000000,
    ]);

    $path = $invoice->generatePdf();

    UnicodePdf::assertGenerated(1);
    expect($path)->toContain('inv-INV-2024-001.pdf')
        ->and($invoice->items()->count())->toBe(1);

    TenantContext::clear();
});

test('accounting lite records double-entry transactions and green capex tags', function () {
    $org = Organization::create(['name' => 'Standard Garments', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);

    $entry = GeneralLedgerEntry::create([
        'organization_id' => $org->id,
        'account_code' => '1500-SOLAR',
        'account_name' => 'Rooftop Solar PV Installation Capex',
        'entry_date' => now()->toDateString(),
        'debit_cents' => 45000000, // $450,000 USD
        'credit_cents' => 0,
        'is_green_capex' => true,
        'is_green_opex' => false,
        'reference' => 'PO-SOLAR-092',
    ]);

    expect($entry->is_green_capex)->toBeTrue()
        ->and($entry->debit_cents)->toBe(45000000);

    TenantContext::clear();
});

test('help desk manages support tickets and customer inquiry messages', function () {
    $org = Organization::create(['name' => 'Standard Garments', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);

    $ticket = SupportTicket::create([
        'organization_id' => $org->id,
        'ticket_number' => 'TCK-8821',
        'requester_name' => 'Rahim Ullah',
        'requester_email' => 'rahim@standardgarments.test',
        'subject' => 'Clarification on Scope 2 Location vs Market grid emission factor',
        'priority' => 'high',
        'status' => 'open',
        'category' => 'esg_data',
    ]);

    $message = $ticket->messages()->create([
        'is_staff' => false,
        'message' => 'Please explain why the Dhaka electric grid emission factor uses 0.589 kgCO2e/kWh instead of last year figure.',
    ]);

    expect($ticket->messages()->count())->toBe(1)
        ->and($message->is_staff)->toBeFalse();

    TenantContext::clear();
});

test('training academy publishes courses with universal slug and issues certificates', function () {
    UnicodePdf::fake();

    $org = Organization::create(['name' => 'Standard Garments', 'industry' => 'manufacturing']);
    TenantContext::setTenant($org);

    $user = User::factory()->create(['name' => 'Mohammad Imran Hossain']);

    $course = AcademyCourse::create([
        'organization_id' => $org->id,
        'title' => 'GHG Protocol Corporate Standard & Scope 3 Decarbonization',
        'description' => 'Comprehensive masterclass on carbon accounting.',
        'level' => 'advanced',
        'duration_hours' => 8,
    ]);

    expect($course->slug)->not->toBeEmpty();

    $cert = SustainabilityCertificate::create([
        'academy_course_id' => $course->id,
        'organization_id' => $org->id,
        'user_id' => $user->id,
        'certificate_number' => 'CERT-2024-GHG-091',
        'recipient_name' => $user->name,
        'completion_date' => now()->toDateString(),
    ]);

    $certPath = $cert->generatePdf();

    UnicodePdf::assertGenerated(1);
    expect($certPath)->toContain('cert-CERT-2024-GHG-091.pdf')
        ->and($cert->course->id)->toBe($course->id);

    TenantContext::clear();
});
