<?php

namespace App\Filament\Resources;

use App\Services\Modules\ModuleGate;
use Filament\Resources\Resource;

abstract class BaseModuleResource extends Resource
{
    /**
     * The slug of the module that controls this resource.
     * If null, the resource is always accessible (e.g. core resources).
     */
    protected static ?string $moduleSlug = null;

    public static function shouldRegisterNavigation(): bool
    {
        if (static::$moduleSlug && ! ModuleGate::isEnabled(static::$moduleSlug)) {
            return false;
        }

        return parent::shouldRegisterNavigation();
    }

    public static function canViewAny(): bool
    {
        if (static::$moduleSlug && ! ModuleGate::isEnabled(static::$moduleSlug)) {
            return false;
        }

        return true;
    }
}
