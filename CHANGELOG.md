# Changelog

All notable changes to the **ESG Reporting Platform** are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2026-11-07

### Added
- **Phase 1: Foundation Architecture & Tenancy**:
  - Multi-tenant hierarchical data model (`Organization`, `LegalEntity`, `Branch`, `Department`, `BusinessUnit`).
  - Single-database tenant isolation via `App\Scopes\OrganizationScope` and `App\Traits\BelongsToOrganization`.
  - Dynamic 75-module system registry (`modules`, `organization_modules`, `branch_modules`, `plans`, `presets`).
  - `ModuleGate` and `CheckModuleEnabled` middleware with `@module` Blade directive.
  - Filament base resource (`BaseModuleResource`) respecting module toggles.
  - Complete 22-role permission matrix seeded with `spatie/laravel-permission`.

- **Phase 2: ESG Frameworks & Calculation Engine**:
  - Global disclosure frameworks: GRI Universal 2021, ISSB (IFRS S1/S2), CSRD/ESRS, TCFD, TNFD, CDP, SDGs, Bangladesh Bank & BSEC, SEBI BRSR.
  - Cross-framework metric mapper (`DisclosureMetricMap`).
  - High-precision `UnitConverter` for mass, volume, energy, distance, and area.
  - `CalculationEngine` for Scope 1 direct fuel, Scope 2 location/market-based electricity, Scope 3 categories, YoY variance, and carbon intensity.
  - Factor libraries (DEFRA 2024, US EPA 2024, Bangladesh Grid 2024) with immutable calculation snapshots.
  - International regulation scraper and watcher (`RegulationWatch`).

- **Phase 3: Domain Modules (Environmental, Social, Governance)**:
  - 24 domain tables covering utility bills, water testing, waste manifests, air emissions, biodiversity sites, product footprints (DPP), carbon credits, and IoT meter readings.
  - Social compliance: workforce demographics, biometric turnstile attendance sync (`AttendanceHub`), OHS incidents / LTIFR, and whistleblower intake.
  - Governance: board independence, corporate policies, compliance obligations, anti-corruption audits, and internal controls.

- **Phase 4: Supply Chain, Materiality & Assurance**:
  - Double Materiality Matrix (Impact + Financial materiality X/Y quadrant scatter plot).
  - TCFD Climate physical and transition risk matrix with SBTi target tracking.
  - Supplier ESG assessments (Higg Index FEM 4.0, SMETA 4-Pillar), primary Scope 3 data ingestion, and batch traceability (EUDR).
  - Independent Assurance Workspace (ISAE 3000 / ISAE 3410) with sampling workpapers and historical restatement log.

- **Phase 5: Reporting Engine & Intelligence**:
  - Outline & Section tree builder with contributor and approver workflows.
  - AI Report Auto-Drafting Engine (`laravel-ai-hub`) with strict number citation guardrails (`ReportNarrativeCitation`).
  - Multilingual Unicode PDF publisher (`laravel-unicode-pdf`) for pixel-perfect reports and 2-page factsheets in English, Bengali (`বাংলা`), Arabic, etc.
  - Framework readiness Gap Analysis engine and internal ESG Scorecard calculator (AAA to CCC).
  - Conversational AI ESG Copilot chat assistant.

- **Phase 6: Commercial & Operations**:
  - CRM pipeline with deal stages and financial estimates.
  - Consultancy project engagement management with billable timesheets.
  - Multi-currency invoicing with itemized billing and Unicode PDF export.
  - Double-entry accounting lite with Green Capex / Green Opex taxonomy tags.
  - Help desk ticketing system with customer-agent messaging.
  - Sustainability training academy with Universal Slug and Unicode PDF completion certificates.

- **Phase 7: Public Website & Public Portals**:
  - Responsive Blade + Tailwind + Alpine.js marketing site (Home, Features, Frameworks, Industries, Roles, Modules Explorer, Pricing).
  - Interactive ROI & Carbon Abatement Calculator.
  - Public Portals: Supplier Portal, Investor Portal, Auditor Data Room, Anonymous Whistleblower Hotline.
  - Free Tools: Quick Carbon Footprint Calculator, ESG Readiness Quiz, Materiality Quick Check.
  - Live Demo Center with 1-click login into any of the 22 user role personas.

- **Phase 8: Demo System & Seeders**:
  - `php artisan demo:reset` command resetting entire database and seeders in under 10 seconds.
  - 3 realistic demo enterprises: Apex Textile & Apparel (Manufacturing), Bengal Commercial Bank (Financial), and CloudScale Software (Tech).
  - 22 demo user personas configured with password `password`.
  - Topbar live demo banner with instant role switcher.

- **Phase 9: Quality & Deployment**:
  - 43 feature and unit tests with 232 assertions passing with 100% success rate.
  - Larastan static analysis level 6 passing with zero errors.
  - Laravel Pint styling check passing cleanly.
  - FastPanel / VPS Nginx configuration, Supervisor Horizon worker config, and cron scheduling entry.

---

## Author & Developer Information

- **Architect & Full-Stack Engineer**: Imran Ahmed
- **Email**: me@imrandev.bd
- **Official Website**: [https://imrandev.bd](https://imrandev.bd)

