<?php

namespace App\Filament\Resources\GapAnalysisResource\Pages;

use App\Filament\Resources\GapAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGapAnalysis extends EditRecord
{
    protected static string $resource = GapAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
