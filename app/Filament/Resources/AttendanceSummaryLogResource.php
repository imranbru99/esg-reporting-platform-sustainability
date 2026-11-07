<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceSummaryLogResource\Pages;
use App\Models\AttendanceSummaryLog;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceSummaryLogResource extends BaseModuleResource
{
    protected static ?string $model = AttendanceSummaryLog::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clock';

    protected static \UnitEnum|string|null $navigationGroup = 'Social (S)';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Attendance & Working Hours';

    protected static ?string $moduleSlug = 'attendance_hours';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Shift & Overtime Monitoring (AttendanceHub)')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('branch_id')->relationship('branch', 'name')->searchable(),
                TextInput::make('employee_code')->required(),
                TextInput::make('employee_name'),
                DatePicker::make('work_date')->required(),
                TextInput::make('shift_name')->default('Day Shift'),
                TextInput::make('regular_hours')->numeric()->default(8.0),
                TextInput::make('overtime_hours')->numeric()->default(0.0),
                Toggle::make('rest_day_provided')->default(true),
                Toggle::make('excessive_ot_alert')->label('Excessive OT Alert (>2 hrs)'),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('work_date')->date()->sortable(),
                TextColumn::make('employee_code')->weight('bold'),
                TextColumn::make('employee_name')->limit(25),
                TextColumn::make('branch.name')->label('Facility'),
                TextColumn::make('regular_hours')->numeric(1),
                TextColumn::make('overtime_hours')->numeric(1)->sortable(),
                IconColumn::make('excessive_ot_alert')->boolean()->label('Excessive OT'),
                IconColumn::make('rest_day_provided')->boolean()->label('Rest Day'),

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceSummaryLogs::route('/'),
            'create' => Pages\CreateAttendanceSummaryLog::route('/create'),
            'edit' => Pages\EditAttendanceSummaryLog::route('/{record}/edit'),
        ];
    }
}
