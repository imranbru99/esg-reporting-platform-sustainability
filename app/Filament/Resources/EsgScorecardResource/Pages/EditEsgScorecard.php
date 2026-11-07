<?php

namespace App\Filament\Resources\EsgScorecardResource\Pages;

use App\Filament\Resources\EsgScorecardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEsgScorecard extends EditRecord
{
    protected static string $resource = EsgScorecardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
