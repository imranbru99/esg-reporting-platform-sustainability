<?php

namespace App\Filament\Resources\RegulationWatchResource\Pages;

use App\Filament\Resources\RegulationWatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegulationWatch extends EditRecord
{
    protected static string $resource = RegulationWatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
