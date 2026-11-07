<?php

namespace App\Filament\Resources\WorkforceSnapshotResource\Pages;

use App\Filament\Resources\WorkforceSnapshotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkforceSnapshots extends ListRecords
{
    protected static string $resource = WorkforceSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
