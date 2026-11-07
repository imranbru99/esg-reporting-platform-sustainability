<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductFootprintResource\Pages;
use App\Models\ProductFootprint;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductFootprintResource extends BaseModuleResource
{
    protected static ?string $model = ProductFootprint::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';

    protected static \UnitEnum|string|null $navigationGroup = 'Environmental (E)';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Product Footprints & DPP';

    protected static ?string $moduleSlug = 'product_footprint';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Digital Product Passport (DPP) Specifications')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('product_name')->required(),
                TextInput::make('sku')->label('SKU / Model Number')->required(),
                TextInput::make('dpp_token')->label('DPP Unique Token')->required(),
                TextInput::make('carbon_footprint_kg_co2e')->label('LCA Carbon Footprint (kg CO2e)')->numeric()->required(),
                TextInput::make('recycled_content_percentage')->label('Recycled Content (%)')->numeric()->default(0),
                TextInput::make('repairability_score')->label('Repairability Index (0-10)')->numeric()->default(7.5),
                Toggle::make('has_epd')->label('Environmental Product Declaration (EPD) Verified'),
                TextInput::make('epd_registration_number')->label('EPD Reg Number'),
                TextInput::make('raw_materials_origin')->label('Raw Material Origin (Traceability)'),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('product_name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('sku')->badge()->color('gray'),
                TextColumn::make('carbon_footprint_kg_co2e')->label('Carbon (kg CO2e)')->numeric(2)->sortable(),
                TextColumn::make('recycled_content_percentage')->label('Recycled %')->suffix('%'),
                TextColumn::make('repairability_score')->label('Repairability /10')->numeric(1),
                IconColumn::make('has_epd')->boolean()->label('EPD'),
                TextColumn::make('dpp_token')->label('DPP Token')->limit(15),

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
            'index' => Pages\ListProductFootprints::route('/'),
            'create' => Pages\CreateProductFootprint::route('/create'),
            'edit' => Pages\EditProductFootprint::route('/{record}/edit'),
        ];
    }
}
