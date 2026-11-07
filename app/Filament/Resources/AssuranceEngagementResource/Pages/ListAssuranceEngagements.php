<?php

namespace App\Filament\Resources\AssuranceEngagementResource\Pages;

use App\Filament\Resources\AssuranceEngagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssuranceEngagements extends ListRecords
{
    protected static string $resource = AssuranceEngagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
