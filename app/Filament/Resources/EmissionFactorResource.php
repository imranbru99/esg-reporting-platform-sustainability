<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmissionFactorResource\Pages;
use App\Models\EmissionFactor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmissionFactorResource extends BaseModuleResource
{
    protected static ?string $model = EmissionFactor::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-fire';

    protected static \UnitEnum|string|null $navigationGroup = 'Environmental (E)';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Emission Factors Library';

    protected static ?string $moduleSlug = 'carbon_ghg';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Factor Activity & Scope')->schema([
                Select::make('library_id')
                    ->relationship('library', 'name')
                    ->required()
                    ->searchable(),
                TextInput::make('activity_name')->label('Activity / Fuel Name')->required()->maxLength(255),
                Select::make('category')->options([
                    'fuels' => 'Stationary & Mobile Fuels',
                    'electricity' => 'Grid & Contractual Electricity',
                    'travel' => 'Business Travel & Passenger Transport',
                    'freight' => 'Freight & Logistics',
                    'waste' => 'Waste Treatment & Disposal',
                    'materials' => 'Raw Materials & Embodied Carbon',
                    'spend' => 'EEIO Spend-Based Factors',
                ])->required(),
                Select::make('scope')->options([
                    'scope1' => 'Scope 1: Direct Combustion / Fugitive',
                    'scope2_location' => 'Scope 2: Location-Based Grid',
                    'scope2_market' => 'Scope 2: Market-Based Residual/Tariff',
                    'scope3_cat1' => 'Scope 3.1: Purchased Goods & Services',
                    'scope3_cat2' => 'Scope 3.2: Capital Goods',
                    'scope3_cat3' => 'Scope 3.3: Fuel & Energy Related',
                    'scope3_cat4' => 'Scope 3.4: Upstream Transportation',
                    'scope3_cat5' => 'Scope 3.5: Waste Generated in Operations',
                    'scope3_cat6' => 'Scope 3.6: Business Travel',
                    'scope3_cat7' => 'Scope 3.7: Employee Commuting',
                    'scope3_cat8' => 'Scope 3.8: Upstream Leased Assets',
                    'scope3_cat9' => 'Scope 3.9: Downstream Transportation',
                    'scope3_cat15' => 'Scope 3.15: Investments / Financed',
                ])->required()->searchable(),
                TextInput::make('factor_value')->label('Factor Value (CO2e)')->numeric()->required(),
                TextInput::make('factor_unit')->label('Unit (e.g. kgCO2e/L, kgCO2e/kWh, kgCO2e/tkm)')->required(),
                TextInput::make('applicable_year')->default('2024')->maxLength(4)->required(),
                TextInput::make('region_code')->label('Region (GLOBAL, BGD, USA, GBR, EU)')->default('GLOBAL')->required(),
                TextInput::make('uncertainty_percentage')->label('Uncertainty (%)')->numeric()->default(5.00),
                TextInput::make('source_reference')->label('Source & Table Citation')->maxLength(255),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('activity_name')->searchable()->sortable()->weight('bold')->limit(35),
                TextColumn::make('scope')->badge()->color(fn (string $state): string => match ($state) {
                    'scope1' => 'danger',
                    'scope2_location', 'scope2_market' => 'warning',
                    default => 'info',
                }),
                TextColumn::make('category')->badge()->color('gray'),
                TextColumn::make('factor_value')->label('Factor Value')->numeric(6)->sortable(),
                TextColumn::make('factor_unit')->label('Unit'),
                TextColumn::make('region_code')->label('Region'),
                TextColumn::make('applicable_year')->label('Year')->sortable(),
                TextColumn::make('library.name')->label('Library')->limit(20),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('scope'),
                Tables\Filters\SelectFilter::make('category'),
                Tables\Filters\SelectFilter::make('region_code'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmissionFactors::route('/'),
            'create' => Pages\CreateEmissionFactor::route('/create'),
            'edit' => Pages\EditEmissionFactor::route('/{record}/edit'),
        ];
    }
}
