<?php

namespace App\Filament\Resources\CorporatePolicyResource\Pages;

use App\Filament\Resources\CorporatePolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorporatePolicy extends EditRecord
{
    protected static string $resource = CorporatePolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
