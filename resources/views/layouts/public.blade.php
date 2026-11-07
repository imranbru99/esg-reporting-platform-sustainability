<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ESG Reporting Platform - Auto-Generated Sustainability Reports')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
    <!-- Top Demo Announcement Bar -->
    <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-900 text-white text-xs py-2 px-4 text-center font-medium shadow-inner flex justify-center items-center gap-3">
        <span>🚀 Live Demo Environment Active: 22 Pre-Configured Enterprise Roles Ready</span>
        <a href="{{ route('demo.center') }}" class="underline font-bold hover:text-emerald-200">Open Live Demo Center &rarr;</a>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-black text-xl shadow-md">
                            🌿
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">ESG<span class="text-emerald-600">Platform</span></span>
                    </a>
                </div>

                <nav class="hidden md:flex items-center space-x-6 text-sm font-semibold text-slate-600">
                    <a href="{{ route('features') }}" class="hover:text-emerald-600 transition">Features</a>
                    <a href="{{ route('frameworks') }}" class="hover:text-emerald-600 transition">Frameworks</a>
                    <a href="{{ route('industries') }}" class="hover:text-emerald-600 transition">Industries</a>
                    <a href="{{ route('modules.explorer') }}" class="hover:text-emerald-600 transition">Modules & Pricing</a>
                    <a href="{{ route('tools.carbon') }}" class="text-emerald-600 hover:text-emerald-700 font-bold transition">Free Tools</a>
                    <a href="{{ route('demo.center') }}" class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-full hover:bg-emerald-100 transition">Demo Center</a>
                    <a href="/admin" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-bold shadow-sm hover:bg-emerald-700 transition">Sign In</a>
                </nav>

                <div class="md:hidden flex items-center">
                    <button @click="mobileMenu = !mobileMenu" class="text-slate-600 hover:text-slate-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" x-cloak class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-4 space-y-2 text-sm font-semibold">
            <a href="{{ route('features') }}" class="block py-1 hover:text-emerald-600">Features</a>
            <a href="{{ route('frameworks') }}" class="block py-1 hover:text-emerald-600">Frameworks</a>
            <a href="{{ route('industries') }}" class="block py-1 hover:text-emerald-600">Industries</a>
            <a href="{{ route('modules.explorer') }}" class="block py-1 hover:text-emerald-600">Modules & Pricing</a>
            <a href="{{ route('tools.carbon') }}" class="block py-1 text-emerald-600 font-bold">Free Tools</a>
            <a href="{{ route('demo.center') }}" class="block py-1 text-emerald-700">Demo Center</a>
            <a href="/admin" class="block w-full text-center bg-emerald-600 text-white py-2 rounded-lg font-bold mt-2">Sign In</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-8">
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xl">🌿</span>
                        <span class="font-bold text-white text-lg">ESG Reporting Platform</span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                        World-class end-to-end sustainability reporting software. Automated data collection, Scope 1-3 carbon accounting, AI report drafting with zero hallucination, and cryptographic assurance workspaces.
                    </p>
                    <div class="mt-4 text-xs text-slate-500">
                        Architected & Developed by <a href="https://imrandev.bd" target="_blank" class="text-emerald-400 font-semibold hover:underline">Imran Ahmed</a> (me@imrandev.bd)
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-3">Product</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('features') }}" class="hover:text-white">All 75 Modules</a></li>
                        <li><a href="{{ route('modules.explorer') }}" class="hover:text-white">Module Explorer</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white">Pricing Plans</a></li>
                        <li><a href="{{ route('demo.center') }}" class="hover:text-white">Live Demo Center</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-3">Public Portals</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('portal.supplier') }}" class="hover:text-white">Supplier Portal</a></li>
                        <li><a href="{{ route('portal.investor') }}" class="hover:text-white">Investor Portal</a></li>
                        <li><a href="{{ route('portal.whistleblower') }}" class="hover:text-white">Whistleblower Hotline</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-3">Free Tools</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('tools.carbon') }}" class="hover:text-white">Carbon Calculator</a></li>
                        <li><a href="{{ route('tools.quiz') }}" class="hover:text-white">ESG Readiness Quiz</a></li>
                        <li><a href="{{ route('tools.materiality') }}" class="hover:text-white">Materiality Check</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} ESG Reporting Platform. All rights reserved. FastPanel & VPS Optimized.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="https://imrandev.bd" target="_blank" class="hover:text-white">Official Website</a>
                    <a href="mailto:me@imrandev.bd" class="hover:text-white">Contact Developer</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

