<p align="center">
  <img src="docs/screenshots/dashboard.svg" alt="ESG Reporting Platform dashboard preview" width="100%"/>
</p>

<h1 align="center">ESG Reporting Platform</h1>

<p align="center"><strong>Auto-generated sustainability reports, GHG accounting & compliance dashboards</strong></p>

<p align="center">
  <a href="https://github.com/imranbru99/esg-reporting-platform-sustainability"><img alt="GitHub" src="https://img.shields.io/badge/GitHub-imranbru99%2Fesg-reporting-platform-sustainability-0F172A?style=for-the-badge&logo=github"/></a>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-PHP-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="Author" src="https://img.shields.io/badge/Author-Imran%20Ahmed-14B8A6?style=for-the-badge"/>
</p>

---

## Overview

**ESG Reporting Platform** is a complete business application focused on real operational workflows. This README lists the full feature surface so GitHub visitors and search engines can understand every capability of the product.

## Product preview

| Dashboard | Operations |
| :---: | :---: |
| ![Dashboard](docs/screenshots/dashboard.svg) | ![Operations](docs/screenshots/operations.svg) |

## Complete feature list

### Product modules & capabilities

- Everything runs on a normal VPS
- Attendance machines
- Slugs for all public content
- Activity log and full audit trail
- Data versioning and locking per reporting period
- Approval workflows engine
- Notification center
- Import/Export everywhere
- Implement with roles + granular permissions
- External Auditor / Assurance Provider
- Investor / Lender / Rating Analyst
- Supplier / Vendor
- Consultant / Agency
- Reseller / Partner
- Employee
- Notification Center & Templates
- Translation Manager
- Impact Valuation
- Health and Safety
- Community and Social Impact
- Product Traceability Link
- Auto Report Generator
- HR & Payroll Lite
- Marketplace / Service Directory
- Carbon Marketplace Link / Offset Purchasing
- Frameworks and Standards page and per-framework pages
- Solutions by Role
- How it Works
- Pricing page
- Academy / Help Center / Knowledge Base
- Free ESG Tools
- Queue everything heavy
- Business operations modules

### Admin / Filament resources

- Academy Course
- Assurance Engagement
- Attendance Summary Log
- Audit Log
- Base Module
- Board Member
- Branch
- Carbon Credit
- Climate Risk
- Compliance Obligation
- Corporate Policy
- Crm Lead
- Csr Project
- Data Collection Request
- Emission Factor
- Esg Scorecard
- Esg Target
- Framework
- Gap Analysis
- Internal Control
- Invoice
- Materiality Assessment
- Metric
- Ohs Incident
- Organization
- Product Footprint
- Project Engagement
- Regulation Watch
- Report Project
- Supplier Assessment
- Supplier
- Support Ticket
- User
- Utility Bill
- Waste Manifest
- Water Record
- Whistleblower Case
- Workforce Snapshot

### Domain models

- Academy Course
- Ai Chat Conversation
- Ai Chat Message
- Air Emission
- Anti Corruption Training
- Assurance Engagement
- Attendance Summary Log
- Audit Log
- Audit Workpaper
- Biodiversity Site
- Board Member
- Branch
- Branch Module
- Business Unit
- Carbon Credit
- Climate Risk
- Compliance Obligation
- Corporate Policy
- Crm Lead
- Csr Project
- Customer Complaint
- Cybersecurity Audit
- Data Collection Request
- Department
- Disclosure Metric Map
- Emission Factor
- Emission Factor Library
- Employee Survey
- Esg Scorecard
- Esg Target
- Evidence Document
- Framework
- Framework Disclosure
- Gap Analysis
- General Ledger Entry
- Human Rights Assessment
- Internal Control
- Invoice
- Invoice Item
- Iot Meter Reading
- Legal Entity
- Materiality Assessment
- Materiality Topic
- Metric
- Metric Entry
- Module
- Notification Template
- Ohs Incident
- Organization
- Organization Module

### HTTP / API controllers

- Free Tools APIs & screens
- Public Portal APIs & screens
- Public Site APIs & screens

### Detailed feature requirements delivered

