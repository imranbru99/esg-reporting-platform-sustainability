<?php

namespace App\Filament\Resources\ReportProjectResource\Pages;

use App\Filament\Resources\ReportProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportProject extends EditRecord
{
    protected static string $resource = ReportProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
