@extends('layouts.public')

@section('title', 'ESG Reporting Platform - Auto-Generated Sustainability Reports')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-emerald-950 via-slate-900 to-slate-900 text-white pt-20 pb-24 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px]"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 bg-emerald-900/60 border border-emerald-500/40 text-emerald-300 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-8 shadow-sm">
            <span>✨ Powered by AI Hub & Unicode PDF Engines</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight max-w-5xl mx-auto">
            Auto-Generated Sustainability Reports <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-200">
                Backed by 100% Cryptographic Data Traceability
            </span>
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-slate-300 max-w-3xl mx-auto font-normal leading-relaxed">
            The all-in-one software solution that runs the complete ESG and sustainability business: automated data ingestion, Scope 1-3 carbon calculations, hallucination-free AI report authoring, multi-lingual Unicode PDF publishing, and independent assurance data rooms.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ route('demo.center') }}" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-500 text-slate-950 font-extrabold rounded-xl shadow-lg hover:bg-emerald-400 transition transform hover:-translate-y-0.5">
                Launch Live Demo Center &rarr;
            </a>
            <a href="{{ route('modules.explorer') }}" class="w-full sm:w-auto px-8 py-3.5 bg-slate-800 text-white border border-slate-700 font-bold rounded-xl hover:bg-slate-750 transition">
                Explore All {{ $totalModules }} Modules & Pricing
            </a>
        </div>

        <!-- Metrics Ticker Bar -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto pt-10 border-t border-slate-800 text-center">
            <div>
                <div class="text-3xl font-black text-emerald-400">{{ $totalModules }}</div>
                <div class="text-xs text-slate-400 uppercase tracking-wider mt-1">Modular Toggle System</div>
            </div>
            <div>
                <div class="text-3xl font-black text-emerald-400">{{ $totalFrameworks }}</div>
                <div class="text-xs text-slate-400 uppercase tracking-wider mt-1">Global Frameworks Built-in</div>
            </div>
            <div>
                <div class="text-3xl font-black text-emerald-400">22</div>
                <div class="text-xs text-slate-400 uppercase tracking-wider mt-1">Role Permission Matrix</div>
            </div>
            <div>
                <div class="text-3xl font-black text-emerald-400">0%</div>
                <div class="text-xs text-slate-400 uppercase tracking-wider mt-1">AI Hallucination Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- Frameworks Ribbon -->
<section class="py-6 bg-slate-100 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Interoperable with all International Sustainability Standards</p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-14 font-extrabold text-slate-600 text-sm">
            <span>GRI Universal 2021</span>
            <span>ISSB (IFRS S1/S2)</span>
            <span>CSRD / ESRS</span>
            <span>TCFD</span>
            <span>TNFD</span>
            <span>CDP Climate</span>
            <span>UN SDGs</span>
            <span>Bangladesh Bank & BSEC</span>
            <span>SEBI BRSR</span>
        </div>
    </div>
</section>

<!-- 3 Pillars Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Comprehensive Architecture</h2>
            <p class="text-3xl font-black text-slate-900 mt-2">Every Dimension of ESG Covered End-to-End</p>
            <p class="text-slate-600 mt-3 text-sm">Turn any module ON or OFF with single-click tenant isolation. Disabled features cleanly disappear from menus, routes, and queue jobs.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Environmental Card -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mb-6">🌱</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Environmental (E)</h3>
                <ul class="space-y-2 text-xs text-slate-600">
                    <li class="flex items-center gap-2">✓ Automated Scope 1 direct combustion tracking</li>
                    <li class="flex items-center gap-2">✓ Scope 2 dual reporting (Location & Market)</li>
                    <li class="flex items-center gap-2">✓ Scope 3 supplier engagement (15 Categories)</li>
                    <li class="flex items-center gap-2">✓ Utility bills OCR data extraction (AI Hub)</li>
                    <li class="flex items-center gap-2">✓ Water withdrawal, discharge & chemical testing</li>
                    <li class="flex items-center gap-2">✓ Hazardous waste & circular DPP product passport</li>
                </ul>
            </div>

            <!-- Social Card -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl mb-6">👥</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Social (S)</h3>
                <ul class="space-y-2 text-xs text-slate-600">
                    <li class="flex items-center gap-2">✓ Biometric turnstile sync (Attendance Hub)</li>
                    <li class="flex items-center gap-2">✓ Living wage & working hour compliance</li>
                    <li class="flex items-center gap-2">✓ Occupational Health & Safety (OHS / LTIFR)</li>
                    <li class="flex items-center gap-2">✓ Diversity, gender pay ratio & workforce metrics</li>
                    <li class="flex items-center gap-2">✓ Anonymous Whistleblower cryptographic intake</li>
                    <li class="flex items-center gap-2">✓ Employee training academy & certifications</li>
                </ul>
            </div>

            <!-- Governance Card -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl mb-6">⚖️</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Governance (G)</h3>
                <ul class="space-y-2 text-xs text-slate-600">
                    <li class="flex items-center gap-2">✓ Board independence & committee composition</li>
                    <li class="flex items-center gap-2">✓ Double Materiality Matrix (Impact + Financial)</li>
                    <li class="flex items-center gap-2">✓ TCFD Climate physical & transition risk matrix</li>
                    <li class="flex items-center gap-2">✓ Anti-bribery & code of conduct attestations</li>
                    <li class="flex items-center gap-2">✓ Cryptographic audit trail & data locking (SHA-256)</li>
                    <li class="flex items-center gap-2">✓ External Assurance data room (ISAE 3000 / 3410)</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Interactive ROI & Carbon Abatement Calculator (Alpine.js) -->
