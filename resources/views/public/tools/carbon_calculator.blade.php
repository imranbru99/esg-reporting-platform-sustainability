@extends('layouts.public')

@section('title', 'Free Quick Carbon Footprint Calculator')

@section('content')
<div class="py-12 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-3xl font-black">Free Quick Carbon Footprint Calculator</h1>
        <p class="text-slate-300 text-sm mt-2">Estimate direct Scope 1 and Scope 2 emissions based on fuel and electricity consumption.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-12">
    @if(session('carbonResult'))
        @php $res = session('carbonResult'); @endphp
        <div class="mb-8 p-6 bg-emerald-50 border border-emerald-300 rounded-2xl">
            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-800 mb-4">Calculated GHG Footprint</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="p-4 bg-white rounded-xl border border-emerald-200">
                    <div class="text-xs text-slate-500">Scope 1 (Direct)</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $res['scope1'] }} <span class="text-xs font-normal">tCO2e</span></div>
                </div>
                <div class="p-4 bg-white rounded-xl border border-emerald-200">
                    <div class="text-xs text-slate-500">Scope 2 (Electricity)</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $res['scope2'] }} <span class="text-xs font-normal">tCO2e</span></div>
                </div>
                <div class="p-4 bg-emerald-600 text-white rounded-xl">
                    <div class="text-xs text-emerald-100">Total Emissions</div>
                    <div class="text-2xl font-black mt-1">{{ $res['total'] }} <span class="text-xs font-normal">tCO2e</span></div>
                </div>
            </div>
            <div class="mt-4 text-xs text-emerald-800 text-center font-medium">
                🌲 Equivalent to planting approximately <strong>{{ number_format($res['offset_trees']) }} trees</strong> to neutralize.
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
        <form action="{{ route('tools.carbon.calculate') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Annual Diesel Consumption (Liters)</label>
                <input type="number" step="10" name="diesel_liters" value="{{ old('diesel_liters', 12500) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Annual Natural Gas Consumption (Cubic Meters - m³)</label>
                <input type="number" step="10" name="natural_gas_m3" value="{{ old('natural_gas_m3', 25000) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Annual Grid Electricity Usage (Kilowatt Hours - kWh)</label>
                <input type="number" step="100" name="electricity_kwh" value="{{ old('electricity_kwh', 180000) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-sm" required>
            </div>

            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition">
                Calculate Scope 1 & 2 Emissions &rarr;
            </button>
        </form>
    </div>
</div>
@endsection

