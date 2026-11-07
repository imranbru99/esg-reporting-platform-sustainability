@extends('layouts.public')

@section('title', 'Supplier ESG Portal & Questionnaire Submission')

@section('content')
<div class="py-12 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-3xl font-black">Supplier ESG & Scope 3 Portal</h1>
        <p class="text-slate-300 text-sm mt-2">Submit verification questionnaires, upload environmental certifications, and track compliance ratings.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-12">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
        <form action="{{ route('portal.supplier.submit') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Supplier Account Code</label>
                <input type="text" name="supplier_code" value="{{ $code ?? old('supplier_code') }}" placeholder="e.g. SUP-981" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500" required>
            </div>

            @if($supplier)
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-1">
                    <div><strong>Supplier:</strong> {{ $supplier->name }} ({{ $supplier->tier }})</div>
                    <div><strong>Country:</strong> {{ $supplier->country_code }}</div>
                    <div><strong>Current ESG Risk Rating:</strong> <span class="capitalize text-emerald-700 font-bold">{{ $supplier->esg_risk_rating }}</span></div>
                </div>
            @else
                <input type="hidden" name="supplier_id" value="1">
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Questionnaire / Assessment Standard</label>
                <select name="questionnaire_name" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm">
                    <option value="Higg Index Facility Environmental Module (FEM 4.0)">Higg Index Facility Environmental Module (FEM 4.0)</option>
                    <option value="Sedex SMETA 4-Pillar Ethical Audit">Sedex SMETA 4-Pillar Ethical Audit</option>
                    <option value="ZDHC Wastewater & Chemical Discharge Compliance">ZDHC Wastewater & Chemical Discharge Compliance</option>
                    <option value="Supplier Code of Conduct Self-Assessment">Supplier Code of Conduct Self-Assessment</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Self-Assessment Verified Score (%)</label>
                <input type="number" step="0.1" min="0" max="100" name="score_percentage" value="85.5" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm" required>
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition">
                Submit Assessment Disclosures &rarr;
            </button>
        </form>
    </div>
</div>
@endsection

