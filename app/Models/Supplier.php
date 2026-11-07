<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'portal_access_enabled' => 'boolean',
        'is_onboarded' => 'boolean',
    ];

    public function assessments()
    {
        return $this->hasMany(SupplierAssessment::class);
    }

    public function scope3Requests()
    {
        return $this->hasMany(SupplierScope3Request::class);
    }
}
