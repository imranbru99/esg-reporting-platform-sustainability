<?php

namespace App\Filament\Resources\ComplianceObligationResource\Pages;

use App\Filament\Resources\ComplianceObligationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComplianceObligation extends EditRecord
{
    protected static string $resource = ComplianceObligationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
