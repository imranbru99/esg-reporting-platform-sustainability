<?php

namespace App\Filament\Resources\GapAnalysisResource\Pages;

use App\Filament\Resources\GapAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGapAnalyses extends ListRecords
{
    protected static string $resource = GapAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
