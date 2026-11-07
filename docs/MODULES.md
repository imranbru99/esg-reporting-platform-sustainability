# Module Registry & Toggleable Architecture (75 Modules)

The **ESG Reporting Platform** is engineered around a dynamic, toggleable module architecture. Every capability in the system belongs to an autonomous module defined in the `modules` registry table. When a module is disabled for a tenant or branch:
- Its Filament navigation items are hidden automatically via `BaseModuleResource::shouldRegisterNavigation()`.
- Its routes and API endpoints return `403 Forbidden` via `CheckModuleEnabled` middleware and `ModuleGate::check()`.
- Its widgets and dashboard cards are suppressed.
- Scheduled tasks and queue workers skip background processing for that module.
- All historical data is safely preserved so re-enabling immediately restores full functionality.

---

## 1. Module Inventory by Category

### A. Platform & Tenancy (Core)
| Module Slug | Name | Core? | Dependencies |
| :--- | :--- | :--- | :--- |
| `tenant_management` | Tenant & Multi-Organization | **Yes** | None |
| `module_manager` | Dynamic Module Registry | **Yes** | None |
| `user_roles` | RBAC & Permissions (22 Roles) | **Yes** | `tenant_management` |
| `audit_trail` | Immutable Activity Log | **Yes** | `tenant_management` |
| `system_health` | System Telemetry & Health | **Yes** | None |
| `settings_center` | White-Label & Localization | **Yes** | None |
| `notification_center`| Multi-Channel Alerts | No | None |
| `api_integrations` | REST API & Webhooks | No | None |

### B. ESG Frameworks & Standards
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `framework_gri` | GRI Universal Standards 2021 | No | GRI 2, 3, 200, 300, 400 indicators |
| `framework_csrd_esrs`| CSRD / European ESRS | No | E1-E5, S1-S4, G1 European standards |
| `framework_issb` | ISSB (IFRS S1 & S2) | No | Global baseline for investor disclosures |
| `framework_tcfd` | TCFD Climate Disclosures | No | Governance, Strategy, Risk, Metrics |
| `framework_tnfd` | TNFD Nature & Biodiversity | No | LEAP approach nature disclosures |
| `framework_cdp` | CDP Climate Disclosure | No | CDP question mapping and exports |
| `framework_sdg` | UN Sustainable Development Goals | No | 17 SDGs target alignment |
| `framework_bsec_bb` | Bangladesh Bank & BSEC Guidelines | No | Sustainable Finance Policy & mandatory green reporting |
| `framework_brsr` | SEBI BRSR (India) | No | Business Responsibility and Sustainability Reporting |
| `disclosure_mapper` | Cross-Framework Metric Mapper | **Yes** | Single data collection mapped across frameworks |

### C. Environmental Performance (E)
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `carbon_scope1` | Scope 1 Direct Emissions | **Yes** | Stationary, mobile combustion, fugitive gases |
| `carbon_scope2` | Scope 2 Indirect Electricity | **Yes** | Dual reporting: Location-based & Market-based |
| `carbon_scope3` | Scope 3 Value Chain (15 Cats) | No | Purchased goods, logistics, travel, investments |
| `utility_bills` | Utility Bill OCR & Ingestion | No | AI Hub powered automated bill parsing |
| `energy_management` | Fuel & Energy Accounting | No | GJ, kWh, MMBtu normalization |
| `water_management` | Water Withdrawal & Effluent | No | Consumption, discharge quality, ZDHC testing |
| `waste_circularity` | Waste Manifests & Recycling | No | Hazardous, non-hazardous, circular diversion |
| `air_emissions` | Point-Source Air Pollutants | No | NOx, SOx, VOC, Particulate Matter (PM2.5/10) |
| `biodiversity_nature`| Protected Sites & TNFD | No | Operations near key biodiversity areas (KBAs) |
| `product_footprint` | Digital Product Passport (DPP) | No | Product-level lifecycle footprint & circularity |
| `carbon_credits` | Carbon Offsets & Registry | No | Verra, Gold Standard retirement tracking |
| `iot_meters` | Smart IoT Meter Telemetry | No | Real-time interval energy/water sub-metering |

### D. Social Responsibility (S)
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `workforce_snapshot`| Demographics & Headcount | No | Full-time, contract, turnover, remuneration |
| `attendance_sync` | Biometric Turnstile Sync | No | AttendanceHub machine sync for overtime compliance |
| `living_wage` | Fair Living Wage Audits | No | Anker methodology living wage benchmark comparison |
| `ohs_incidents` | Health & Safety / LTIFR | No | Incident investigation, near misses, OSHA logs |
| `training_education`| Worker Capacity Building | No | Training hours per employee by gender & role |
| `human_rights` | Due Diligence & Forced Labor | No | Child labor prevention, freedom of association |
| `csr_projects` | Community Investment & Philanthropy | No | Beneficiaries, budget impact, SDG linkage |
| `whistleblower` | Anonymous Cryptographic Intake | No | Encrypted allegations with zero metadata tracking |
| `employee_surveys` | ESG Materiality Pulse Surveys | No | Worker satisfaction and ethics surveys |
| `customer_welfare` | Product Safety & Complaints | No | Recall tracking, consumer privacy, quality cases |

