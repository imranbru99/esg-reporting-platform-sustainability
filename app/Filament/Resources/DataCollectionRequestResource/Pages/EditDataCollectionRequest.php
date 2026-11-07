<?php

namespace App\Filament\Resources\DataCollectionRequestResource\Pages;

use App\Filament\Resources\DataCollectionRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataCollectionRequest extends EditRecord
{
    protected static string $resource = DataCollectionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
