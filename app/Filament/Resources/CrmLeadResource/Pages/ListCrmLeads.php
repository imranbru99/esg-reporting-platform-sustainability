<?php

namespace App\Filament\Resources\CrmLeadResource\Pages;

use App\Filament\Resources\CrmLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrmLeads extends ListRecords
{
    protected static string $resource = CrmLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