### E. Corporate Governance (G)
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `board_governance` | Board Structure & Independence | No | Committee charters, independence %, diversity |
| `corporate_policies`| Policy & Attestation Engine | No | Code of conduct, anti-bribery, policy sign-off |
| `compliance_obligations`| Legal Permits & Licenses | No | Environmental permits, expiration alerts |
| `anti_corruption` | Anti-Bribery & Fraud Audits | No | High-risk vendor reviews and training logs |
| `cybersecurity_audit`| InfoSec & Privacy (ISO 27001) | No | Penetration tests, GDPR data privacy compliance |
| `internal_controls` | Risk Management & Internal Audit | No | COSO framework control matrices & test results |
| `tax_transparency` | Country-by-Country Tax (GRI 207)| No | Corporate income tax payments by jurisdiction |
| `remuneration_link` | ESG Executive Incentive Link | No | Linking executive bonuses to ESG KPIs |

### F. Strategy, Materiality & Supply Chain
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `double_materiality`| Impact & Financial Matrix | **Yes** | X/Y quadrant scatter plot with stakeholder weighting |
| `stakeholder_engagement`| Stakeholder Prioritization | No | Dialogue records, expectations, survey inputs |
| `target_setting` | Net Zero & SBTi Targets | No | Science-Based Targets (1.5°C), interim milestones |
| `climate_risks` | TCFD Physical & Transition Risk | No | Climate scenario modeling (RCP 2.6 / 8.5) |
| `supplier_onboarding`| Supplier ESG Due Diligence | No | Supplier profiles, tier categorization, risk ratings |
| `supplier_assessments`| Higg FEM & SMETA Audits | No | Standardized questionnaire scoring & corrective plans |
| `supplier_scope3` | Primary Scope 3 Ingestion | No | Supplier activity data and spend-based allocation |
| `traceability_chain`| Batch Raw Material Traceability| No | GOTS, BCI, FSC origin tracking & EUDR compliance |

### G. Assurance Workspace
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `assurance_workspace`| External Auditor Data Room | No | ISAE 3000 / 3410 limited & reasonable assurance |
| `sampling_tools` | Audit Sample Selection | No | Random and risk-weighted transaction sampling |
| `restatements` | Historical Data Restatements | No | Justification logs, YoY recalculation adjustments |

### H. Reporting & Analytics
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `report_builder` | Outline Builder & Campaign Hub| **Yes** | Drag-and-drop section tree with contributor assignment |
| `ai_auto_drafting` | AI Report Writer (AI Hub) | **Yes** | Zero-hallucination drafting citing verified datapoints |
| `pdf_publisher` | Unicode PDF Multilingual Engine| **Yes** | Pixel-perfect PDF in English, Bangla, Arabic, etc. |
| `esg_scorecards` | Internal ESG Rating (AAA - CCC)| No | Weighted pillar algorithm scoring (0-100) |
| `gap_analysis` | Readiness Scoring & Missing Data| **Yes** | Audit gap identification and remediation checklist |
| `ai_chat_assistant` | Conversational ESG Copilot | No | Natural language Q&A over corporate ESG records |

### I. Commercial & Operations
| Module Slug | Name | Core? | Description |
| :--- | :--- | :--- | :--- |
| `crm_pipeline` | Leads & Proposal Pipeline | No | Client deals, pipeline stages, contract values |
| `project_management`| Consultancy Engagements | No | Milestones, billable timesheets, team allocations |
| `invoicing_billing`| Invoicing & Subscriptions | No | Multi-currency invoices generated via Unicode PDF |
| `accounting_lite` | Green Capex/Opex Ledger | No | Double-entry ledger with green taxonomy tags |
| `helpdesk_tickets` | Support Desk & SLA Management | No | Client inquiries, canned responses, SLA tracking |
| `academy_courses` | Training Academy & Quizzes | No | Sustainability courses & Unicode PDF certificates |

---

## 2. Industry Presets

Organizations can enable optimal module configurations with one click using built-in presets:

1. **Manufacturing & Industrial**: Focuses on Scope 1 direct combustion, utility bill OCR, water effluent, hazardous waste, and OHS incident tracking.
2. **Garment & Textile (RMG)**: Includes Higg Index FEM, turnstile attendance machine sync, living wage audits, and EUDR batch traceability.
3. **Banking & Financial Institutions**: Enables financed emissions (PCAF Cat 15), loan ESG screening, and green bond tracking.
4. **Technology & Cloud Services**: Activates Scope 2 renewable PPAs, cloud server Scope 3 allocation, data privacy (ISO 27001), and remote commute surveys.
5. **Real Estate & Construction**: Configures GRESB metrics, green building certifications (LEED/BREEAM), and embodied carbon in building materials.
6. **Agriculture & Food**: Prioritizes soil carbon, deforestation-free supply chains (EUDR), water irrigation efficiency, and fair labor audits.
7. **ESG Consultancy & Audit Firm**: Enables multi-client management, auditor data rooms, timesheets, and white-label report exports.
8. **Enterprise Complete**: Enables all 75 modules with unlimited capacity.

---

## Author & Architecture Identity

- **Architect & Full-Stack Lead**: Imran Ahmed
- **Email**: me@imrandev.bd
- **Official Website**: [https://imrandev.bd](https://imrandev.bd)

