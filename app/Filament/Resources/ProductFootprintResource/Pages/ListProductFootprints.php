<?php

namespace App\Filament\Resources\ProductFootprintResource\Pages;

use App\Filament\Resources\ProductFootprintResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductFootprints extends ListRecords
{
    protected static string $resource = ProductFootprintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
