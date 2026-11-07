<?php

namespace App\Filament\Resources\AcademyCourseResource\Pages;

use App\Filament\Resources\AcademyCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademyCourses extends ListRecords
{
    protected static string $resource = AcademyCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
