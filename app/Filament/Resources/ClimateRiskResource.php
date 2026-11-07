<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClimateRiskResource\Pages;
use App\Models\ClimateRisk;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClimateRiskResource extends BaseModuleResource
{
    protected static ?string $model = ClimateRisk::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static \UnitEnum|string|null $navigationGroup = 'Materiality & Strategy';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Climate Risks & TCFD';

    protected static ?string $moduleSlug = 'risks_opportunities';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Climate Physical & Transition Risk')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('title')->label('Risk Title (e.g. Extreme Monsoon Flooding in Coastal Mills)')->required(),
                Select::make('risk_category')->options([
                    'physical_acute' => 'Physical Acute (Cyclones, Floods, Heatwaves)',
                    'physical_chronic' => 'Physical Chronic (Sea level rise, Drought, Temperature shift)',
                    'transition_policy' => 'Transition Policy & Legal (Carbon taxes, CBAM tariffs)',
                    'transition_technology' => 'Transition Technology (Electrification capital costs)',
                    'transition_market' => 'Transition Market (Shift to low-carbon consumer preference)',
                ])->required(),
                Select::make('climate_scenario')->options([
                    '1.5C' => '1.5°C Orderly Net-Zero Transition (IPCC SSP1-1.9)',
                    '2.0C' => '2.0°C Delayed Policy Reaction (IPCC SSP1-2.6)',
                    '4.0C' => '4.0°C High Physical Damage World (IPCC SSP5-8.5)',
                ])->default('1.5C')->required(),
                Select::make('time_horizon')->options([
                    'short' => 'Short-Term (0-3 years)',
                    'medium' => 'Medium-Term (3-10 years)',
                    'long' => 'Long-Term (10-30 years)',
                ])->default('medium')->required(),
                TextInput::make('estimated_financial_impact_cents')->label('Estimated Financial Impact (Cents)')->numeric()->default(0),
                Textarea::make('mitigation_plan')->label('Mitigation & Adaptation Strategy')->columnSpanFull(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->searchable()->sortable()->weight('bold')->limit(40),
                TextColumn::make('risk_category')->badge()->color('danger'),
                TextColumn::make('climate_scenario')->badge()->color('gray'),
                TextColumn::make('time_horizon')->badge()->color('info'),
                TextColumn::make('estimated_financial_impact_cents')->label('Financial Exposure')->formatStateUsing(fn ($state) => '$'.number_format($state / 100, 2))->sortable(),

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
            'index' => Pages\ListClimateRisks::route('/'),
            'create' => Pages\CreateClimateRisk::route('/create'),
            'edit' => Pages\EditClimateRisk::route('/{record}/edit'),
        ];
    }
}
