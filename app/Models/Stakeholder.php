<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];
}
