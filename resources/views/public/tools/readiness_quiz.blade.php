@extends('layouts.public')

@section('title', 'Free ESG & Sustainability Readiness Quiz')

@section('content')
<div class="py-12 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-3xl font-black">Free ESG Readiness Quiz</h1>
        <p class="text-slate-300 text-sm mt-2">Answer 10 rapid compliance questions to evaluate your corporate readiness for GRI, CSRD, and ISSB audits.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-12">
    @if(session('quizResult'))
        @php $res = session('quizResult'); @endphp
        <div class="mb-8 p-6 bg-emerald-50 border border-emerald-300 rounded-2xl text-center">
            <div class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-1">Your ESG Maturity Score</div>
            <div class="text-5xl font-black text-emerald-700 my-2">{{ $res['score'] }}%</div>
            <div class="text-base font-bold text-slate-800 mb-2">{{ $res['grade'] }}</div>
            <p class="text-xs text-slate-600 max-w-md mx-auto">
                You currently satisfy {{ $res['completed'] }} out of 10 core compliance checkpoints. {{ $res['missing'] }} gaps must be addressed prior to formal assurance sign-off.
            </p>
            <div class="mt-4">
                <a href="{{ route('demo.center') }}" class="inline-block px-6 py-2 bg-emerald-600 text-white font-bold text-xs rounded-lg hover:bg-emerald-700">
                    Fix Remaining Gaps in Demo Sandbox &rarr;
                </a>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
        <form action="{{ route('tools.quiz.submit') }}" method="POST" class="space-y-6">
            @csrf
            @php
                $questions = [
                    'Do you calculate verified Scope 1 direct greenhouse gas emissions annually?',
                    'Do you report Scope 2 electricity emissions using both location-based and market-based methods?',
                    'Have you conducted a formal Double Materiality Assessment (Financial + Impact materiality)?',
                    'Do you collect primary Scope 3 ESG questionnaires from Tier-1 and Tier-2 suppliers?',
                    'Are your utility bills and fuel receipts verified with cryptographic audit trails?',
                    'Do you integrate turnstile attendance data for workforce hours, overtime and safety compliance?',
                    'Does your Board of Directors have an explicit sustainability oversight committee?',
                    'Do you have a secure, confidential whistleblower hotline with encrypted case intake?',
                    'Are your corporate ESG disclosures mapped to recognized frameworks (GRI, ISSB, CSRD)?',
                    'Has an independent external auditor ever provided limited or reasonable assurance on your ESG metrics?',
                ];
            @endphp

            @foreach($questions as $idx => $q)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <span class="text-xs font-semibold text-slate-800">{{ $idx + 1 }}. {{ $q }}</span>
                    <div class="flex items-center gap-4 text-xs font-bold">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="answers[{{ $idx }}]" value="yes" checked class="accent-emerald-600">
                            <span>Yes</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="answers[{{ $idx }}]" value="no" class="accent-emerald-600">
                            <span>No</span>
                        </label>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition">
                Submit & Calculate My ESG Readiness Score &rarr;
            </button>
        </form>
    </div>
</div>
@endsection

