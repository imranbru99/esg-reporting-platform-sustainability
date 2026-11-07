<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'array',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting ? ($setting->value['value'] ?? $setting->value) : $default;
    }

    public static function set(string $key, mixed $value, ?string $description = null): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? $value : ['value' => $value], 'description' => $description]
        );
    }
}
