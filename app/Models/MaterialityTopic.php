<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialityTopic extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    public function assessments()
    {
        return $this->hasMany(MaterialityAssessment::class);
    }
}
