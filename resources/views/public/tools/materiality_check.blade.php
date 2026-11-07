@extends('layouts.public')

@section('title', 'Free Materiality Quick Check by Industry')

@section('content')
<div class="py-12 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-3xl font-black">Free Materiality Quick Check</h1>
        <p class="text-slate-300 text-sm mt-2">Select your operating sector to reveal the top 5 mandatory material topics for your sustainability disclosures.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Industry Selector Tabs -->
    <div class="flex flex-wrap gap-2 justify-center mb-8">
        @foreach(array_keys($topicsByIndustry) as $ind)
            <a href="{{ route('tools.materiality', ['industry' => $ind]) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold capitalize transition {{ $industry === $ind ? 'bg-emerald-600 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                {{ $ind }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200">
            <div>
                <h2 class="text-xl font-black text-slate-900 capitalize">{{ $industry }} Sector Priorities</h2>
                <p class="text-xs text-slate-500 mt-1">Evaluated across Double Materiality (Impact on Society + Financial Materiality)</p>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 px-3 py-1 rounded-full">
                GRI & CSRD Aligned
            </span>
        </div>

        <div class="space-y-4 mb-8">
            @foreach($topics as $idx => $t)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center">
                            {{ $idx + 1 }}
                        </span>
                        <span class="text-sm font-bold text-slate-900">{{ $t }}</span>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded">
                        High Double Materiality
                    </span>
                </div>
            @endforeach
        </div>

        <div class="p-4 bg-slate-900 text-white rounded-xl flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
            <div>
                <div class="text-sm font-bold">Need a full interactive Double Materiality matrix?</div>
                <div class="text-xs text-slate-400 mt-0.5">Explore our matrix generator with stakeholder weighting and X/Y quadrant charts.</div>
            </div>
            <a href="{{ route('demo.center') }}" class="px-5 py-2.5 bg-emerald-500 text-slate-950 font-bold text-xs rounded-lg hover:bg-emerald-400 whitespace-nowrap">
                Open Matrix in Demo &rarr;
            </a>
        </div>
    </div>
</div>
@endsection

