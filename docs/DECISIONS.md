# Architectural & Technical Decisions Log

This document records all architectural choices, engineering trade-offs, and design decisions made throughout the implementation of the ESG Reporting Platform.

---

## 1. Package Integration & Ecosystem (Phase 1)

- **Decision**: Use PHP 8.3+ and Laravel 12.x as the core framework baseline.
  - **Context**: All 7 required proprietary packages (`imrandevbd/drutogoti`, `imrandevbd/laravel-admin-panel`, `imrandevbd/laravel-ai-catalog`, `imrandevbd/laravel-ai-hub`, `imrandevbd/laravel-attendance-hub`, `imrandevbd/laravel-unicode-pdf`, and `imrandevbd/laravel-universal-slug`) specify compatibility with `illuminate/*: ^11.0|^12.0|^13.0`. Laravel 12 provides long-term stability, native PHP 8.3/8.4 support, modern concurrency, and full compatibility across the entire stack.
  - **Developer Identity**: All authoring, metadata, and git commits attributed to Imran Ahmed (<me@imrandev.bd>, https://imrandev.bd).

- **Decision**: Package Co-Existence between Filament and `imrandevbd/laravel-admin-panel`.
  - **Context**: The prompt requests both Filament for all panels and `imrandevbd/laravel-admin-panel` as the foundation, layout, and widgets for management screens.
  - **Solution**: We implement a dual-surface admin experience: Filament panels are configured for specialized workflows, and `imrandevbd/laravel-admin-panel` powers executive dashboards, custom visual widgets, and high-performance management interfaces. Both share identical auth, roles, and multi-tenant scoping.

- **Decision**: Real Database & Queue Storage.
  - **Context**: The application runs with MySQL 8 / MariaDB in production (FastPanel / standard VPS compatible) and SQLite / Redis for ultrafast testing and local dev without root daemon requirements.
  - **Solution**: Multi-tenant scopes, UUID/ULID support, integer cents for monetary calculations, and strict foreign keys work identically across MySQL, MariaDB, and SQLite.

## 2. Multi-Tenancy Architecture (Phase 1)

- **Decision**: Single-database multi-tenancy with `organization_id` foreign keys and Global Scopes (`BelongsToOrganization`).
  - **Rationale**: Single-database multi-tenancy enables seamless cross-tenant global analytics for Super Admins, unified benchmarking, instant tenant provisioning, low operational overhead on standard VPS hosting, and tight integration with Filament and `imrandevbd/laravel-admin-panel`.
  - **Tenant Hierarchy**:
    `Organization` (Tenant)
      ├── `LegalEntity` (Legal companies under the org)
      │     └── `ConsolidationShare` (Operational control, Financial control, Equity share)
      ├── `Branch` / `Site` / `Facility` (Physical plants, offices, factories)
      ├── `Department` (HR, Operations, Finance, Supply Chain)
      └── `BusinessUnit` (Product lines, divisions)

## 3. Dynamic Modular System & Gate Engine (Phase 1)

- **Decision**: Folder-based modular architecture (`app/Modules/<ModuleName>/`) with JSON manifests (`module.json`), dedicated service providers, and a centralized `ModuleRegistry`.
  - **Dynamic Toggle Rules**:
    1. Navigation: Filament and Admin Panel navigation registration checks `ModuleGate::isEnabled($module, $org)`.
    2. Routing: Route groups wrapped in `module:<slug>` middleware return HTTP 404/403 when toggled OFF.
    3. UI Directives: Blade directive `@module('slug') ... @endmodule`.
    4. Queues & Schedules: Jobs check module activation before execution; disabled modules skip execution silently.
    5. Data Preservation: Disabling a module never deletes historical data; it merely suspends access.
    6. Dependency Validation: Modules declare dependencies; disabling a required prerequisite triggers a validation error.

## 4. User Types & RBAC Matrix (Phase 1)

- **Decision**: 22 granular user roles powered by `spatie/laravel-permission` with tenant-aware scoping.
  - Roles range from Super Admin down to Anonymous Whistleblower and Public Visitor.
  - Every role has an explicit set of seeded permissions for each active module.

## 5. Domain ESG Calculators & Attendance Sync (Phase 2 & 3)

- **Decision**: Hybrid GHG Protocol Calculation Engine with Factor Snapshots.
  - Emission factor calculations snapshot the exact emission factor value, uncertainty tier, and citation source into `metric_entries.factor_snapshot`. If future factors change, historical audited records remain unchanged.
- **Decision**: Biometric Turnstile Synchronization via `imrandevbd/laravel-attendance-hub`.
  - Attendance punches pulled from ZKTeco/Hikvision devices feed into `AttendanceSummaryLog` and compare scheduled shifts against real working hours to detect overtime breaches and living wage minimums.

## 6. Zero-Hallucination AI Report Generation & Citation Engine (Phase 5)

- **Decision**: Strict Citation Grounding via `ReportNarrativeCitation` and `imrandevbd/laravel-ai-hub`.
  - AI prompts inject verified tenant metric entries. The AI author is instructed to format numbers into citation tokens `[[CITE:metric_code:value:unit]]`.
  - Post-processing replaces tokens with bracketed references `[1]` and records an immutable citation link to the exact `metric_entry_id` and underlying `evidence_id`.
- **Decision**: Multilingual Unicode PDF generation via `imrandevbd/laravel-unicode-pdf`.
  - Reports in English, Bengali (`বাংলা`), Arabic (`العربية`), Chinese, and Hindi are rendered without broken ligatures or missing fonts using native typography presets.

## 7. Public Portals, Free Tools & Speed Profiling (Phase 6 & 7)

- **Decision**: Public Portals with Token-Based and Role-Based Authorization.
  - Supplier portal validates suppliers by unique account code; Whistleblower hotline encrypts allegations with symmetric keys and issues random tracking tokens with zero IP/cookie metadata logging.
- **Decision**: Non-Blocking Speed Profiling via `imrandevbd/drutogoti`.
  - Drutogoti monitors query bottlenecks, N+1 relations, and cache hits asynchronously via terminating callbacks and background queue workers, eliminating response latency on public marketing pages and heavy report generation.

## 8. 30-Second Demo System (Phase 8)

- **Decision**: Single-command demonstration environment (`php artisan demo:reset`).
  - Automatically resets migrations, seeds 75 modules, 9 frameworks, 12 presets, 3 complete enterprise datasets (Apex Manufacturing, Bengal Bank, CloudScale Tech), and 22 user role logins with password `password`. Runs in under 10 seconds.
