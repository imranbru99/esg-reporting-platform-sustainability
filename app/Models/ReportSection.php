<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(ReportProject::class, 'report_project_id');
    }

    public function parent()
    {
        return $this->belongsTo(ReportSection::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ReportSection::class, 'parent_id')->orderBy('order_index');
    }

    public function citations()
    {
        return $this->hasMany(ReportNarrativeCitation::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
