<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class AcademyCourse extends Model
{
    use BelongsToOrganization, HasFactory, HasUniversalSlug;

    protected $guarded = ['id'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->skipIfFilled();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function certificates()
    {
        return $this->hasMany(SustainabilityCertificate::class);
    }
}
