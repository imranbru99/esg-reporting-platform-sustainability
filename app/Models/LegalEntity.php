<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LegalEntity extends Model
{
    use BelongsToOrganization, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'ownership_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