- MASTER PROMPT: ESG Reporting Platform (Auto-Generated Sustainability Reports)
- Mandatory Tech Stack
- Frontend for public pages: Blade + Tailwind CSS + Alpine.js (SEO friendly, fast, responsive, dark mode optional).
- Testing: Pest, Laravel Dusk or Playwright for key flows. Static analysis: Larastan level 6+. Code style: Laravel Pint.
- Application speed-up: apply across public pages, heavy report queries, caching, response optimization
- Scrape and catalog online data: emission factors, regulation updates, framework changes, industry benchmarks, peer company disclosures
- ALL AI features: report writing, gap analysis, materiality suggestions, data extraction, chat assistant. Provider must be switchable in settings (OpenAI, Gemini, etc.)
- Every PDF: sustainability reports, certificates, invoices, audit packs, in any language including Bangla, Arabic, Chinese, Hindi, etc.
- Slugs for all public content (pages, blog, frameworks, reports, help articles) in any language
- Architecture Requirements
- Multi-tenant: Organization (business) as tenant, with data isolation enforced by global scopes and policies. Provide a Super Admin level above all tenants.
- Data versioning and locking per reporting period (draft, in review, approved, locked, restated).
- Import/Export everywhere (CSV, XLSX, JSON, PDF), bulk import wizards with validation and error reports.
- Performance: use drutogoti for speed-up, query/index tuning, eager loading, chunked jobs, cache tags per tenant, queue for all heavy work.
- User Types and Roles
- Super Admin (platform owner): tenants, plans, modules, billing, global frameworks, emission factor libraries, system health, impersonation (logged).
- Platform Staff / Support Agent: tickets, tenant help, limited impersonation.
- Organization Owner / Admin: full control of their organization, modules, users, billing.
- ESG Manager / Sustainability Lead: runs the ESG program, approves data, generates reports.
- Data Contributor / Site Manager / Facility Manager: enters and uploads data for their branch/site only.
- Department Head / Approver: reviews and approves data and targets.
- Finance Officer: financial/ESG linkage, green finance, carbon pricing, invoicing.
- HR Officer: workforce, diversity, training, safety data.
- Procurement/Supply Chain Manager: supplier ESG assessments and Scope 3 data.
- Compliance & Risk Officer: regulations, risks, incidents, policies.
- Internal Auditor: read plus audit workpapers, findings, sign-off.
- External Auditor / Assurance Provider (limited-time access): read-only data room, evidence requests, assurance statements.
- Board Member / Executive: dashboards, approve final report, read-only drill-down.
- Investor / Lender / Rating Analyst (portal): view published disclosures, request data, ESG scorecards.
- Supplier / Vendor (portal): fill questionnaires, upload certificates, see their score and corrective actions.
- Customer / Client portal: view product footprints, certificates, EPD-style data.
- Consultant / Agency (multi-client): manage many client organizations from one login, white-label output.
- Reseller / Partner (if Reseller module ON): sells plans, sees commissions.
- Employee (self-service): survey, training, ethics hotline, commute/travel data entry.
- Whistleblower / Anonymous reporter: anonymous secure submission and case follow-up by token.
- Public Visitor / Guest: public pages only.
- API / Integration user: token-based machine access.
- Modules (ALL are toggleable)
- Tenant & Organization Management: onboarding wizard, profile, entities, branches, sites, departments, business units, org chart, consolidation settings.
- Module Manager and Plans: as in 3.2, with usage limits and upgrade prompts.
- Integrations Hub: API keys, webhooks, Zapier/Make style triggers, ERP/accounting connectors (CSV, REST), utility bill import, IoT/meter endpoints.
- Translation Manager (edit translations in UI, AI-assisted translation via laravel-ai-hub).
- Regulation Watch: scrape and catalog regulation and framework updates (via laravel-ai-catalog), AI summary of impact, alerts and tasks.
- Double Materiality Assessment: impact and financial materiality, stakeholder surveys, scoring, matrix visualization, topic library, approval and history.
- Stakeholder Engagement: stakeholder register, mapping, engagement log, surveys, feedback, grievance tracking.
- Risks and Opportunities: climate physical and transition risk, scenario analysis (1.5C, 2C, 4C), financial impact estimate, risk register, heatmap, mitigation plans, TCFD tables.
- Impact Valuation (optional): monetize environmental and social impacts.
- Energy Management: electricity, fuel, renewables, utility bill capture (upload + AI OCR extraction), meters, tariffs, energy intensity, efficiency projects.
- Water Management: withdrawal, discharge, recycling, water stress areas, intensity.
- Waste and Circularity: waste streams, disposal methods, recycling rate, hazardous waste, manifests, circular-economy metrics.
- Air Emissions and Effluents: NOx, SOx, PM, VOC, effluent quality, permit limits and alerts.
- Biodiversity and Land Use: sites near protected areas, TNFD LEAP workflow, land-use change.
- Materials and Product Footprint: raw materials, packaging, product carbon footprint, LCA lite, EPD-style sheets, recycled content.
- Climate Finance / Carbon Credits & Offsets: credit register, retirement tracking, internal carbon price, carbon tax/CBAM exposure estimator.
- Energy/IoT Data Ingestion: API/CSV/MQTT-style endpoints for meters and sensors, validation, anomaly detection.
- Workforce Data: headcount, turnover, hiring, diversity and inclusion, pay equity, gender pay gap, minimum wage compliance, benefits, parental leave.
- Health and Safety (OHS): incident reporting, near-miss, LTIFR/TRIR, investigations, corrective actions, safety inspections, PPE, drills, certifications.
- Training and Development: programs, attendance (via attendance hub), hours per employee, competency matrix.
- Human Rights and Labor Practices: due diligence, child/forced labor risk checks, grievance mechanism, freedom of association data.
- Community and Social Impact (CSR): projects, beneficiaries, spend, impact metrics, photo evidence, SDG tagging.
- Ethics Hotline / Whistleblower: anonymous submissions, token follow-up, case management, SLA, confidentiality controls.
- Employee Surveys and Engagement: pulse surveys, eNPS, anonymized analytics.
- Customer Welfare / Product Responsibility: complaints, product safety, data privacy incidents.
- Board and Governance Data: board composition, independence, diversity, committees, meetings, attendance, ESG oversight, executive remuneration links.
- Policies and Code of Conduct: policy library, versioning, acknowledgment tracking, reminders.
- Compliance Management: obligations register, permits and licenses, expiry alerts, inspections, fines, regulatory actions.
- Anti-Corruption / Ethics / Tax Transparency: training completion, incidents, country-by-country data, political contributions.
- Data Privacy and Cybersecurity Governance: incidents, audits, certifications (ISO 27001 etc.).
- Internal Controls over ESG Data: control library, testing, sign-offs, deficiency tracking.
- Supplier Management: supplier register, tiers, documents, risk scoring, onboarding portal.

