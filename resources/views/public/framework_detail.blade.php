@extends('layouts.public')

@section('title', $framework->name . ' - Disclosures & Metrics')

@section('content')
<div class="py-12 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('frameworks') }}" class="text-xs text-emerald-400 font-semibold hover:underline">&larr; Back to all frameworks</a>
        <h1 class="text-3xl font-black mt-2">{{ $framework->name }}</h1>
        <p class="text-slate-300 text-sm mt-2 max-w-3xl">{{ $framework->description }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Disclosures & Required Datapoints ({{ $framework->disclosures->count() }})</h2>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase">
                <tr>
                    <th class="px-6 py-3">Code</th>
                    <th class="px-6 py-3">Disclosure Title</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Mandatory</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-700">
                @foreach($framework->disclosures as $d)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-mono font-bold text-emerald-700">{{ $d->code }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $d->title }}</td>
                        <td class="px-6 py-4 capitalize">{{ $d->category }}</td>
                        <td class="px-6 py-4 capitalize">{{ $d->requirement_type }}</td>
                        <td class="px-6 py-4">
                            @if($d->is_mandatory)
                                <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">Mandatory</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px]">Optional</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

