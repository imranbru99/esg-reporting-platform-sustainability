@extends('layouts.public')

@section('title', 'Global ESG Frameworks & Standards Support')

@section('content')
<div class="py-16 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl sm:text-5xl font-black">Supported Global ESG Frameworks</h1>
        <p class="mt-4 text-slate-300 text-base max-w-2xl mx-auto">
            Report once, comply everywhere. Cross-framework metric mapping enables unified data collection across global disclosure standards.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($frameworks as $fw)
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                            {{ $fw->version ?? '2024' }}
                        </span>
                        <span class="text-xs text-slate-500 font-semibold">{{ $fw->disclosures_count }} Disclosures</span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $fw->name }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed mb-6">{{ $fw->description }}</p>
                </div>

                <a href="{{ route('frameworks.detail', $fw->slug) }}" class="block text-center w-full py-2 bg-slate-100 hover:bg-emerald-50 text-emerald-700 font-bold text-xs rounded-lg transition border border-slate-200">
                    View Disclosure Catalog &rarr;
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

