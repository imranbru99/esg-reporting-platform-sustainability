<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEngagement extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function timesheets()
    {
        return $this->hasMany(ProjectTimesheet::class);
    }
}
