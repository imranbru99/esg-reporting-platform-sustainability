<?php

namespace App\Filament\Resources\WasteManifestResource\Pages;

use App\Filament\Resources\WasteManifestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWasteManifest extends EditRecord
{
    protected static string $resource = WasteManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
