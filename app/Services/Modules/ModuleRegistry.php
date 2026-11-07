<?php

namespace App\Services\Modules;

use App\Models\Module;
use App\Models\Organization;
use App\Models\OrganizationModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ModuleRegistry
{
    /**
     * @var array<string, array<string, mixed>>
     */
    protected static array $manifests = [];

    /**
     * Register a module in memory or load from database/manifests.
     */
    public static function register(string $slug, array $metadata): void
    {
        static::$manifests[$slug] = $metadata;
    }

    /**
     * Get all registered module definitions.
     *
     * @return Collection<int, Module>
     */
    public static function all(): Collection
    {
        return Cache::remember('all_modules_catalog', 3600, function () {
            return Module::orderBy('sort_order')->orderBy('name')->get();
        });
    }

    /**
     * Get modules grouped by category.
     */
    public static function byCategory(): Collection
    {
        return static::all()->groupBy('category');
    }

    /**
     * Check if a module exists.
     */
    public static function exists(string $slug): bool
    {
        return static::all()->contains('slug', $slug);
    }

    /**
     * Find a module by slug.
     */
    public static function find(string $slug): ?Module
    {
        return static::all()->firstWhere('slug', $slug);
    }

    /**
     * Check whether disabling a module would break dependents for an organization.
     *
     * @return array<int, string> Array of dependent module names that require this module
     */
    public static function checkDependents(string $slug, Organization $organization): array
    {
        $enabledSlugs = OrganizationModule::where('organization_id', $organization->id)
            ->where('is_enabled', true)
            ->pluck('module_slug')
            ->toArray();

        $brokenDependents = [];

        foreach (static::all() as $module) {
            if ($module->slug === $slug || ! in_array($module->slug, $enabledSlugs)) {
                continue;
            }

            $deps = $module->dependencies ?? [];
            if (in_array($slug, $deps)) {
                $brokenDependents[] = $module->name;
            }
        }

        return $brokenDependents;
    }

    /**
     * Toggle a module for an organization with validation.
     *
     * @throws \Exception
     */
    public static function toggle(string $slug, Organization $organization, bool $enable): bool
    {
        $module = static::find($slug);
        if (! $module) {
            throw new \InvalidArgumentException("Module [{$slug}] does not exist.");
        }

        if (! $enable && $module->is_core) {
            throw new \DomainException("Core module [{$module->name}] cannot be disabled.");
        }

        if (! $enable) {
            $dependents = static::checkDependents($slug, $organization);
            if (! empty($dependents)) {
                throw new \DomainException("Cannot disable [{$module->name}] because the following active modules depend on it: ".implode(', ', $dependents));
            }
        } else {
            // Verify all dependencies are enabled
            $deps = $module->dependencies ?? [];
            foreach ($deps as $depSlug) {
                if (! ModuleGate::isEnabled($depSlug, $organization)) {
                    $depModule = static::find($depSlug);
                    $depName = $depModule ? $depModule->name : $depSlug;
                    throw new \DomainException("Cannot enable [{$module->name}] without enabling prerequisite module [{$depName}] first.");
                }
            }
        }

        OrganizationModule::updateOrCreate(
            ['organization_id' => $organization->id, 'module_slug' => $slug],
            [
                'is_enabled' => $enable,
                'enabled_at' => $enable ? now() : null,
                'disabled_at' => $enable ? null : now(),
            ]
        );

        Cache::forget("org_{$organization->id}_enabled_modules");

        return true;
    }
}