### Platform capabilities

- Multi-role ready structure for operators, managers and admins
- Database migrations and seeders for demo-ready local setup
- Laravel architecture with services/controllers/models separation
- Responsive UI foundation (Blade / Filament where enabled)
- Environment-based config for local and production databases
- GitHub-ready documentation with visual product previews

## Tech stack

- PHP 8.2+ / Laravel
- Filament admin resources (where enabled)
- Vite asset pipeline
- SQLite/MySQL-ready database layer
- Migrations, seeders, and test-ready structure

## Quick start

```bash
git clone https://github.com/imranbru99/esg-reporting-platform-sustainability.git
cd esg-reporting-platform-sustainability
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Repository

- GitHub: https://github.com/imranbru99/esg-reporting-platform-sustainability
- Developer: **Imran Ahmed** · https://imrandev.bd

---

## 🤝 Let's Connect & Collaborate

I am open to **Senior Remote Full-Stack Roles**, AI Platform Architecture Contracts, and Enterprise Technical Advisory.

**Timezone:** UTC+6 (Dhaka / Rangpur, Bangladesh) — flexible overlap with US, UK & EU hours.  
**Delivery:** Async-ready · Slack · Discord · Jira · GitHub · production-first accountability.

| Channel | Handle | Link |
| :--- | :--- | :--- |
| Portfolio | imrandev.bd | https://imrandev.bd |
| Packagist | imrandevbd | https://packagist.org/packages/imrandevbd/ |
| LinkedIn | imranbru99 | https://linkedin.com/in/imranbru99 |
| GitHub | imranbru99 | https://github.com/imranbru99 |
| WhatsApp | +880 1576-918420 | https://wa.me/8801576918420 |
| Email | me@imrandev.bd | mailto:me@imrandev.bd |
| X | @imrandev_bd | https://x.com/imrandev_bd |
| YouTube | @ImranDevBD | https://youtube.com/@ImranDevBD |
