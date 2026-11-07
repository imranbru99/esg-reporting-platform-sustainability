<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoardMemberResource\Pages;
use App\Models\BoardMember;
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

class BoardMemberResource extends BaseModuleResource
{
    protected static ?string $model = BoardMember::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-identification';

    protected static \UnitEnum|string|null $navigationGroup = 'Governance (G)';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Board & Governance';

    protected static ?string $moduleSlug = 'board_governance';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Board Director Profile')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('name')->required(),
                TextInput::make('designation')->placeholder('Independent Non-Executive Director')->required(),
                Toggle::make('is_independent')->label('Independent Director (Meets independence criteria)')->default(true),
                Select::make('gender')->options([
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other / Non-Binary',
                ])->required(),
                DatePicker::make('appointment_date'),
                Toggle::make('esg_oversight_role')->label('Dedicated ESG Committee / Oversight Member')->default(false),
                Toggle::make('executive_remuneration_esg_linked')->label('Compensation Linked to ESG Targets')->default(false),
                TextInput::make('meeting_attendance_percentage')->label('Attendance Rate (%)')->numeric()->default(100.0),
                Toggle::make('is_active')->default(true),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('designation')->sortable(),
                IconColumn::make('is_independent')->boolean()->label('Independent'),
                TextColumn::make('gender')->badge()->color('gray'),
                IconColumn::make('esg_oversight_role')->boolean()->label('ESG Oversight'),
                IconColumn::make('executive_remuneration_esg_linked')->boolean()->label('ESG Pay Link'),
                TextColumn::make('meeting_attendance_percentage')->label('Attendance')->suffix('%'),

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
            'index' => Pages\ListBoardMembers::route('/'),
            'create' => Pages\CreateBoardMember::route('/create'),
            'edit' => Pages\EditBoardMember::route('/{record}/edit'),
        ];
    }
}
