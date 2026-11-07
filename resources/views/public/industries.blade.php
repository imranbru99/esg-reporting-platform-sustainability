@extends('layouts.public')

@section('title', 'Industry Presets & Tailored ESG Configurations')

@section('content')
<div class="py-16 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl sm:text-5xl font-black">12 Industry Presets</h1>
        <p class="mt-4 text-slate-300 text-base max-w-2xl mx-auto">
            One-click deployment tailored specifically for your operational reality.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar Navigation of Presets -->
        <div class="space-y-1">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 px-3">Industries</h3>
            @foreach($presets as $p)
                <a href="{{ route('industries', $p->key) }}" class="block px-4 py-2.5 rounded-lg text-sm font-semibold transition {{ ($selectedPreset && $selectedPreset->id === $p->id) ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' }}">
                    {{ $p->name }}
                </a>
            @endforeach
        </div>

        <!-- Preset Detail View -->
        <div class="md:col-span-3 bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
            @if($selectedPreset)
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-black text-slate-900">{{ $selectedPreset->name }} Solution</h2>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-600 px-3 py-1 rounded">
                        {{ count($selectedPreset->included_modules ?? []) }} Recommended Modules
                    </span>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed mb-8">{{ $selectedPreset->description }}</p>

                <h3 class="font-bold text-slate-900 text-sm mb-4">Pre-Configured Active Modules:</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                    @foreach($selectedPreset->included_modules ?? [] as $modSlug)
                        <div class="flex items-center gap-2 text-xs bg-emerald-50 text-emerald-900 border border-emerald-200 px-3 py-2 rounded-lg font-semibold">
                            <span>✓</span>
                            <span>{{ ucwords(str_replace('_', ' ', $modSlug)) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-6 border-t border-slate-200 flex justify-end">
                    <a href="{{ route('demo.center') }}" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg text-xs hover:bg-emerald-700 transition">
                        Deploy {{ $selectedPreset->name }} in Demo &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

