@extends('layouts.public')

@section('title', 'Pricing Plans - Transparent Commercial Options')

@section('content')
<div class="py-16 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl sm:text-5xl font-black">Simple, Predictable ESG Pricing</h1>
        <p class="mt-4 text-slate-300 text-base max-w-2xl mx-auto">
            Choose the package that fits your organization scale. All plans include automated calculations, full audit trail, and multi-user access.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white rounded-2xl p-6 border {{ $plan->slug === 'professional' ? 'border-emerald-500 shadow-xl ring-2 ring-emerald-500' : 'border-slate-200 shadow-sm' }} flex flex-col justify-between">
                <div>
                    @if($plan->slug === 'professional')
                        <div class="inline-block bg-emerald-500 text-slate-950 font-black text-[10px] uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-3">
                            Most Popular
                        </div>
                    @endif

                    <h3 class="text-xl font-black text-slate-900">{{ $plan->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-4">{{ $plan->description }}</p>

                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-3xl font-black text-slate-900">${{ number_format($plan->price_monthly / 100, 0) }}</span>
                        <span class="text-xs text-slate-500">/ mo</span>
                    </div>

                    <ul class="space-y-2.5 text-xs text-slate-600 border-t border-slate-100 pt-4">
                        <li class="flex items-center gap-2">✓ <strong>{{ $plan->max_users }}</strong> Active Users</li>
                        <li class="flex items-center gap-2">✓ <strong>{{ $plan->max_entities }}</strong> Legal Entities / Sites</li>
                        <li class="flex items-center gap-2">✓ <strong>{{ $plan->max_ai_credits_monthly }}</strong> AI Report Credits / mo</li>
                        <li class="flex items-center gap-2">✓ <strong>{{ $plan->max_reports_per_month }}</strong> PDF Reports / mo</li>
                        <li class="flex items-center gap-2">✓ Full Audit Trail & Evidence Vault</li>
                        <li class="flex items-center gap-2">✓ Unicode PDF Multilingual Export</li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ route('demo.center') }}" class="block text-center w-full py-2.5 {{ $plan->slug === 'professional' ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-slate-100 text-slate-800 hover:bg-slate-200' }} font-bold text-xs rounded-xl transition">
                        Select {{ $plan->name }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

