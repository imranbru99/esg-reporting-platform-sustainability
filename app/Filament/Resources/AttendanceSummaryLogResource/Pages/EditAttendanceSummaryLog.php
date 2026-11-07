<?php

namespace App\Filament\Resources\AttendanceSummaryLogResource\Pages;

use App\Filament\Resources\AttendanceSummaryLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceSummaryLog extends EditRecord
{
    protected static string $resource = AttendanceSummaryLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
