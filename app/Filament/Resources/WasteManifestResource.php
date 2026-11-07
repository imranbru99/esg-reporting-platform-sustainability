<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WasteManifestResource\Pages;
use App\Models\WasteManifest;
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

class WasteManifestResource extends BaseModuleResource
{
    protected static ?string $model = WasteManifest::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-trash';

    protected static \UnitEnum|string|null $navigationGroup = 'Environmental (E)';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Waste & Circularity';

    protected static ?string $moduleSlug = 'waste';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Waste Stream & Manifest')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('branch_id')->relationship('branch', 'name')->searchable(),
                TextInput::make('waste_stream')->label('Waste Material Stream')->required(),
                Toggle::make('is_hazardous')->label('Hazardous Waste (Requires manifest)')->default(false),
                TextInput::make('weight_tonnes')->label('Weight (Tonnes)')->numeric()->required(),
                Select::make('disposal_route')->options([
                    'recycling' => 'Recycling / Circular Recovery',
                    'composting' => 'Composting / Organic Waste',
                    'energy_recovery' => 'Waste to Energy (Incineration with recovery)',
                    'incineration' => 'Incineration (without recovery)',
                    'landfill' => 'Sanitary Landfill',
                ])->required(),
                TextInput::make('circular_recovery_pct')->label('Circularity / Recycled %')->numeric()->default(0),
                TextInput::make('manifest_number')->label('Chain of Custody / Manifest #'),
                TextInput::make('certified_hauler_name')->label('Certified Waste Hauler'),
                DatePicker::make('disposal_date')->required(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('waste_stream')->searchable()->sortable()->weight('bold'),
                IconColumn::make('is_hazardous')->boolean()->label('Hazardous'),
                TextColumn::make('weight_tonnes')->label('Weight (t)')->numeric(2)->sortable(),
                TextColumn::make('disposal_route')->badge()->color(fn ($state) => match ($state) {
                    'recycling', 'composting' => 'success',
                    'energy_recovery' => 'info',
                    'landfill' => 'danger',
                    default => 'gray',
                }),
                TextColumn::make('circular_recovery_pct')->label('Circularity %')->suffix('%'),
                TextColumn::make('disposal_date')->date()->sortable(),

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
            'index' => Pages\ListWasteManifests::route('/'),
            'create' => Pages\CreateWasteManifest::route('/create'),
            'edit' => Pages\EditWasteManifest::route('/{record}/edit'),
        ];
    }
}
