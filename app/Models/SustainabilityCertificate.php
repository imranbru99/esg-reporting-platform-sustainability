<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

class SustainabilityCertificate extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'completion_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(AcademyCourse::class, 'academy_course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function generatePdf(): string
    {
        $document = UnicodePdf::preset('bengali')
            ->loadView('certificates.certificate_pdf', [
                'cert' => $this->load(['course', 'user', 'organization']),
            ])
            ->a4()
            ->title("Certificate - {$this->recipient_name}");

        $binary = $document->output();
        $path = "certificates/{$this->organization_id}/cert-{$this->certificate_number}.pdf";
        Storage::disk('public')->put($path, $binary);

        $this->update(['certificate_pdf_path' => $path]);

        return $path;
    }
}
