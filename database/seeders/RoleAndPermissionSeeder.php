<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Granular Permissions across functional domains
        $permissions = [
            // Platform & Tenant
            'platform.tenants.manage',
            'platform.tenants.view',
            'platform.modules.manage',
            'platform.plans.manage',
            'platform.system.health',
            'platform.impersonate',

            // Org & User Management
            'org.settings.manage',
            'org.modules.toggle',
            'org.users.manage',
            'org.users.view',
            'org.structure.manage', // entities, branches, departments

            // ESG Data Collection & Quality
            'esg.data.view',
            'esg.data.create',
            'esg.data.edit',
            'esg.data.delete',
            'esg.data.approve',
            'esg.data.lock',
            'esg.data.restate',
            'esg.data.branch_only',

            // Environmental & Carbon
            'esg.carbon.manage',
            'esg.carbon.calculate',
            'esg.energy.manage',
            'esg.water.manage',
            'esg.waste.manage',
            'esg.air.manage',
            'esg.product_footprint.manage',
            'esg.carbon_credits.manage',

            // Social & Workforce
            'esg.workforce.manage',
            'esg.attendance.manage',
            'esg.ohs.manage',
            'esg.ohs.report_incident',
            'esg.training.manage',
            'esg.human_rights.manage',
            'esg.csr.manage',
            'esg.whistleblower.submit',
            'esg.whistleblower.investigate',
            'esg.surveys.participate',
            'esg.surveys.manage',

            // Governance & Compliance
            'esg.governance.manage',
            'esg.policies.manage',
            'esg.policies.acknowledge',
            'esg.compliance.manage',
            'esg.risks.manage',
            'esg.strategy.manage',
            'esg.materiality.manage',

            // Supply Chain & Scope 3
            'esg.suppliers.manage',
            'esg.suppliers.assess',
            'esg.suppliers.portal_access',
            'esg.scope3.collect',

            // Reporting & AI
            'esg.reports.generate',
            'esg.reports.view_draft',
            'esg.reports.approve',
            'esg.reports.publish',
            'esg.reports.export_pdf',
            'esg.reports.gap_analysis',
            'esg.ai.chat',

            // Assurance & Audit
            'esg.assurance.manage',
            'esg.assurance.view_dataroom',
            'esg.assurance.request_evidence',
            'esg.assurance.sign_off',

            // Finance & Commercial
            'billing.manage',
            'billing.view_invoices',
            'green_finance.manage',
            'crm.manage',
            'helpdesk.manage',
            'helpdesk.submit_ticket',
            'academy.access',
            'reseller.manage',
            'api.access',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // 2. Define the 22 User Roles with their assigned permissions
        $roleDefinitions = [
            'super_admin' => $permissions, // Super admin receives every permission

            'platform_staff' => [
                'platform.tenants.view',
                'platform.system.health',
                'helpdesk.manage',
                'org.users.view',
            ],

            'org_admin' => [
                'org.settings.manage',
                'org.modules.toggle',
                'org.users.manage',
                'org.users.view',
                'org.structure.manage',
                'billing.manage',
                'billing.view_invoices',
                'esg.data.view',
                'esg.data.create',
                'esg.data.edit',
                'esg.data.delete',
                'esg.data.approve',
                'esg.data.lock',
                'esg.reports.generate',
                'esg.reports.view_draft',
                'esg.reports.approve',
                'esg.reports.publish',
                'esg.reports.export_pdf',
                'esg.reports.gap_analysis',
                'esg.ai.chat',
                'esg.carbon.manage',
                'esg.workforce.manage',
                'esg.suppliers.manage',
                'helpdesk.submit_ticket',
                'academy.access',
                'api.access',
            ],

            'esg_manager' => [
                'esg.data.view',
                'esg.data.create',
                'esg.data.edit',
                'esg.data.approve',
                'esg.data.lock',
                'esg.data.restate',
                'esg.carbon.manage',
                'esg.carbon.calculate',
                'esg.energy.manage',
                'esg.water.manage',
                'esg.waste.manage',
                'esg.air.manage',
                'esg.product_footprint.manage',
                'esg.carbon_credits.manage',
                'esg.workforce.manage',
                'esg.attendance.manage',
                'esg.ohs.manage',
                'esg.training.manage',
                'esg.human_rights.manage',
                'esg.csr.manage',
                'esg.governance.manage',
                'esg.compliance.manage',
                'esg.risks.manage',
                'esg.strategy.manage',
                'esg.materiality.manage',
                'esg.suppliers.manage',
                'esg.suppliers.assess',
                'esg.scope3.collect',
                'esg.reports.generate',
                'esg.reports.view_draft',
                'esg.reports.approve',
                'esg.reports.publish',
                'esg.reports.export_pdf',
                'esg.reports.gap_analysis',
                'esg.ai.chat',
                'esg.assurance.manage',
                'academy.access',
                'helpdesk.submit_ticket',
            ],

            'site_manager' => [
                'esg.data.view',
                'esg.data.create',
                'esg.data.edit',
                'esg.data.branch_only',
                'esg.energy.manage',
                'esg.water.manage',
                'esg.waste.manage',
                'esg.ohs.report_incident',
                'esg.attendance.manage',
                'helpdesk.submit_ticket',
            ],

            'dept_head' => [
                'esg.data.view',
                'esg.data.approve',
                'esg.reports.view_draft',
                'esg.ai.chat',
                'helpdesk.submit_ticket',
            ],

            'finance_officer' => [
                'esg.data.view',
                'billing.view_invoices',
                'green_finance.manage',
                'esg.carbon_credits.manage',
                'esg.reports.view_draft',
                'esg.reports.export_pdf',
                'esg.ai.chat',
            ],

            'hr_officer' => [
                'esg.workforce.manage',
                'esg.attendance.manage',
                'esg.training.manage',
                'esg.human_rights.manage',
                'esg.ohs.manage',
                'esg.surveys.manage',
                'esg.data.view',
                'esg.data.create',
                'esg.data.edit',
            ],

            'procurement_manager' => [
                'esg.suppliers.manage',
                'esg.suppliers.assess',
                'esg.scope3.collect',
                'esg.data.view',
                'esg.data.create',
                'esg.ai.chat',
            ],

            'compliance_officer' => [
                'esg.compliance.manage',
                'esg.policies.manage',
                'esg.risks.manage',
                'esg.whistleblower.investigate',
                'esg.data.view',
                'esg.reports.view_draft',
                'esg.reports.gap_analysis',
            ],

            'internal_auditor' => [
                'esg.data.view',
                'esg.reports.view_draft',
                'esg.assurance.view_dataroom',
                'esg.assurance.request_evidence',
                'esg.assurance.sign_off',
                'esg.reports.gap_analysis',
            ],

            'external_auditor' => [
                'esg.assurance.view_dataroom',
                'esg.assurance.request_evidence',
                'esg.assurance.sign_off',
                'esg.data.view',
                'esg.reports.view_draft',
                'esg.reports.export_pdf',
            ],

            'executive' => [
                'esg.data.view',
                'esg.reports.view_draft',
                'esg.reports.approve',
                'esg.reports.export_pdf',
                'green_finance.manage',
                'esg.ai.chat',
            ],

            'investor_portal' => [
                'esg.reports.export_pdf',
                'green_finance.manage',
            ],

            'supplier_portal' => [
                'esg.suppliers.portal_access',
            ],

            'customer_portal' => [
                'esg.reports.export_pdf',
            ],

            'consultant' => [
                'esg.data.view',
                'esg.data.create',
                'esg.data.edit',
                'esg.reports.generate',
                'esg.reports.view_draft',
                'esg.reports.gap_analysis',
                'esg.reports.export_pdf',
                'esg.ai.chat',
                'crm.manage',
            ],

            'reseller' => [
                'reseller.manage',
                'billing.view_invoices',
            ],

            'employee' => [
                'esg.policies.acknowledge',
                'esg.surveys.participate',
                'esg.ohs.report_incident',
                'esg.whistleblower.submit',
                'academy.access',
            ],

            'whistleblower' => [
                'esg.whistleblower.submit',
            ],

            'public_visitor' => [
                // Public pages only, no protected system permissions
            ],

            'api_user' => [
                'api.access',
                'esg.data.view',
                'esg.data.create',
            ],
        ];

        foreach ($roleDefinitions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }
}
