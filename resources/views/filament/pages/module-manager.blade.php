<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Preset Quick-Apply Banner -->
        <div class="bg-gradient-to-r from-emerald-900 to-teal-900 p-6 rounded-2xl text-white shadow-lg border border-emerald-700/50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <span>⚡ Industry Business Presets</span>
                        <span class="text-xs bg-emerald-500/30 text-emerald-300 px-2 py-0.5 rounded-full border border-emerald-400/30">1-Click Configuration</span>
                    </h2>
                    <p class="text-sm text-emerald-200 mt-1">
                        Instantly enable the exact ESG and operational modules required for your specific industry sector.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model.defer="selectedPreset" class="bg-emerald-950 border border-emerald-600 text-sm rounded-lg px-3 py-2 text-emerald-100 focus:ring-emerald-400">
                        <option value="">-- Choose Industry Preset --</option>
                        @foreach($this->presets as $preset)
                            <option value="{{ $preset->slug }}">{{ $preset->name }} ({{ $preset->industry }})</option>
                        @endforeach
                    </select>
                    <button wire:click="applyPreset" class="bg-emerald-500 hover:bg-emerald-400 text-emerald-950 font-bold px-4 py-2 rounded-lg text-sm shadow transition">
                        Apply Preset
                    </button>
                </div>
            </div>
        </div>

        <!-- Modules Grouped by Category -->
        @foreach($this->categories as $category => $modules)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white capitalize">
                            {{ str_replace('_', ' ', $category) }} Modules
                        </h3>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        {{ $modules->count() }} modules
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modules as $module)
                        @php
                            $isEnabled = $this->isModuleEnabled($module->slug);
                        @endphp
                        <div class="border rounded-xl p-4 transition-all {{ $isEnabled ? 'border-emerald-500/40 bg-emerald-50/20 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 opacity-75' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white">
                                            {{ $module->name }}
                                        </h4>
                                        @if($module->is_core)
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-600 dark:text-amber-400 px-1.5 py-0.5 rounded border border-amber-500/30">
                                                Core
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ $module->description }}
                                    </p>
                                </div>

                                <div>
                                    @if($module->is_core)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                                            Locked ON
                                        </span>
                                    @else
                                        <button wire:click="toggleModule('{{ $module->slug }}')"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $isEnabled ? 'bg-emerald-600' : 'bg-gray-300 dark:bg-gray-700' }}">
                                            <span class="sr-only">Toggle {{ $module->name }}</span>
                                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $isEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($module->dependencies))
                                <div class="mt-3 pt-2 border-t border-gray-100 dark:border-gray-800/80 flex items-center gap-1.5 flex-wrap">
                                    <span class="text-[10px] text-gray-400 font-medium">Requires:</span>
                                    @foreach($module->dependencies as $dep)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-mono">
                                            {{ $dep }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>

