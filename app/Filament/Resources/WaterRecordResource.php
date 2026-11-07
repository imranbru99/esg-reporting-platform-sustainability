<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaterRecordResource\Pages;
use App\Models\WaterRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WaterRecordResource extends BaseModuleResource
{
    protected static ?string $model = WaterRecord::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-beaker';

    protected static \UnitEnum|string|null $navigationGroup = 'Environmental (E)';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Water & Effluents';

    protected static ?string $moduleSlug = 'water';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Water Withdrawal & Discharge')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('branch_id')->relationship('branch', 'name')->searchable(),
                DatePicker::make('period_start')->required(),
                DatePicker::make('period_end')->required(),
                Select::make('withdrawal_source')->options([
                    'municipal' => 'Municipal Water Network',
                    'groundwater' => 'Groundwater (Borewell / Tube-well)',
                    'surface_water' => 'Surface Water (River / Lake)',
                    'rainwater' => 'Harvested Rainwater',
                ])->required(),
                TextInput::make('withdrawal_volume_m3')->label('Withdrawal (m³)')->numeric()->required(),
                Select::make('discharge_destination')->options([
                    'municipal_sewer' => 'Municipal Sewerage System',
                    'surface_water' => 'Treated Discharge to Surface Water',
                    'offsite_treatment' => 'Central Effluent Treatment Plant (CETP)',
                ]),
                TextInput::make('discharge_volume_m3')->label('Discharge (m³)')->numeric()->default(0),
                TextInput::make('recycled_volume_m3')->label('Recycled Water (m³)')->numeric()->default(0),
                Select::make('water_stress_level')->options([
                    'low' => 'Low Stress Area',
                    'medium' => 'Medium Stress Area',
                    'high' => 'High Stress Area',
                    'extremely_high' => 'Extremely High Stress Basin',
                ])->default('low'),
                TextInput::make('effluent_bod_mg_l')->label('BOD (mg/L)')->numeric(),
                TextInput::make('effluent_cod_mg_l')->label('COD (mg/L)')->numeric(),
                TextInput::make('effluent_ph')->label('pH Value')->numeric(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('withdrawal_source')->badge()->color('info')->sortable(),
                TextColumn::make('branch.name')->label('Facility'),
                TextColumn::make('withdrawal_volume_m3')->label('Withdrawal (m³)')->numeric()->sortable(),
                TextColumn::make('discharge_volume_m3')->label('Discharge (m³)')->numeric()->sortable(),
                TextColumn::make('recycled_volume_m3')->label('Recycled (m³)')->numeric(),
                TextColumn::make('water_stress_level')->badge()->color(fn ($state) => in_array($state, ['high', 'extremely_high']) ? 'danger' : 'success'),
                TextColumn::make('period_start')->date()->sortable(),

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
            'index' => Pages\ListWaterRecords::route('/'),
            'create' => Pages\CreateWaterRecord::route('/create'),
            'edit' => Pages\EditWaterRecord::route('/{record}/edit'),
        ];
    }
}
