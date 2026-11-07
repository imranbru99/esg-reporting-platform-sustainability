<x-filament-panels::page>
    <div class="space-y-6">
        <!-- AI Hub Settings Card -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 pb-4 mb-5">
                <div class="p-2.5 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                    <x-heroicon-o-cpu-chip class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">AI Engine Configuration (laravel-ai-hub)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Dynamic provider switching, model routing, safety guardrails and spending budget.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AI Provider</label>
                    <select wire:model.defer="aiProvider" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="gemini">Google Gemini (Recommended for ESG synthesis)</option>
                        <option value="openai">OpenAI (GPT-4o / GPT-5)</option>
                        <option value="claude">Anthropic Claude (Extended Thinking)</option>
                        <option value="deepseek">DeepSeek (V3 / R1 Reasoner)</option>
                        <option value="ollama">Ollama (Local Private On-Prem LLM)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model Name</label>
                    <input type="text" wire:model.defer="aiModel" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="e.g. gemini-2.5-flash, gpt-4o, claude-3-7-sonnet" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Provider API Key</label>
                    <input type="password" wire:model.defer="aiApiKey" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="sk-..." />
                    <p class="text-[11px] text-gray-500 mt-1">Leaves existing key unchanged if kept blank.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monthly Budget Cap (USD)</label>
                    <input type="number" wire:model.defer="aiMonthlyBudgetUsd" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                </div>
            </div>
        </div>

        <!-- Drutogoti Performance Optimization Card -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 pb-4 mb-5">
                <div class="p-2.5 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <x-heroicon-o-bolt class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Drutogoti Speed & Profiling Engine</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Zero-overhead N+1 query detection, slow query telemetry, and response acceleration.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profiler State</label>
                    <select wire:model.defer="drutogotiEnabled" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="1">Enabled (Active Profiling & Optimization)</option>
                        <option value="0">Disabled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slow Query Threshold (ms)</label>
                    <input type="number" wire:model.defer="slowQueryThresholdMs" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telemetry Storage Driver</label>
                    <select wire:model.defer="drutogotiStorageDriver" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="queue">Queue (Redis / Horizon asynchronous)</option>
                        <option value="sync">Sync (Direct write)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ESG Reporting Defaults -->
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 pb-4 mb-5">
                <div class="p-2.5 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400">
                    <x-heroicon-o-calendar-days class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">ESG Reporting Defaults</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Fiscal year boundaries, base year recalculation triggers, and default GHG accounting rules.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fiscal Year Start Month</label>
                    <select wire:model.defer="fiscalYearStartMonth" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="1">January (Calendar Year)</option>
                        <option value="4">April (UK / India)</option>
                        <option value="7">July (Bangladesh / Australia)</option>
                        <option value="10">October (US Federal)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Boundary</label>
                    <select wire:model.defer="defaultReportingBoundary" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                        <option value="operational_control">Operational Control</option>
                        <option value="financial_control">Financial Control</option>
                        <option value="equity_share">Equity Share</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Baseline Year</label>
                    <input type="text" wire:model.defer="baseYear" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button wire:click="saveSettings" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-2.5 rounded-lg text-sm shadow transition">
                Save Platform Settings
            </button>
        </div>
    </div>
</x-filament-panels::page>

