@extends('layouts.public')

@section('title', 'Auditor Data Room - Independent Assurance Workspace')

@section('content')
<div class="py-12 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <div class="inline-block bg-emerald-800 text-emerald-200 text-xs px-3 py-1 rounded-full uppercase font-bold mb-3">
            Secure Cryptographic Access Token: {{ substr($token, 0, 8) }}...
        </div>
        <h1 class="text-3xl font-black">External Assurance & Auditor Data Room</h1>
        <p class="text-slate-300 text-sm mt-2">ISAE 3000 / ISAE 3410 independent audit review workspace with sampling workpapers.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $engagement ? $engagement->title : 'FY2024 Independent Sustainability Assurance' }}</h2>
                <div class="text-xs text-slate-500 mt-1">Audit Firm: {{ $engagement ? $engagement->audit_firm : 'KPMG Global Assurance' }} | Standard: {{ $engagement ? $engagement->standard : 'ISAE 3000' }}</div>
            </div>
            <span class="bg-emerald-100 text-emerald-800 font-bold text-xs px-3 py-1 rounded-full uppercase">
                {{ $engagement ? $engagement->status : 'In Fieldwork' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-xs">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="text-slate-500">Assurance Level</div>
                <div class="text-base font-bold text-slate-900 mt-1">Limited Assurance</div>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="text-slate-500">Evidence Vault Status</div>
                <div class="text-base font-bold text-emerald-700 mt-1">100% SHA-256 Hashed</div>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="text-slate-500">Data Room Access</div>
                <div class="text-base font-bold text-slate-900 mt-1">Read-Only Enforced</div>
            </div>
        </div>

        <h3 class="font-bold text-slate-900 text-sm mb-3">Assurance Audit Workpapers Sample</h3>
        <div class="border border-slate-200 rounded-xl overflow-hidden text-xs">
            <table class="min-w-full divide-y divide-slate-200 text-left">
                <thead class="bg-slate-50 text-slate-600 font-bold">
                    <tr>
                        <th class="px-4 py-3">Disclosure</th>
                        <th class="px-4 py-3">Audit Procedure</th>
                        <th class="px-4 py-3">Finding</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <tr>
                        <td class="px-4 py-3 font-mono font-bold text-emerald-700">GRI 305-1</td>
                        <td class="px-4 py-3">Sample tested natural gas meter logs against supplier utility billing statements.</td>
                        <td class="px-4 py-3"><span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-bold">No Exception</span></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono font-bold text-emerald-700">GRI 403-9</td>
                        <td class="px-4 py-3">Reconciled safety incident records with factory biometric turnstile attendance logs.</td>
                        <td class="px-4 py-3"><span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-bold">No Exception</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

