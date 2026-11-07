@extends('layouts.public')

@section('title', 'Live Demo Center - 22 Role Personas')

@section('content')
<div class="py-16 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <div class="inline-flex items-center gap-2 bg-emerald-900/60 border border-emerald-500/40 text-emerald-300 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">
            <span>⚡ Zero-Setup Interactive Exploration</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black">Live Enterprise Demo Center</h1>
        <p class="mt-4 text-slate-300 text-base max-w-2xl mx-auto">
            Click any of the 22 pre-configured enterprise user roles below to instantly log in and experience their tailored dashboard, permissions, and workflow capabilities.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $roleDefinitions = [
                'super_admin' => ['title' => 'Super Admin (Platform Owner)', 'badge' => 'Platform', 'desc' => 'Multi-tenant management, commercial plans, global emission libraries, and platform health monitoring.'],
                'platform_staff' => ['title' => 'Platform Staff / Support Agent', 'badge' => 'Support', 'desc' => 'Tenant onboarding support, customer ticket handling, and assisted audits.'],
                'org_admin' => ['title' => 'Apex CEO / Organization Admin', 'badge' => 'Tenant Owner', 'desc' => 'Full control over company legal entities, branches, users, and commercial subscriptions.'],
                'esg_manager' => ['title' => 'ESG Director Samantha', 'badge' => 'Core Program', 'desc' => 'Full control over company GHG inventory, materiality matrix, targets, and auto-generated reports.'],
                'site_manager' => ['title' => 'Gazipur Facility Lead Rafiq', 'badge' => 'Site Ops', 'desc' => 'Restricted branch scope: inputs utility bills, meter readings, water tests, and site waste manifests.'],
                'dept_head' => ['title' => 'Dept Approver Dr. Alam', 'badge' => 'Approval', 'desc' => 'Multi-step review workflows, data approval checkpoints, and anomalous metric flagging.'],
                'finance_officer' => ['title' => 'Bank CFO Tanveer', 'badge' => 'Finance', 'desc' => 'Green finance accounting, EU taxonomy alignment, carbon pricing, and invoicing.'],
                'hr_officer' => ['title' => 'HR Compliance Farzana', 'badge' => 'Workforce', 'desc' => 'Attendance machine turnstile sync, living wage records, diversity analytics, and safety training.'],
                'procurement_manager' => ['title' => 'Procurement Lead Tariq', 'badge' => 'Supply Chain', 'desc' => 'Supplier ESG risk assessments, Higg Index scores, Scope 3 primary data ingestion, and EUDR.'],
                'compliance_officer' => ['title' => 'Risk Officer Shireen', 'badge' => 'Risk & Legal', 'desc' => 'International regulation watcher (AI Catalog), policy acknowledgment tracking, and legal obligations.'],
                'internal_auditor' => ['title' => 'Internal Auditor Hasan', 'badge' => 'Audit', 'desc' => 'Full read access with audit workpaper logging, finding categorization, and internal control reviews.'],
                'external_auditor' => ['title' => 'KPMG Lead Auditor David', 'badge' => 'Assurance', 'desc' => 'Limited-time data room access, cryptographically hashed evidence verification, ISAE 3000 sign-off.'],
                'executive' => ['title' => 'Board Director Amina', 'badge' => 'Governance', 'desc' => 'High-level executive scorecards, peer benchmark comparison, risk heatmaps, and final report sign-off.'],
                'investor_portal' => ['title' => 'BlackRock ESG Analyst Lucas', 'badge' => 'External', 'desc' => 'Access to published disclosures, interactive ESG data books, carbon intensity metrics, and green bonds.'],
                'supplier_portal' => ['title' => 'Cotton Supplier Rahim', 'badge' => 'Portal', 'desc' => 'Completes tier ESG questionnaires, uploads material certifications (GOTS, BCI), tracks corrective actions.'],
                'customer_portal' => ['title' => 'H&M Buyer Partner Lisa', 'badge' => 'Portal', 'desc' => 'Views Digital Product Passports (DPP), product lifecycle carbon footprints, and recycling instructions.'],
                'consultant' => ['title' => 'Veritas Consultant Marcus', 'badge' => 'Agency', 'desc' => 'Manages multiple client organizations from a unified console with white-label output.'],
                'reseller' => ['title' => 'EcoPartner Reseller Neil', 'badge' => 'Commercial', 'desc' => 'Sells subscription plans, tracks enterprise deal registrations, and views commission payouts.'],
                'employee' => ['title' => 'Software Engineer Fahim', 'badge' => 'Staff', 'desc' => 'Enrolls in Academy courses, participates in materiality surveys, and logs daily commute emissions.'],
                'whistleblower' => ['title' => 'Confidential Whistleblower', 'badge' => 'Ethics', 'desc' => 'Encrypted reporting of corporate misconduct with zero metadata tracking and follow-up tokens.'],
                'public_visitor' => ['title' => 'Public Visitor Guest', 'badge' => 'Public', 'desc' => 'Browses public disclosures, free carbon footprint calculator, and framework catalog.'],
                'api_user' => ['title' => 'IoT Meter System Bot', 'badge' => 'System', 'desc' => 'Token-based machine-to-machine REST API ingestion for IoT meters and ERP sync.'],
            ];
        @endphp

        @foreach($roleDefinitions as $roleKey => $roleInfo)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                            {{ $roleInfo['badge'] }}
                        </span>
                        <span class="text-xs font-mono text-emerald-600 font-bold">role: {{ $roleKey }}</span>
                    </div>

                    <h3 class="font-bold text-slate-900 text-base mb-2">{{ $roleInfo['title'] }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-6">{{ $roleInfo['desc'] }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ route('demo.login', $roleKey) }}" class="block text-center w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        1-Click Sign In as {{ explode(' ', $roleInfo['title'])[0] }} &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
