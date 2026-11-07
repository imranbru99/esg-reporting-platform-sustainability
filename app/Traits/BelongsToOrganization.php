<?php

namespace App\Traits;

use App\Models\Organization;
use App\Scopes\OrganizationScope;
use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    public static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope(new OrganizationScope);

        static::creating(function ($model) {
            if (empty($model->organization_id) && $tenantId = TenantContext::getTenantId()) {
                $model->organization_id = $tenantId;
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
