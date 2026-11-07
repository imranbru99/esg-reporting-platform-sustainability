<?php

namespace App\Filament\Resources\SupplierAssessmentResource\Pages;

use App\Filament\Resources\SupplierAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierAssessment extends EditRecord
{
    protected static string $resource = SupplierAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
