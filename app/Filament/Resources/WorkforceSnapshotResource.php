<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkforceSnapshotResource\Pages;
use App\Models\WorkforceSnapshot;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkforceSnapshotResource extends BaseModuleResource
{
    protected static ?string $model = WorkforceSnapshot::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static \UnitEnum|string|null $navigationGroup = 'Social (S)';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Workforce & Demographics';

    protected static ?string $moduleSlug = 'workforce';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Workforce Demographics & Equity')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('branch_id')->relationship('branch', 'name')->searchable(),
                TextInput::make('reporting_year')->default(date('Y'))->maxLength(4)->required(),
                TextInput::make('total_headcount')->numeric()->required(),
                TextInput::make('male_headcount')->numeric()->default(0),
                TextInput::make('female_headcount')->numeric()->default(0),
                TextInput::make('female_leadership_pct')->label('Women in Leadership %')->numeric()->default(0),
                TextInput::make('turnover_rate_pct')->label('Annual Turnover %')->numeric()->default(0),
                TextInput::make('gender_pay_gap_pct')->label('Gender Pay Gap %')->numeric()->default(0),
                Toggle::make('living_wage_compliant')->label('100% Minimum / Living Wage Compliant')->default(true),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('reporting_year')->label('Year')->sortable()->weight('bold'),
                TextColumn::make('branch.name')->label('Site / Facility'),
                TextColumn::make('total_headcount')->label('Total Headcount')->numeric()->sortable(),
                TextColumn::make('female_headcount')->label('Female Count')->numeric(),
                TextColumn::make('female_leadership_pct')->label('Women in Leadership')->suffix('%'),
                TextColumn::make('turnover_rate_pct')->label('Turnover')->suffix('%'),
                IconColumn::make('living_wage_compliant')->boolean()->label('Living Wage'),

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
            'index' => Pages\ListWorkforceSnapshots::route('/'),
            'create' => Pages\CreateWorkforceSnapshot::route('/create'),
            'edit' => Pages\EditWorkforceSnapshot::route('/{record}/edit'),
        ];
    }
}