<section class="py-16 bg-slate-900 text-white" x-data="{
    revenueMil: 50,
    reportingHours: 480,
    consultantCost: 35000,
    get hoursSaved() { return Math.round(this.reportingHours * 0.82); },
    get costSaved() { return Math.round(this.consultantCost * 0.70 + (this.hoursSaved * 45)); }
}">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Instant Cost & Time Benefit</h2>
            <p class="text-3xl font-black text-white mt-1">Interactive Enterprise ROI Estimator</p>
        </div>

        <div class="bg-slate-800 rounded-2xl p-8 border border-slate-700 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Current Annual Reporting Hours Spent:</label>
                    <input type="range" min="100" max="1500" step="20" x-model="reportingHours" class="w-full accent-emerald-500">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>100 hrs</span>
                        <span class="text-emerald-400 font-bold" x-text="reportingHours + ' Hours / Year'"></span>
                        <span>1,500 hrs</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">External Consultant / Auditor Fees ($):</label>
                    <input type="range" min="10000" max="120000" step="5000" x-model="consultantCost" class="w-full accent-emerald-500">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>$10,000</span>
                        <span class="text-emerald-400 font-bold" x-text="'$' + Number(consultantCost).toLocaleString()"></span>
                        <span>$120,000</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/80 rounded-xl p-6 border border-emerald-500/30 text-center">
                <div class="text-xs uppercase font-bold text-slate-400 mb-1">Estimated Annual Savings</div>
                <div class="text-4xl font-black text-emerald-400" x-text="'$' + costSaved.toLocaleString()"></div>
                <div class="text-xs text-slate-400 mt-1">82% reduction in staff data collection overhead</div>

                <div class="mt-6 pt-6 border-t border-slate-800 grid grid-cols-2 gap-4 text-left">
                    <div>
                        <div class="text-xs text-slate-400">Hours Eliminated</div>
                        <div class="text-xl font-bold text-white" x-text="hoursSaved + ' hrs'"></div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">Report Turnaround</div>
                        <div class="text-xl font-bold text-emerald-400">&lt; 48 Hours</div>
                    </div>
                </div>

                <a href="{{ route('demo.center') }}" class="block w-full mt-6 py-2.5 bg-emerald-500 text-slate-950 font-bold rounded-lg text-sm hover:bg-emerald-400 transition">
                    Test in Live Demo Environment
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-20 bg-emerald-600 text-white text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-black">Ready to Automate Your Corporate Sustainability Disclosures?</h2>
        <p class="mt-4 text-emerald-100 text-base max-w-2xl mx-auto">
            Choose an industry preset (Manufacturing, Banking, Textiles, Real Estate, Technology) and spin up a fully isolated, production-grade ESG software instance immediately.
        </p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('demo.center') }}" class="px-8 py-3.5 bg-white text-emerald-900 font-extrabold rounded-xl shadow-md hover:bg-emerald-50 transition">
                Open Demo Center &rarr;
            </a>
            <a href="{{ route('pricing') }}" class="px-8 py-3.5 bg-emerald-800 text-white border border-emerald-700 font-bold rounded-xl hover:bg-emerald-900 transition">
                View Commercial Plans
            </a>
        </div>
    </div>
</section>
@endsection

