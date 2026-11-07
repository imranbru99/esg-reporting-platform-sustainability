@extends('layouts.public')

@section('title', 'Investor & ESG Disclosures Portal')

@section('content')
<div class="py-12 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-black">Investor Sustainability Disclosures Portal</h1>
        <p class="text-slate-300 text-sm mt-2">Access assured ESG reports, verified Scope 1-3 emissions inventories, and green bond compliance data.</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-12">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Published Sustainability Reports</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($publishedReports as $rep)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                            {{ strtoupper($rep->primary_framework) }}
                        </span>
                        <span class="text-xs text-slate-400">Period: {{ $rep->reporting_period }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $rep->title }}</h3>
                    <div class="text-xs text-slate-500 mb-4">{{ $rep->organization->name }}</div>

                    <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg text-xs space-y-1 font-mono text-slate-600">
                        <div><strong>Integrity Hash:</strong> {{ substr($rep->sha256_hash ?? 'N/A', 0, 16) }}...</div>
                        <div><strong>Language:</strong> {{ strtoupper($rep->language) }}</div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-xs text-emerald-600 font-semibold">✓ Independent Assured</span>
                    <a href="/admin/report-projects" class="text-xs font-bold bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                        View Report &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 bg-white rounded-xl border border-slate-200 text-slate-500 text-sm">
                No published sustainability reports available yet. Explore the live demo center to generate and publish reports.
            </div>
        @endforelse
    </div>
</div>
@endsection

