<?php

namespace App\Filament\Resources\MaterialityAssessmentResource\Pages;

use App\Filament\Resources\MaterialityAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaterialityAssessments extends ListRecords
{
    protected static string $resource = MaterialityAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
