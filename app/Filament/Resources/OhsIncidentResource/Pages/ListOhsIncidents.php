<?php

namespace App\Filament\Resources\OhsIncidentResource\Pages;

use App\Filament\Resources\OhsIncidentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOhsIncidents extends ListRecords
{
    protected static string $resource = OhsIncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
