@extends('layouts.public')

@section('title', 'Role-Based Solutions')

@section('content')
<div class="py-16 bg-slate-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl sm:text-5xl font-black">Solutions by Stakeholder Role</h1>
        <p class="mt-4 text-slate-300 text-base max-w-2xl mx-auto">
            Granular interfaces and custom dashboards designed for every persona across the ESG lifecycle.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-wrap gap-3 justify-center mb-12">
        @foreach($rolesList as $key => $info)
            <a href="{{ route('roles', $key) }}" class="px-5 py-2 rounded-full text-xs font-bold transition {{ $currentRole === $key ? 'bg-emerald-600 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                {{ $info['title'] }}
            </a>
        @endforeach
    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 border border-slate-200 shadow-sm text-center">
        <h2 class="text-2xl font-black text-slate-900 mb-4">{{ $rolesList[$currentRole]['title'] }}</h2>
        <p class="text-slate-600 text-base leading-relaxed mb-8">{{ $rolesList[$currentRole]['desc'] }}</p>

        <a href="{{ route('demo.center') }}" class="inline-block px-8 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition">
            Test Drive This Role in Demo Center &rarr;
        </a>
    </div>
</div>
@endsection

