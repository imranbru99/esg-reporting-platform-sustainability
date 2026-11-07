<?php

namespace App\Filament\Pages;

use App\Models\Organization;
use App\Models\OrganizationModule;
use App\Models\Preset;
use App\Services\Modules\ModuleRegistry;
use App\Services\Tenancy\TenantContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class ModuleManager extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-plus';

    protected static \UnitEnum|string|null $navigationGroup = 'Platform & Tenancy';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Module & Preset Manager';

    protected string $view = 'filament.pages.module-manager';

    public ?int $selectedOrganizationId = null;

    public ?string $selectedPreset = null;

    public function mount(): void
    {
        $tenant = TenantContext::getTenant() ?? Organization::first();
        $this->selectedOrganizationId = $tenant?->id;
    }

    public function getOrganization(): ?Organization
    {
        return $this->selectedOrganizationId ? Organization::find($this->selectedOrganizationId) : null;
    }

    public function getCategoriesProperty(): Collection
    {
        return ModuleRegistry::byCategory();
    }

    public function getPresetsProperty(): Collection
    {
        return Preset::orderBy('sort_order')->get();
    }

    public function isModuleEnabled(string $slug): bool
    {
        $org = $this->getOrganization();
        if (! $org) {
            return false;
        }

        $module = ModuleRegistry::find($slug);
        if ($module && $module->is_core) {
            return true;
        }

        $orgModule = OrganizationModule::where('organization_id', $org->id)
            ->where('module_slug', $slug)
            ->first();

        return $orgModule ? $orgModule->is_enabled : (bool) $module?->default_enabled;
    }

    public function toggleModule(string $slug): void
    {
        $org = $this->getOrganization();
        if (! $org) {
            Notification::make()->title('No Organization selected')->danger()->send();

            return;
        }

        $currentStatus = $this->isModuleEnabled($slug);
        $newStatus = ! $currentStatus;

        try {
            ModuleRegistry::toggle($slug, $org, $newStatus);

            Notification::make()
                ->title($newStatus ? "Module [{$slug}] enabled successfully." : "Module [{$slug}] disabled.")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Action Denied')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function applyPreset(): void
    {
        $org = $this->getOrganization();
        if (! $org || ! $this->selectedPreset) {
            Notification::make()->title('Select a preset first')->warning()->send();

            return;
        }

        $preset = Preset::where('slug', $this->selectedPreset)->first();
        if (! $preset) {
            Notification::make()->title('Invalid preset')->danger()->send();

            return;
        }

        $targetSlugs = $preset->included_modules ?? [];

        // Disable modules not in preset (except core)
        foreach (ModuleRegistry::all() as $mod) {
            if ($mod->is_core) {
                continue;
            }

            $shouldEnable = in_array($mod->slug, $targetSlugs, true);
            OrganizationModule::updateOrCreate(
                ['organization_id' => $org->id, 'module_slug' => $mod->slug],
                [
                    'is_enabled' => $shouldEnable,
                    'enabled_at' => $shouldEnable ? now() : null,
                    'disabled_at' => $shouldEnable ? null : now(),
                ]
            );
        }

        ModuleRegistry::toggle('tenant_management', $org, true); // refresh cache
        Notification::make()
            ->title("Applied [{$preset->name}] preset to {$org->name}!")
            ->body('Enabled '.count($targetSlugs)." modules tailored for {$preset->industry}.")
            ->success()
            ->send();
    }
}
