<?php

namespace App\Filament\Resources\EsgScorecardResource\Pages;

use App\Filament\Resources\EsgScorecardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEsgScorecards extends ListRecords
{
    protected static string $resource = EsgScorecardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
