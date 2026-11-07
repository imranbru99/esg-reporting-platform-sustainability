# Proprietary Packages Integration Guide

This document records the exact usage, API integration, and configuration of our 7 required packages in the **ESG Reporting Platform**.

---

## 1. `imrandevbd/drutogoti`
- **Purpose**: Application performance profiler, query analysis, cache optimization, and zero-overhead response acceleration.
- **Packagist**: `imrandevbd/drutogoti`
- **Repository**: [https://github.com/imranbru99/laravel-speed-optimization-drutogoti](https://github.com/imranbru99/laravel-speed-optimization-drutogoti)
- **Author**: Imran Ahmed (<me@imrandev.bd>, [https://imrandev.bd](https://imrandev.bd))
- **Usage in ESG Platform**:
  - Middleware registered on all web and API routes to monitor N+1 queries during heavy ESG metric rollups.
  - Telemetry stored via Redis queues in production (`DRUTOGOTI_STORAGE_DRIVER=queue`).
  - Query watcher triggers alerts on unindexed metric queries exceeding 100ms.
  - Image scanner audits evidence documents and public report media assets.
- **Config**: `config/drutogoti.php`

---

## 2. `imrandevbd/laravel-admin-panel`
- **Purpose**: Admin dashboard foundation, widgets, responsive layout, dynamic data views, and telemetry.
- **Packagist**: `imrandevbd/laravel-admin-panel`
- **Repository**: [https://github.com/imranbru99/laravel-admin-panel](https://github.com/imranbru99/laravel-admin-panel)
- **Author**: Imran Ahmed (<me@imrandev.bd>, [https://imrandev.bd](https://imrandev.bd))
- **Usage in ESG Platform**:
  - Provides executive dashboard layout, KPI widgets, multi-tenant navigation, and quick management views.
  - Used for rapid business operations screens, system diagnostics, and audit log inspection.
  - Works alongside Filament with shared session auth and permission scopes.
- **Config**: `config/admin-panel.php`
- **Artisan Commands**: `admin-panel:publish`, `admin-panel:install`

---

## 3. `imrandevbd/laravel-ai-catalog`
- **Purpose**: Autonomous online data scraping, regulation updates, emission factors, peer disclosures, and Digital Product Passports (DPP).
- **Packagist**: `imrandevbd/laravel-ai-catalog`
- **Repository**: [https://github.com/imranbru99/laravel-ai-catalog](https://github.com/imranbru99/laravel-ai-catalog)
- **Author**: Imran Ahmed (<me@imrandev.bd>, [https://imrandev.bd](https://imrandev.bd))
- **Usage in ESG Platform**:
  - `AiCatalog::fromUrl($regulationUrl)`: Scrapes international framework amendments (GRI, ISSB, CSRD) into a draft review queue.
  - `AiCatalog::fromDatasheet($file)`: Extracts technical emission factors and LCA specs from supplier documentation.
  - Generates Digital Product Passports (DPP) containing carbon footprint (kg CO2e), recyclability, and repairability metrics for products.
- **Config**: `config/ai-catalog.php`
- **Artisan Commands**: `ai-catalog:audit`, `ai-catalog:agentic-feed`

---

## 4. `imrandevbd/laravel-ai-hub`
- **Purpose**: Unified multi-provider AI SDK (OpenAI, Gemini, Claude, DeepSeek, Ollama, etc.) with dynamic failover, structured JSON outputs, streaming, and cost tracking.
- **Packagist**: `imrandevbd/laravel-ai-hub`
- **Repository**: [https://github.com/imranbru99/laravel-ai-hub](https://github.com/imranbru99/laravel-ai-hub)
- **Author**: Imran Ahmed (<me@imrandev.bd>, [https://imrandev.bd](https://imrandev.bd))
- **Usage in ESG Platform**:
  - Powers the **Auto Report Generator**: Writes structured narrative disclosures strictly citing database figures without hallucination.
  - `AIHub::structured($prompt, $schema)`: Extracts tabular data from utility invoices and ESG surveys.
  - Chat assistant ("Ask your ESG data"): Multi-turn conversational interface querying tenant-specific datasets.
  - Materiality scoring recommendations and gap analysis action plans.
  - Switchable provider via database settings (Gemini, OpenAI, Claude, Local LLMs).
- **Testing**: Uses `AIHub::fake()` in automated Pest test suites.
- **Config**: `config/ai-hub.php`

---

## 5. `imrandevbd/laravel-attendance-hub`
- **Purpose**: Hardware-agnostic biometric and attendance terminal integration (ZKTeco, Hikvision, Suprema, Dahua, Anviz, etc.).
- **Packagist**: `imrandevbd/laravel-attendance-hub`
- **Repository**: [https://github.com/imranbru99/laravel-attendance-hub](https://github.com/imranbru99/laravel-attendance-hub)
- **Author**: Imran Ahmed (<me@imrandev.bd>, [https://imrandev.bd](https://imrandev.bd))
- **Usage in ESG Platform**:
  - Pulls workforce punch logs from factory and office turnstiles.
  - Automatically calculates total working hours, overtime hours, and rest periods for labor compliance (GRI 401/402/403 and Bangladesh Labor Act / factory audits).
  - Syncs training session attendance and safety drill participation directly into ESG social metric registers.
  - Test simulation via `AttendanceHub::fake()` and `attendance:simulate`.
- **Config**: `config/attendance-hub.php`

---

## 6. `imrandevbd/laravel-unicode-pdf`
- **Purpose**: Universal multilingual and complex-script PDF generation with native support for Bengali, Arabic, Chinese, Hindi, and European languages.
- **Packagist**: `imrandevbd/laravel-unicode-pdf`
- **Repository**: [https://github.com/imranbru99/laravel-unicode-pdf](https://github.com/imranbru99/laravel-unicode-pdf)
- **Author**: Imran Ahmed (<me@imrandev.bd>, [https://imrandev.bd](https://imrandev.bd))
- **Usage in ESG Platform**:
  - Generates official sustainability reports in PDF format in English, Bengali, Arabic, and other target languages.
  - Generates audit packs, assurance statement certificates, ESG invoices, and employee training certificates.
  - API:
    ```php
    UnicodePdf::locale($locale)
        ->loadView('reports.sustainability', $reportData)
        ->setPaper('a4')
        ->download("sustainability-report-{$year}.pdf");
    ```
- **Config**: `config/unicode-pdf.php`

---

## 7. `imrandevbd/laravel-universal-slug`
- **Purpose**: Universal, multilingual, and Unicode-safe URL slug generator for Eloquent models with automatic 301 redirects and collision handling.
- **Packagist**: `imrandevbd/laravel-universal-slug`
- **Repository**: [https://github.com/imranbru99/laravel-universal-slug](https://github.com/imranbru99/laravel-universal-slug)
- **Author**: Imran Ahmed (<me@imrandev.bd>, [https://imrandev.bd](https://imrandev.bd))
- **Usage in ESG Platform**:
  - Generates clean, SEO-optimized slugs in any language (Bengali, Arabic, Hindi, Chinese, English) for CMS articles, public framework pages, peer benchmarks, published sustainability reports, and help center topics.
  - Models implement the `HasUniversalSlug` trait.
  - Blade directive `@slug($text)` and helper `universal_slug($text)`.
- **Config**: `config/universal-slug.php`

