@extends('layouts.public')

@section('title', 'Interactive Modules Explorer & Pricing Calculator')

@section('content')
<div class="py-16 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl sm:text-5xl font-black">Interactive Modules Explorer</h1>
        <p class="mt-4 text-slate-300 text-base max-w-2xl mx-auto">
            Select modules to build your tailored sustainability stack and see estimated pricing in real-time.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="{
    activeCategory: 'all',
    selectedModules: {{ json_encode($modules->take(12)->pluck('slug')->all()) }},
    basePrice: 199,
    moduleAddonPrice: 25,
    get totalPrice() {
        return this.basePrice + (Math.max(0, this.selectedModules.length - 8) * this.moduleAddonPrice);
    },
    toggleModule(slug) {
        if (this.selectedModules.includes(slug)) {
            this.selectedModules = this.selectedModules.filter(s => s !== slug);
        } else {
            this.selectedModules.push(slug);
        }
    }
}">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Module Grid -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Filter Categories -->
            <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-200">
                <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">All Modules ({{ $modules->count() }})</button>
                <button @click="activeCategory = 'environmental'" :class="activeCategory === 'environmental' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">Environmental</button>
                <button @click="activeCategory = 'social'" :class="activeCategory === 'social' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">Social</button>
                <button @click="activeCategory = 'governance'" :class="activeCategory === 'governance' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">Governance</button>
                <button @click="activeCategory = 'reporting'" :class="activeCategory === 'reporting' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition">Reporting & AI</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($modules as $m)
                    <div x-show="activeCategory === 'all' || activeCategory === '{{ $m->category }}'" 
                         @click="toggleModule('{{ $m->slug }}')"
                         :class="selectedModules.includes('{{ $m->slug }}') ? 'border-emerald-500 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-slate-200 bg-white'"
                         class="p-4 rounded-xl border cursor-pointer transition select-none hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">{{ $m->name }}</span>
                            <span x-text="selectedModules.includes('{{ $m->slug }}') ? '✓ Active' : '+ Enable'"
                                  :class="selectedModules.includes('{{ $m->slug }}') ? 'text-emerald-700 font-bold' : 'text-slate-400'"
                                  class="text-xs"></span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $m->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sticky Pricing & Estimation Card -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-white rounded-2xl p-6 border border-slate-200 shadow-lg">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Live Stack Estimate</h3>
                <div class="flex items-baseline gap-1 mb-4">
                    <span class="text-4xl font-black text-slate-900" x-text="'$' + totalPrice"></span>
                    <span class="text-xs text-slate-500">/ month billed annually</span>
                </div>

                <div class="space-y-3 py-4 border-t border-b border-slate-200 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Selected Modules:</span>
                        <strong class="text-slate-900" x-text="selectedModules.length + ' modules'"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Base Platform (Includes 8 Core Modules):</span>
                        <span>$199 / mo</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Additional Selected Modules:</span>
                        <span x-text="'$' + (Math.max(0, selectedModules.length - 8) * moduleAddonPrice) + ' / mo'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>AI Report Drafting Engine:</span>
                        <span class="text-emerald-600 font-bold">Included</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Unicode PDF Universal Export:</span>
                        <span class="text-emerald-600 font-bold">Included</span>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <a href="{{ route('demo.center') }}" class="block text-center w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition">
                        Deploy Stack in Demo Sandbox
                    </a>
                    <a href="{{ route('pricing') }}" class="block text-center w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                        View Fixed Annual Enterprise Plans
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

