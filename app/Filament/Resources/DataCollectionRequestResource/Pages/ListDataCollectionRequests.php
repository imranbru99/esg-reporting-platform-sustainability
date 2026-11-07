<?php

namespace App\Filament\Resources\DataCollectionRequestResource\Pages;

use App\Filament\Resources\DataCollectionRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataCollectionRequests extends ListRecords
{
    protected static string $resource = DataCollectionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
