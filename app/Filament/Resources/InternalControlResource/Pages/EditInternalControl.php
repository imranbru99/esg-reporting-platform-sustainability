<?php

namespace App\Filament\Resources\InternalControlResource\Pages;

use App\Filament\Resources\InternalControlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternalControl extends EditRecord
{
    protected static string $resource = InternalControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
