<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BranchResource extends BaseModuleResource
{
    protected static ?string $model = Branch::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-map-pin';

    protected static \UnitEnum|string|null $navigationGroup = 'Platform & Tenancy';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Sites & Facilities';

    protected static ?string $moduleSlug = 'tenant_management';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Facility Identity')->schema([
                Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->required()
                    ->searchable(),
                Select::make('legal_entity_id')
                    ->relationship('legalEntity', 'name')
                    ->searchable(),
                TextInput::make('name')->label('Facility / Site Name')->required()->maxLength(255),
                TextInput::make('code')->label('Site Code (e.g. FAC-001)')->maxLength(50),
                Select::make('facility_type')->options([
                    'factory' => 'Manufacturing Plant / Factory',
                    'office' => 'Corporate Office / HQ',
                    'warehouse' => 'Warehouse / Logistics Hub',
                    'retail' => 'Retail Store / Showroom',
                    'data_center' => 'Data Center',
                    'farm' => 'Agricultural Farm / Field',
                    'mine' => 'Extraction / Mining Site',
                ])->required(),
                Toggle::make('is_active')->label('Operating / Active')->default(true),
            ])->columns(2),

            Section::make('Location & Capacity')->schema([
                TextInput::make('address')->columnSpanFull(),
                TextInput::make('city')->maxLength(100),
                TextInput::make('state')->maxLength(100),
                TextInput::make('country_code')->label('Country (ISO 3)')->default('BGD')->maxLength(3),
                TextInput::make('postal_code')->maxLength(20),
                TextInput::make('latitude')->numeric(),
                TextInput::make('longitude')->numeric(),
                TextInput::make('gross_floor_area')->label('Gross Floor Area')->numeric(),
                Select::make('area_unit')->options([
                    'sqm' => 'Square Meters (m²)',
                    'sqft' => 'Square Feet (sqft)',
                ])->default('sqm'),
                TextInput::make('headcount')->label('Full-time Headcount')->numeric()->default(0),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('organization.name')->sortable()->limit(25),
                TextColumn::make('facility_type')->badge()->sortable(),
                TextColumn::make('city')->sortable(),
                TextColumn::make('country_code')->label('Country'),
                TextColumn::make('gross_floor_area')->formatStateUsing(fn ($state, $record) => $state ? number_format($state).' '.$record->area_unit : '-'),
                TextColumn::make('headcount')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('facility_type'),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
