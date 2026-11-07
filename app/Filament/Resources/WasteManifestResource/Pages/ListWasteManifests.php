<?php

namespace App\Filament\Resources\WasteManifestResource\Pages;

use App\Filament\Resources\WasteManifestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWasteManifests extends ListRecords
{
    protected static string $resource = WasteManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
