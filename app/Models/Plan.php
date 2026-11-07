<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'included_modules' => 'array',
        'limits' => 'array',
        'monthly_price_cents' => 'integer',
        'annual_price_cents' => 'integer',
        'is_active' => 'boolean',
    ];

    public function includesModule(string $slug): bool
    {
        return in_array('*', $this->included_modules ?? []) || in_array($slug, $this->included_modules ?? []);
    }
}
