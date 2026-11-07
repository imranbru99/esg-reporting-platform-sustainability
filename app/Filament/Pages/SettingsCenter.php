<?php

namespace App\Filament\Pages;

use App\Models\PlatformSetting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SettingsCenter extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static \UnitEnum|string|null $navigationGroup = 'Platform & Tenancy';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Settings Center';

    protected string $view = 'filament.pages.settings-center';

    // AI Configuration (laravel-ai-hub)
    public string $aiProvider = 'gemini';

    public string $aiModel = 'gemini-2.5-flash';

    public string $aiApiKey = '';

    public int $aiMonthlyBudgetUsd = 100;

    // Drutogoti Performance Optimization
    public bool $drutogotiEnabled = true;

    public int $slowQueryThresholdMs = 100;

    public string $drutogotiStorageDriver = 'queue';

    // ESG Reporting Defaults
    public int $fiscalYearStartMonth = 1;

    public string $defaultReportingBoundary = 'operational_control';

    public string $baseYear = '2023';

    public function mount(): void
    {
        $this->aiProvider = PlatformSetting::get('ai_provider', 'gemini');
        $this->aiModel = PlatformSetting::get('ai_model', 'gemini-2.5-flash');
        $this->aiApiKey = PlatformSetting::get('ai_api_key', '');
        $this->aiMonthlyBudgetUsd = (int) PlatformSetting::get('ai_monthly_budget_usd', 100);

        $this->drutogotiEnabled = (bool) PlatformSetting::get('drutogoti_enabled', true);
        $this->slowQueryThresholdMs = (int) PlatformSetting::get('slow_query_threshold_ms', 100);
        $this->drutogotiStorageDriver = PlatformSetting::get('drutogoti_storage_driver', 'queue');

        $this->fiscalYearStartMonth = (int) PlatformSetting::get('fiscal_year_start_month', 1);
        $this->defaultReportingBoundary = PlatformSetting::get('default_reporting_boundary', 'operational_control');
        $this->baseYear = PlatformSetting::get('base_year', '2023');
    }

    public function saveSettings(): void
    {
        PlatformSetting::set('ai_provider', $this->aiProvider, 'Default AI provider (Gemini, OpenAI, Claude)');
        PlatformSetting::set('ai_model', $this->aiModel, 'Selected AI model');
        if (! empty($this->aiApiKey)) {
            PlatformSetting::set('ai_api_key', $this->aiApiKey, 'Encrypted Provider API key');
        }
        PlatformSetting::set('ai_monthly_budget_usd', $this->aiMonthlyBudgetUsd, 'Monthly spending cap in USD');

        PlatformSetting::set('drutogoti_enabled', $this->drutogotiEnabled, 'Enable Drutogoti profiler');
        PlatformSetting::set('slow_query_threshold_ms', $this->slowQueryThresholdMs, 'Slow query threshold in ms');
        PlatformSetting::set('drutogoti_storage_driver', $this->drutogotiStorageDriver, 'Drutogoti telemetry driver');

        PlatformSetting::set('fiscal_year_start_month', $this->fiscalYearStartMonth, 'Month index (1-12) where fiscal year starts');
        PlatformSetting::set('default_reporting_boundary', $this->defaultReportingBoundary, 'Default GHG boundary');
        PlatformSetting::set('base_year', $this->baseYear, 'GHG Baseline emission recalculation base year');

        Notification::make()
            ->title('Platform & AI Settings Saved')
            ->body('All configurations successfully updated across services.')
            ->success()
            ->send();
    }
}
