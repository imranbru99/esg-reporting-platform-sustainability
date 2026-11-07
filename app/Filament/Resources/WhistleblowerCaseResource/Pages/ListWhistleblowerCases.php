<?php

namespace App\Filament\Resources\WhistleblowerCaseResource\Pages;

use App\Filament\Resources\WhistleblowerCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWhistleblowerCases extends ListRecords
{
    protected static string $resource = WhistleblowerCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
