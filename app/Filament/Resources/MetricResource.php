<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetricResource\Pages;
use App\Models\Metric;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MetricResource extends BaseModuleResource
{
    protected static ?string $model = Metric::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-variable';

    protected static \UnitEnum|string|null $navigationGroup = 'ESG Frameworks';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Metric Library & Mapper';

    protected static ?string $moduleSlug = 'disclosure_mapper';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Metric Identity & Categorization')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('code')->label('Metric Code (e.g. GHG-S1, NRG-ELEC)')->maxLength(50),
                Select::make('category')->options([
                    'environmental' => 'Environmental (E)',
                    'social' => 'Social (S)',
                    'governance' => 'Governance (G)',
                ])->required(),
                TextInput::make('subcategory')->label('Subcategory (e.g. ghg_scope1, water, diversity)')->required(),
                Select::make('unit_type')->options([
                    'mass' => 'Mass (t, kg, lb)',
                    'energy' => 'Energy (kWh, MWh, GJ)',
                    'volume' => 'Volume (L, m³, gal)',
                    'count' => 'Count / Headcount',
                    'percentage' => 'Percentage (%)',
                    'ratio' => 'Ratio / Intensity Rate',
                    'currency' => 'Currency / Financial',
                    'text' => 'Qualitative Text',
                ])->required(),
                TextInput::make('default_unit')->label('Standard Unit (e.g. tCO2e, MWh, m3, %)')->required(),
                Select::make('aggregation_method')->options([
                    'sum' => 'Sum (Roll-up total across sites)',
                    'average' => 'Average (Mean value)',
                    'latest' => 'Latest (End of period snapshot)',
                    'weighted' => 'Weighted Average',
                ])->required(),
                Toggle::make('is_standard')->label('Standard Pre-configured Metric')->default(true),
            ])->columns(2),

            Section::make('Calculations & Formula Guidelines')->schema([
                Textarea::make('description')->columnSpanFull(),
                Textarea::make('calculation_formula')->label('Standard Calculation Formula Notes')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('code')->badge()->color('gray'),
                TextColumn::make('category')->badge()->color(fn (string $state): string => match ($state) {
                    'environmental' => 'success',
                    'social' => 'info',
                    'governance' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('subcategory')->sortable(),
                TextColumn::make('default_unit')->label('Standard Unit')->sortable(),
                TextColumn::make('disclosures_count')->counts('disclosures')->label('Mapped Standards'),
                IconColumn::make('is_standard')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options([
                    'environmental' => 'Environmental',
                    'social' => 'Social',
                    'governance' => 'Governance',
                ]),
                Tables\Filters\SelectFilter::make('unit_type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMetrics::route('/'),
            'create' => Pages\CreateMetric::route('/create'),
            'edit' => Pages\EditMetric::route('/{record}/edit'),
        ];
    }
}
