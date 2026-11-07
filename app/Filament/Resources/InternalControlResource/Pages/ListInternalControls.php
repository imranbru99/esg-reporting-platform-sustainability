<?php

namespace App\Filament\Resources\InternalControlResource\Pages;

use App\Filament\Resources\InternalControlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternalControls extends ListRecords
{
    protected static string $resource = InternalControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
