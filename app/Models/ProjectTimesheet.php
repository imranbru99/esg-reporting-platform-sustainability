<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimesheet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function engagement()
    {
        return $this->belongsTo(ProjectEngagement::class, 'project_engagement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
