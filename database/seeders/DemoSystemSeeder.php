<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\EvidenceDocument;
use App\Models\Invoice;
use App\Models\LegalEntity;
use App\Models\MaterialityAssessment;
use App\Models\MaterialityTopic;
use App\Models\Metric;
use App\Models\MetricEntry;
use App\Models\Organization;
use App\Models\ReportProject;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use App\Models\User;
use App\Services\Reporting\ReportGeneratorService;
use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Demo Organization A: Apex Textile & Apparel Park Ltd
        $apex = Organization::updateOrCreate(
            ['slug' => 'apex-textile-apparel'],
            [
                'name' => 'Apex Textile & Apparel Park Ltd',
                'industry' => 'textiles_apparel',
                'country_code' => 'BGD',
                'currency_code' => 'USD',
            ]
        );

        $apexEntity = LegalEntity::updateOrCreate(
            ['organization_id' => $apex->id, 'registration_number' => 'C-88912-BGD'],
            ['name' => 'Apex Textile Holdings PLC', 'country_code' => 'BGD']
        );

        $apexBranchGazipur = Branch::updateOrCreate(
            ['organization_id' => $apex->id, 'code' => 'APEX-GAZ'],
            [
                'legal_entity_id' => $apexEntity->id,
                'name' => 'Gazipur Integrated Garments Complex',
                'facility_type' => 'factory',
                'city' => 'Gazipur',
                'country_code' => 'BGD',
            ]
        );

        $apexDeptSustainability = Department::updateOrCreate(
            ['organization_id' => $apex->id, 'code' => 'SUST-01'],
            ['branch_id' => $apexBranchGazipur->id, 'name' => 'Sustainability & Environmental Compliance']
        );

        // 2. Create Demo Organization B: Bengal Commercial Bank PLC
        $bank = Organization::updateOrCreate(
            ['slug' => 'bengal-commercial-bank'],
            [
                'name' => 'Bengal Commercial Bank PLC',
                'industry' => 'banking_finance',
                'country_code' => 'BGD',
                'currency_code' => 'BDT',
            ]
        );

        // 3. Create Demo Organization C: CloudScale Software Technologies Ltd
        $cloud = Organization::updateOrCreate(
            ['slug' => 'cloudscale-software'],
            [
                'name' => 'CloudScale Software Technologies Ltd',
                'industry' => 'technology',
                'country_code' => 'USA',
                'currency_code' => 'USD',
            ]
        );

        // 4. Seed Monthly ESG Metrics for Apex (2023 & 2024)
        TenantContext::setTenant($apex);

        $scope1Metric = Metric::where('code', 'GHG-S1')->first();
        $scope2Metric = Metric::where('code', 'GHG-S2-LOC')->first();
        $waterMetric = Metric::where('code', 'WTR-WTH')->first();
        $femaleMetric = Metric::where('code', 'SOC-FEM')->first();

        $evidence = EvidenceDocument::firstOrCreate(
            ['title' => 'Gazipur Natural Gas Invoices FY2024', 'organization_id' => $apex->id],
            [
                'file_name' => 'gas-meter-2024.pdf',
                'file_path' => 'evidence/gas-meter-2024.pdf',
                'file_hash_sha256' => hash('sha256', 'apex-gas-evidence'),
                'document_type' => 'utility_bill',
            ]
        );

        if ($scope1Metric) {
            $s1Entry = MetricEntry::updateOrCreate(
                ['organization_id' => $apex->id, 'metric_id' => $scope1Metric->id, 'period_start' => '2024-01-01'],
                [
                    'branch_id' => $apexBranchGazipur->id,
                    'department_id' => $apexDeptSustainability->id,
                    'period_end' => '2024-12-31',
                    'activity_value' => 2450.80,
                    'activity_unit' => 'tCO2e',
                    'normalized_value' => 2450.80,
                    'status' => 'verified',
                ]
            );
            $evidence->update(['metric_entry_id' => $s1Entry->id]);
        }

        if ($scope2Metric) {
            MetricEntry::updateOrCreate(
                ['organization_id' => $apex->id, 'metric_id' => $scope2Metric->id, 'period_start' => '2024-01-01'],
                [
                    'branch_id' => $apexBranchGazipur->id,
                    'period_end' => '2024-12-31',
                    'activity_value' => 1840.25,
                    'activity_unit' => 'tCO2e',
                    'normalized_value' => 1840.25,
                    'status' => 'verified',
                ]
            );
        }

        if ($waterMetric) {
            MetricEntry::updateOrCreate(
                ['organization_id' => $apex->id, 'metric_id' => $waterMetric->id, 'period_start' => '2024-01-01'],
                [
                    'branch_id' => $apexBranchGazipur->id,
                    'period_end' => '2024-12-31',
                    'activity_value' => 84200.0,
                    'activity_unit' => 'm3',
                    'normalized_value' => 84200.0,
                    'status' => 'verified',
                ]
            );
        }

        if ($femaleMetric) {
            MetricEntry::updateOrCreate(
                ['organization_id' => $apex->id, 'metric_id' => $femaleMetric->id, 'period_start' => '2024-01-01'],
                [
                    'period_end' => '2024-12-31',
                    'activity_value' => 54.5,
                    'activity_unit' => '%',
                    'normalized_value' => 54.5,
                    'status' => 'verified',
                ]
            );
        }

        // Seed Materiality for Apex
        $topicEffluent = MaterialityTopic::firstOrCreate(
            ['organization_id' => $apex->id, 'name' => 'Wastewater Treatment & Chemical Zero-Discharge'],
            ['category' => 'environmental', 'description' => 'ZDHC zero discharge of hazardous chemicals and closed-loop water treatment.']
        );

        MaterialityAssessment::updateOrCreate(
            ['organization_id' => $apex->id, 'materiality_topic_id' => $topicEffluent->id, 'reporting_year' => '2024'],
            [
                'impact_materiality_score' => 4.8,
                'financial_materiality_score' => 4.5,
                'is_double_material' => true,
            ]
        );

        // Seed Suppliers & Higg Assessments
        $supplier = Supplier::updateOrCreate(
            ['organization_id' => $apex->id, 'code' => 'SUP-981'],
            [
                'name' => 'Cotton Mills Delta Ltd',
                'tier' => 'tier_1',
                'country_code' => 'BGD',
                'contact_email' => 'compliance@cottondelta.test',
                'esg_risk_rating' => 'low',
                'portal_access_enabled' => true,
            ]
        );

        SupplierAssessment::updateOrCreate(
            ['organization_id' => $apex->id, 'supplier_id' => $supplier->id, 'questionnaire_name' => 'Higg Index FEM 4.0'],
            [
                'score_percentage' => 91.5,
                'status' => 'approved',
            ]
        );

        // Seed Report Project
        $report = ReportProject::updateOrCreate(
            ['organization_id' => $apex->id, 'reporting_period' => '2024'],
            [
                'title' => 'Apex 2024 Sustainability & Climate Disclosure Report',
                'primary_framework' => 'gri',
                'language' => 'en',
                'status' => 'locked',
                'sha256_hash' => hash('sha256', 'apex-report-2024-verified'),
                'published_at' => now(),
            ]
        );

        $reportService = new ReportGeneratorService;
        $reportService->initializeOutline($report);

        // Seed Invoicing
        Invoice::updateOrCreate(
            ['organization_id' => $apex->id, 'invoice_number' => 'INV-APEX-2024-001'],
            [
                'client_name' => 'Inditex Global Supply',
                'billing_email' => 'sustainability.invoices@inditex.test',
                'subtotal_cents' => 1500000,
                'tax_amount_cents' => 150000,
                'total_amount_cents' => 1650000,
                'currency' => 'USD',
                'status' => 'paid',
                'due_date' => now()->addDays(30),
                'paid_at' => now(),
            ]
        );

        TenantContext::clear();

        // 5. Seed All 22 Demo Users
        $demoUsersList = [
            ['name' => 'Super Admin Imran', 'email' => 'superadmin@platform.test', 'role' => 'super_admin', 'org_id' => $apex->id],
            ['name' => 'Support Agent Maya', 'email' => 'staff@platform.test', 'role' => 'platform_staff', 'org_id' => $apex->id],
            ['name' => 'Apex CEO Kabir', 'email' => 'owner@apex.test', 'role' => 'org_admin', 'org_id' => $apex->id],
            ['name' => 'ESG Director Samantha', 'email' => 'esg-manager@apex.test', 'role' => 'esg_manager', 'org_id' => $apex->id],
            ['name' => 'Gazipur Facility Lead Rafiq', 'email' => 'facility@apex.test', 'role' => 'site_manager', 'org_id' => $apex->id],
            ['name' => 'Dept Approver Dr. Alam', 'email' => 'approver@apex.test', 'role' => 'dept_head', 'org_id' => $apex->id],
            ['name' => 'Bank CFO Tanveer', 'email' => 'cfo@bengalbank.test', 'role' => 'finance_officer', 'org_id' => $bank->id],
            ['name' => 'HR Compliance Farzana', 'email' => 'hr@apex.test', 'role' => 'hr_officer', 'org_id' => $apex->id],
            ['name' => 'Procurement Lead Tariq', 'email' => 'procurement@apex.test', 'role' => 'procurement_manager', 'org_id' => $apex->id],
            ['name' => 'Risk Officer Shireen', 'email' => 'compliance@bengalbank.test', 'role' => 'compliance_officer', 'org_id' => $bank->id],
            ['name' => 'Internal Auditor Hasan', 'email' => 'internal-auditor@bengalbank.test', 'role' => 'internal_auditor', 'org_id' => $bank->id],
            ['name' => 'KPMG Lead Auditor David', 'email' => 'auditor@kpmg.test', 'role' => 'external_auditor', 'org_id' => $apex->id],
            ['name' => 'Board Director Amina', 'email' => 'board@bengalbank.test', 'role' => 'executive', 'org_id' => $bank->id],
            ['name' => 'BlackRock ESG Analyst Lucas', 'email' => 'investor@blackrock.test', 'role' => 'investor_portal', 'org_id' => $bank->id],
            ['name' => 'Cotton Supplier Rahim', 'email' => 'supplier@cottondelta.test', 'role' => 'supplier_portal', 'org_id' => $apex->id],
            ['name' => 'H&M Buyer Partner Lisa', 'email' => 'client@hmgroup.test', 'role' => 'customer_portal', 'org_id' => $apex->id],
            ['name' => 'Veritas Consultant Marcus', 'email' => 'consultant@veritas.test', 'role' => 'consultant', 'org_id' => $apex->id],
            ['name' => 'EcoPartner Reseller Neil', 'email' => 'reseller@ecopartner.test', 'role' => 'reseller', 'org_id' => $cloud->id],
            ['name' => 'Software Engineer Fahim', 'email' => 'employee@cloudscale.test', 'role' => 'employee', 'org_id' => $cloud->id],
            ['name' => 'Confidential Whistleblower', 'email' => 'whistleblower@anonymous.test', 'role' => 'whistleblower', 'org_id' => $apex->id],
            ['name' => 'Public Visitor Guest', 'email' => 'visitor@public.test', 'role' => 'public_visitor', 'org_id' => $apex->id],
            ['name' => 'IoT Meter System Bot', 'email' => 'api@integration.test', 'role' => 'api_user', 'org_id' => $apex->id],
        ];

        foreach ($demoUsersList as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'organization_id' => $userData['org_id'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Assign role
            if (Role::where('name', $userData['role'])->exists()) {
                $user->syncRoles([$userData['role']]);
            }
        }
    }
}
