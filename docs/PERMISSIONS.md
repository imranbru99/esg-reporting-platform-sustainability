# Roles & Permissions Matrix (22 Stakeholder Personas)

The platform enforces strict role-based access control (RBAC) via `spatie/laravel-permission` integrated directly into Filament navigation, resource policies, API token scopes, and tenant global scopes.

---

## 1. 22 Pre-Configured Enterprise Roles

| # | Role Key | Role Title | Primary Scope | Typical User Persona |
| :- | :--- | :--- | :--- | :--- |
| 1 | `super_admin` | Super Administrator | Global Platform | Imran Ahmed (Platform Architect / SaaS Owner) |
| 2 | `platform_staff` | Platform Support Specialist | Global Operations | Customer Success & Assisted Onboarding Lead |
| 3 | `org_admin` | Organization Administrator / CEO | Enterprise Tenant | CEO / Managing Director / Operating Partner |
| 4 | `esg_manager` | ESG Manager / Sustainability Director| Enterprise Tenant | Head of Sustainability & Corporate Responsibility |
| 5 | `site_manager` | Facility / Site Manager | Branch / Facility | Factory General Manager / Plant Engineer |
| 6 | `dept_head` | Department Head / Reviewer | Department | Production Head / ETP In-Charge / HR Director |
| 7 | `finance_officer` | Finance & Green Capex Officer | Enterprise Tenant | CFO / Financial Controller / Green Finance Desk |
| 8 | `hr_officer` | HR & Workforce Compliance Lead | Enterprise Tenant | Head of Human Resources / Compliance Manager |
| 9 | `procurement_manager`| Procurement & Supply Chain Lead | Enterprise Tenant | VP of Sourcing / Supply Chain Due Diligence Head |
| 10| `compliance_officer` | Compliance & Risk Officer | Enterprise Tenant | Chief Risk Officer / General Legal Counsel |
| 11| `internal_auditor` | Internal Auditor | Enterprise Tenant | Head of Internal Audit & Risk Assurance |
| 12| `external_auditor` | Independent Assurance Provider | Limited Data Room | KPMG / PwC / EY / SGS Lead Audit Partner |
| 13| `executive` | Board Member / Non-Executive Director | Read-Only Drilldown| Board Chairman / Audit Committee Member |
| 14| `investor_portal` | Institutional Investor / Rating Analyst| External Portal | BlackRock / MSCI / Sustainalytics ESG Analyst |
| 15| `supplier_portal` | Tier-1 / Tier-2 Supplier Partner | Supplier Portal | Vendor Compliance Officer / Factory Owner |
| 16| `customer_portal` | Client / Buyer Sustainability Partner | Customer Portal | H&M / Inditex / Marks & Spencer Sourcing Team |
| 17| `consultant` | Sustainability Advisory Agency Lead | Multi-Tenant Agency | Boutique ESG Consultancy Engagement Partner |
| 18| `reseller` | Commercial Reseller & Partner | Reseller Console | Enterprise Software Channel Partner |
| 19| `employee` | Employee Self-Service | Employee Console | Company Worker / Associate / Team Member |
| 20| `whistleblower` | Confidential Whistleblower | Anonymous Token | Anonymous Worker / Subcontractor / Public Citizen |
| 21| `public_visitor` | Public Portal Guest | Public Marketing | Prospective Client / Community Member |
| 22| `api_user` | Machine-to-Machine Integration System | API Token Scope | Smart Metering Gateway / ERP Synchronization Bot |

---

## 2. Granular Permissions Domain Matrix

```
Domain                 Permissions Included
------------------------------------------------------------------------------------------------------
Platform Management    platform.tenants.manage, platform.tenants.view, platform.modules.manage,
                       platform.plans.manage, platform.system.health, platform.impersonate

Tenant Administration  org.settings.manage, org.modules.toggle, org.users.manage, org.users.view,
                       org.structure.manage

ESG Data Collection    esg.data.view, esg.data.create, esg.data.edit, esg.data.delete,
                       esg.data.approve, esg.data.lock, esg.data.restate, esg.data.branch_only

Environmental Pillars  esg.carbon.manage, esg.carbon.calculate, esg.energy.manage, esg.water.manage,
                       esg.waste.manage, esg.air.manage, esg.product_footprint.manage,
                       esg.carbon_credits.manage

Social Pillars         esg.workforce.manage, esg.attendance.manage, esg.ohs.manage,
                       esg.ohs.report_incident, esg.training.manage, esg.human_rights.manage,
                       esg.csr.manage, esg.whistleblower.manage, esg.whistleblower.submit,
                       esg.surveys.participate

Governance Pillars     esg.board.manage, esg.policies.manage, esg.policies.acknowledge,
                       esg.compliance.manage, esg.risks.manage, esg.targets.manage,
                       esg.materiality.manage

Supply Chain Due Diligence esg.suppliers.manage, esg.suppliers.assess, esg.suppliers.portal_access,
                       esg.scope3.request, esg.traceability.manage

Assurance Workspace    esg.audit.view_dataroom, esg.audit.workpapers, esg.audit.signoff

Reporting & AI Hub     esg.reports.create, esg.reports.edit, esg.reports.generate,
                       esg.reports.view_draft, esg.reports.approve, esg.reports.publish,
                       esg.reports.gap_analysis, esg.reports.export_pdf, esg.ai.chat

Commercial Operations  crm.manage, project.manage, project.timesheet, billing.manage,
                       billing.view_invoices, helpdesk.manage, helpdesk.create_ticket,
                       academy.manage, academy.access, reseller.manage, api.access
```

---

## 3. Security & Multi-Tenancy Boundary Enforcement

1. **Global Scope Isolation**:
   Every database query automatically applies `App\Scopes\OrganizationScope` through the `BelongsToOrganization` trait. Cross-tenant data leakage is cryptographically and logically impossible.
2. **Branch Sandboxing**:
   Users with the `esg.data.branch_only` permission (e.g., Facility Managers) have their queries automatically filtered to their assigned `branch_id`.
3. **Audit Trail Immutability**:
   Approved and locked reporting periods cannot be modified or restated without explicit authorization and an immutable entry logged to `restatement_logs` with before-and-after variance calculations.

---

## Author & Developer Information

- **Developer**: Imran Ahmed
- **Email**: me@imrandev.bd
- **Official Website**: [https://imrandev.bd](https://imrandev.bd)

