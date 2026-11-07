@extends('layouts.public')

@section('title', 'Whistleblower Ethics Hotline - Anonymous Intake')

@section('content')
<div class="py-12 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <div class="inline-block bg-red-900/60 border border-red-500/40 text-red-300 text-xs px-3 py-1 rounded-full uppercase font-bold mb-3">
            🔒 Zero Metadata Logging & Complete Anonymity Guaranteed
        </div>
        <h1 class="text-3xl font-black">Whistleblower & Ethics Hotline</h1>
        <p class="text-slate-300 text-sm mt-2">Submit confidential reports regarding environmental non-compliance, labor violations, safety incidents, or financial fraud.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-12">
    @if(session('token'))
        <div class="mb-8 p-6 bg-emerald-50 border-2 border-emerald-500 rounded-2xl">
            <h3 class="text-base font-black text-emerald-900 mb-2">Report Submitted Securely!</h3>
            <p class="text-xs text-emerald-700 mb-4">Please save your unique tracking token below. You will need it to check responses or provide additional follow-up evidence anonymously.</p>
            <div class="p-3 bg-white border border-emerald-300 rounded-xl font-mono text-lg font-black text-emerald-900 select-all">
                {{ session('token') }}
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
        <form action="{{ route('portal.whistleblower.submit') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Enterprise</label>
                <select name="organization_id" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm" required>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Category of Allegation</label>
                <select name="allegation_type" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm" required>
                    <option value="environmental_discharge">Illegal Wastewater or Hazardous Chemical Discharge</option>
                    <option value="forced_labor_overtime">Excessive Overtime, Wage Suppression or Labor Abuse</option>
                    <option value="safety_falsification">Falsification of Safety Logs or Concealed Work Accidents</option>
                    <option value="bribery_corruption">Bribery, Kickbacks or Corruption in Procurement</option>
                    <option value="data_tampering">Tampering with Carbon Scope 1/2 Utility Bills or Meters</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Incident Details (Names, Dates, Locations, Evidence)</label>
                <textarea name="details" rows="6" placeholder="Provide as much verifiable factual detail as possible. Do not include your own personal details if you wish to remain anonymous." class="w-full px-4 py-3 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500" required></textarea>
                <p class="text-[11px] text-slate-400 mt-1">All text will be encrypted at rest in accordance with ISO 37002 whistleblower guidelines.</p>
            </div>

            <button type="submit" class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition shadow-sm">
                Submit Encrypted Incident Report &rarr;
            </button>
        </form>
    </div>
</div>
@endsection

