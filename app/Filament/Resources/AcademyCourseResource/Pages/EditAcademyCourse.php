<?php

namespace App\Filament\Resources\AcademyCourseResource\Pages;

use App\Filament\Resources\AcademyCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcademyCourse extends EditRecord
{
    protected static string $resource = AcademyCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
