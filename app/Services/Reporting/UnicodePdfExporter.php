<?php

namespace App\Services\Reporting;

use App\Models\ReportProject;
use Illuminate\Support\Facades\Storage;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

class UnicodePdfExporter
{
    /**
     * Export complete pixel-perfect multi-language sustainability report to PDF.
     * Uses ImranDev\UnicodePdf to render UTF-8 scripts (English, Bengali, Arabic, etc.) flawlessly.
     */
    public function export(ReportProject $project): string
    {
        $preset = match ($project->language) {
            'bn' => 'bengali',
            'ar' => 'arabic',
            'hi' => 'devanagari',
            'zh' => 'cjk-sc',
            default => 'default',
        };

        $document = UnicodePdf::preset($preset)
            ->loadView('reports.sustainability_report_pdf', [
                'project' => $project->load(['sections.citations.sourceEvidence', 'signoffs.user', 'organization']),
                'language' => $project->language,
            ])
            ->a4()
            ->title($project->title)
            ->author($project->organization->name);

        $binary = $document->output();

        $path = "reports/{$project->organization_id}/report-{$project->id}-{$project->reporting_period}.pdf";
        Storage::disk('public')->put($path, $binary);

        $project->update(['pdf_path' => $path]);

        return $path;
    }

    /**
     * Generate 2-page executive summary / factsheet.
     */
    public function exportFactsheet(ReportProject $project): string
    {
        $preset = $project->language === 'bn' ? 'bengali' : 'default';

        $document = UnicodePdf::preset($preset)
            ->loadView('reports.sustainability_report_pdf', [
                'project' => $project->load(['sections.citations.sourceEvidence', 'signoffs.user', 'organization']),
                'language' => $project->language,
                'isFactsheet' => true,
            ])
            ->a4();

        $binary = $document->output();
        $path = "reports/{$project->organization_id}/factsheet-{$project->id}.pdf";
        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
