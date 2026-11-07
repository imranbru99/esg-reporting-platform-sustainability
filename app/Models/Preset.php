<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preset extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'included_modules' => 'array',
        'sort_order' => 'integer',
    ];
}
