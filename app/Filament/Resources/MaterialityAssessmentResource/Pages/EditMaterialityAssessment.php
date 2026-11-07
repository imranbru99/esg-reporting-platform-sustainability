<?php

namespace App\Filament\Resources\MaterialityAssessmentResource\Pages;

use App\Filament\Resources\MaterialityAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaterialityAssessment extends EditRecord
{
    protected static string $resource = MaterialityAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
