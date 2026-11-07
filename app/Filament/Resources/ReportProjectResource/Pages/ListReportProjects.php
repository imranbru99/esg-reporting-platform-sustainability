<?php

namespace App\Filament\Resources\ReportProjectResource\Pages;

use App\Filament\Resources\ReportProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportProjects extends ListRecords
{
    protected static string $resource = ReportProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
