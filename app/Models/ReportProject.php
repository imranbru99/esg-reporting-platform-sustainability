<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportProject extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function sections()
    {
        return $this->hasMany(ReportSection::class)->orderBy('order_index');
    }

    public function signoffs()
    {
        return $this->hasMany(ReportSignoff::class)->orderBy('signed_at');
    }

    public function calculateSha256Hash(): string
    {
        $payload = $this->sections()->pluck('content_markdown')->join("\n---\n");

        return hash('sha256', $payload.'|'.$this->reporting_period.'|'.$this->primary_framework);
    }

    public function lockAndPublish(): void
    {
        $this->update([
            'status' => 'locked',
            'sha256_hash' => $this->calculateSha256Hash(),
            'published_at' => now(),
        ]);
    }
}
