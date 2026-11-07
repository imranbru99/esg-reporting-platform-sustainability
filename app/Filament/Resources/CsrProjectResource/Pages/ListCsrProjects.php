<?php

namespace App\Filament\Resources\CsrProjectResource\Pages;

use App\Filament\Resources\CsrProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCsrProjects extends ListRecords
{
    protected static string $resource = CsrProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
