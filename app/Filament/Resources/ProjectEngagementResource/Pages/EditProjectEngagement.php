<?php

namespace App\Filament\Resources\ProjectEngagementResource\Pages;

use App\Filament\Resources\ProjectEngagementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectEngagement extends EditRecord
{
    protected static string $resource = ProjectEngagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
