<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EsgTargetResource\Pages;
use App\Models\EsgTarget;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EsgTargetResource extends BaseModuleResource
{
    protected static ?string $model = EsgTarget::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-flag';

    protected static \UnitEnum|string|null $navigationGroup = 'Materiality & Strategy';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Strategy, Goals & Targets (SBTi)';

    protected static ?string $moduleSlug = 'strategy_targets';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('SMART Target & SBTi Alignment')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('title')->label('Target Name (e.g. Net-Zero Scope 1 & 2 by 2040)')->required(),
                Select::make('target_type')->options([
                    'sbti_1_5c' => 'SBTi 1.5°C Near-Term (42% reduction by 2030)',
                    'net_zero_2050' => 'Net-Zero Long-Term Target (90% reduction by 2050)',
                    'renewable_100' => 'RE100: 100% Renewable Electricity',
                    'water_neutrality' => 'Water Stewardship & Replenishment',
                    'gender_equity' => 'Workforce Gender Parity (50/50)',
                ])->required(),
                TextInput::make('baseline_year')->default('2022')->maxLength(4)->required(),
                TextInput::make('baseline_value')->numeric()->required(),
                TextInput::make('target_year')->default('2030')->maxLength(4)->required(),
                TextInput::make('target_value')->numeric()->required(),
                TextInput::make('current_value')->numeric()->required(),
                TextInput::make('unit')->default('tCO2e')->required(),
                TextInput::make('progress_percentage')->label('Progress (%)')->numeric()->default(0),
                Select::make('status')->options([
                    'on_track' => 'On Track',
                    'at_risk' => 'At Risk',
                    'behind' => 'Behind Schedule',
                    'achieved' => 'Target Achieved',
                ])->default('on_track')->required(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->searchable()->sortable()->weight('bold')->limit(35),
                TextColumn::make('target_type')->badge()->color('info'),
                TextColumn::make('baseline_year')->label('Base Yr'),
                TextColumn::make('target_year')->label('Target Yr')->sortable(),
                TextColumn::make('progress_percentage')->label('Progress')->suffix('%')->sortable(),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'achieved', 'on_track' => 'success',
                    'at_risk' => 'warning',
                    'behind' => 'danger',
                    default => 'gray',
                }),

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
            'index' => Pages\ListEsgTargets::route('/'),
            'create' => Pages\CreateEsgTarget::route('/create'),
            'edit' => Pages\EditEsgTarget::route('/{record}/edit'),
        ];
    }
}
