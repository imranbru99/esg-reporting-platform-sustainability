<?php

namespace App\Filament\Resources\ClimateRiskResource\Pages;

use App\Filament\Resources\ClimateRiskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClimateRisks extends ListRecords
{
    protected static string $resource = ClimateRiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
