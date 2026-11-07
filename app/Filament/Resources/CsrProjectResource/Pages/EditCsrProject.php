<?php

namespace App\Filament\Resources\CsrProjectResource\Pages;

use App\Filament\Resources\CsrProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCsrProject extends EditRecord
{
    protected static string $resource = CsrProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
