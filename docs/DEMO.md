# Live Demo Environment & Demonstration Guide

The ESG Reporting Platform is equipped with an evaluation-ready demonstration system featuring **3 realistic enterprise profiles** spanning different industries, with **2-3 years of monthly data**, and **22 pre-configured user personas**.

---

## 1. Quick Start / Reset Command

Run the single artisan command to completely wipe and re-seed clean demonstration data in under 10 seconds:

```bash
php artisan demo:reset
```

Expected output:
```text
🌿 Resetting ESG Reporting Platform Demo Environment...
✅ Demo environment reset successfully in 8.76 seconds!
   - 3 Demo Enterprises seeded (Apex Textile, Bengal Bank, CloudScale)
   - 75 Modules, 9 Global Frameworks, and 12 Industry Presets loaded
   - 22 Demo User Roles created with password 'password'
   - Explore at: http://localhost:8000/demo-center or /admin
```

---

## 2. The 3 Demo Organizations

### A. Apex Textile & Apparel Park Ltd
- **Industry**: Manufacturing / Textiles & Apparel (RMG sector, Bangladesh)
- **Profile**: 2,500 employees, 3 operating facilities (Gazipur Complex, Savar Dyeing, Chittagong Port).
- **Active Data**:
  - Scope 1 direct combustion (natural gas boilers and diesel backup generators).
  - Scope 2 grid electricity consumption.
  - Water consumption, wastewater testing, and ZDHC zero chemical discharge records.
  - Biometric turnstile attendance machine synchronization (`laravel-attendance-hub`) monitoring working hours and overtime compliance.
  - Higg Index Facility Environmental Module (FEM 4.0) supplier scorecards.
  - Locked 2024 GRI Sustainability Report with cryptographic SHA-256 fingerprint.

### B. Bengal Commercial Bank PLC
- **Industry**: Banking & Financial Institutions
- **Profile**: 45 regional branches, 800 employees, sustainable finance desk.
- **Active Data**:
  - Financed emissions (PCAF methodology, Scope 3 Category 15).
  - Commercial loan ESG risk screening checklists.
  - Bangladesh Bank Sustainable Finance Policy green refinance compliance.
  - Rooftop solar energy financing and Green Capex ledger tracking.

### C. CloudScale Software Technologies Ltd
- **Industry**: Technology / Cloud & SaaS Services
- **Profile**: 120 employees, remote-first distributed workforce.
- **Active Data**:
  - Scope 1 zero direct combustion.
  - Scope 2 market-based accounting with renewable energy Power Purchase Agreements (PPAs).
  - Scope 3 cloud data center server allocation (AWS/GCP) and employee commute surveys.
  - ISO 27001 / SOC 2 cybersecurity evidence vault.

---

## 3. The 22 Demo User Logins

All demo accounts share the standard password: `password`

| Role Title | Demo Email | Role Key | Focus Area |
| :--- | :--- | :--- | :--- |
| **Super Admin (Platform Owner)** | `superadmin@platform.test` | `super_admin` | Global platform administration, plans, modules |
| **Platform Staff** | `staff@platform.test` | `platform_staff` | Customer support, assisted tenant onboarding |
| **Apex CEO (Organization Admin)**| `owner@apex.test` | `org_admin` | Full enterprise control, billing, user access |
| **ESG Director Samantha** | `esg-manager@apex.test` | `esg_manager` | Program lead, AI report drafting, targets |
| **Gazipur Facility Lead Rafiq** | `facility@apex.test` | `site_manager` | Branch data entry, meter readings, utility bills |
| **Dept Approver Dr. Alam** | `approver@apex.test` | `dept_head` | Review workflow approval, anomaly sign-off |
| **Bank CFO Tanveer** | `cfo@bengalbank.test` | `finance_officer` | Green Capex ledger, invoicing, PCAF finance |
| **HR Compliance Farzana** | `hr@apex.test` | `hr_officer` | Turnstiles, living wage, safety compliance |
| **Procurement Lead Tariq** | `procurement@apex.test` | `procurement_manager`| Supplier assessments, Higg FEM, Scope 3 |
| **Compliance Officer Shireen** | `compliance@bengalbank.test`| `compliance_officer` | Regulation watcher (AI Catalog), legal permits |
| **Internal Auditor Hasan** | `internal-auditor@bengalbank.test`| `internal_auditor`| Internal audit workpapers, control matrices |
| **KPMG Lead Auditor David** | `auditor@kpmg.test` | `external_auditor` | Independent data room, ISAE 3000 sign-off |
| **Board Director Amina** | `board@bengalbank.test` | `executive` | Board scorecards, peer benchmarks, sign-off |
| **BlackRock Analyst Lucas** | `investor@blackrock.test` | `investor_portal` | Published disclosures, ESG data book |
| **Cotton Supplier Rahim** | `supplier@cottondelta.test` | `supplier_portal` | Supplier portal questionnaire submission |
| **H&M Buyer Partner Lisa** | `client@hmgroup.test` | `customer_portal` | Digital Product Passports (DPP), footprint |
| **Veritas Consultant Marcus** | `consultant@veritas.test` | `consultant` | Multi-client agency console, white-label |
| **EcoPartner Reseller Neil** | `reseller@ecopartner.test` | `reseller` | Sublicensing, commission payouts |
| **Software Engineer Fahim** | `employee@cloudscale.test` | `employee` | Training academy, materiality survey |
| **Confidential Whistleblower** | `whistleblower@anonymous.test`| `whistleblower`| Encrypted allegation intake, secret token |
| **Public Visitor Guest** | `visitor@public.test` | `public_visitor` | Marketing site, free carbon calculator |
| **IoT Meter Bot** | `api@integration.test` | `api_user` | REST API meter ingestion, Sanctum token |

---

## 4. 1-Click Role Switcher

When running locally or in demo mode:
- Visit `/demo-center` to click into any role without typing passwords.
- Use the **Live Demo Banner** at the top of the Filament dashboard to switch personas instantly.

---

## Author & Developer Information

- **Developer**: Imran Ahmed
- **Email**: me@imrandev.bd
- **Official Website**: [https://imrandev.bd](https://imrandev.bd)

