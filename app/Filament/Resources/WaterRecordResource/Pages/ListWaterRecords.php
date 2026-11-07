<?php

namespace App\Filament\Resources\WaterRecordResource\Pages;

use App\Filament\Resources\WaterRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWaterRecords extends ListRecords
{
    protected static string $resource = WaterRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
