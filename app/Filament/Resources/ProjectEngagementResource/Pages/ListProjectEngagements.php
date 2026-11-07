<?php

namespace App\Filament\Resources\ProjectEngagementResource\Pages;

use App\Filament\Resources\ProjectEngagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectEngagements extends ListRecords
{
    protected static string $resource = ProjectEngagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
