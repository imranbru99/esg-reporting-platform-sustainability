@extends('layouts.public')

@section('title', 'Platform Features & 75 Toggleable Modules')

@section('content')
<div class="py-16 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl sm:text-5xl font-black">75 Enterprise ESG Modules</h1>
        <p class="mt-4 text-slate-300 text-base max-w-2xl mx-auto">
            Every feature is an independent, toggleable module. Enable only what your organization needs.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    @foreach($modulesByCategory as $category => $modules)
        <div class="mb-14">
            <h2 class="text-2xl font-black text-slate-900 mb-6 pb-2 border-b border-slate-200 capitalize">
                {{ str_replace('_', ' ', $category) }} Domain
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($modules as $mod)
                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:border-emerald-500 transition">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-mono font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                                {{ $mod->slug }}
                            </span>
                            <span class="text-xs text-slate-400">v{{ $mod->version ?? '1.0' }}</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">{{ $mod->name }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $mod->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection

