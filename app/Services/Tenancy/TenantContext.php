<?php

namespace App\Services\Tenancy;

use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class TenantContext
{
    protected static ?Organization $currentTenant = null;

    public static function setTenant(?Organization $organization): void
    {
        static::$currentTenant = $organization;
    }

    public static function getTenant(): ?Organization
    {
        if (static::$currentTenant !== null) {
            return static::$currentTenant;
        }

        $user = Auth::user();
        if ($user && $user->organization_id) {
            static::$currentTenant = $user->organization;

            return static::$currentTenant;
        }

        return null;
    }

    public static function getTenantId(): ?int
    {
        return static::getTenant()?->id;
    }

    public static function clear(): void
    {
        static::$currentTenant = null;
    }
}
