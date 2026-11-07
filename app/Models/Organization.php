<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Organization extends Model
{
    use HasFactory, HasUniversalSlug, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'settings' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function legalEntities(): HasMany
    {
        return $this->hasMany(LegalEntity::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function businessUnits(): HasMany
    {
        return $this->hasMany(BusinessUnit::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function organizationModules(): HasMany
    {
        return $this->hasMany(OrganizationModule::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function metricEntries(): HasMany
    {
        return $this->hasMany(MetricEntry::class);
    }

    public function isModuleEnabled(string $moduleSlug): bool
    {
        $module = $this->organizationModules()
            ->where('module_slug', $moduleSlug)
            ->first();

        return $module ? $module->is_enabled : false;
    }
}
