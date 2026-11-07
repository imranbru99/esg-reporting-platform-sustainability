<?php

namespace App\Services\Modules;

use App\Models\Organization;
use App\Models\OrganizationModule;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\Cache;

class ModuleGate
{
    /**
     * Check if a module is enabled for the current or given organization.
     */
    public static function isEnabled(string $slug, ?Organization $organization = null): bool
    {
        $org = $organization ?? TenantContext::getTenant();

        // If no organization context (e.g. platform visitor or super admin outside tenant view),
        // core modules and modules enabled by default are allowed.
        if (! $org) {
            $module = ModuleRegistry::find($slug);

            return $module ? ($module->is_core || $module->default_enabled) : false;
        }

        $enabledSlugs = Cache::remember("org_{$org->id}_enabled_modules", 1800, function () use ($org) {
            return OrganizationModule::where('organization_id', $org->id)
                ->where('is_enabled', true)
                ->pluck('module_slug')
                ->toArray();
        });

        // Core modules are always enabled
        $module = ModuleRegistry::find($slug);
        if ($module && $module->is_core) {
            return true;
        }

        return in_array($slug, $enabledSlugs, true);
    }

    /**
     * Check if a module is disabled.
     */
    public static function isDisabled(string $slug, ?Organization $organization = null): bool
    {
        return ! static::isEnabled($slug, $organization);
    }

    /**
     * Clear cached state for an organization.
     */
    public static function clearCache(int $organizationId): void
    {
        Cache::forget("org_{$organizationId}_enabled_modules");
    }
}
