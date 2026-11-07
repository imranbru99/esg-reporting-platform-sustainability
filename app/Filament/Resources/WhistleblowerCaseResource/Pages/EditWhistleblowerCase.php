<?php

namespace App\Filament\Resources\WhistleblowerCaseResource\Pages;

use App\Filament\Resources\WhistleblowerCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhistleblowerCase extends EditRecord
{
    protected static string $resource = WhistleblowerCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
