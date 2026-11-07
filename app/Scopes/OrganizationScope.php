<?php

namespace App\Scopes;

use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrganizationScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = TenantContext::getTenant();

        if ($tenant) {
            $builder->where($model->getTable().'.organization_id', $tenant->id);
        }
    }
}
