<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_core' => 'boolean',
        'default_enabled' => 'boolean',
        'dependencies' => 'array',
        'sort_order' => 'integer',
    ];

    public function organizationModules(): HasMany
    {
        return $this->hasMany(OrganizationModule::class, 'module_slug', 'slug');
    }

    public function branchModules(): HasMany
    {
        return $this->hasMany(BranchModule::class, 'module_slug', 'slug');
    }
}
