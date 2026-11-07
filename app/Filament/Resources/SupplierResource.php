<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupplierResource extends BaseModuleResource
{
    protected static ?string $model = Supplier::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-truck';

    protected static \UnitEnum|string|null $navigationGroup = 'Supply Chain';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Supplier Register & Risk';

    protected static ?string $moduleSlug = 'suppliers';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Supplier Directory & ESG Rating')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('name')->required(),
                TextInput::make('code')->label('Vendor Code (e.g. SUP-901)'),
                Select::make('tier')->options([
                    'tier_1' => 'Tier 1: Direct Component / Garment Assembler',
                    'tier_2' => 'Tier 2: Dyeing & Finishing Mill / Fabric',
                    'tier_3_raw' => 'Tier 3: Raw Cotton / Yarn / Chemical Farm',
                ])->required(),
                TextInput::make('country_code')->default('BGD')->maxLength(3)->required(),
                TextInput::make('contact_email')->email()->required(),
                Select::make('esg_risk_rating')->options([
                    'low' => 'Low ESG Risk (Gold / Compliant)',
                    'medium' => 'Medium Risk (Action plan required)',
                    'high' => 'High Risk (Audit remediation pending)',
                    'critical' => 'Critical Risk (Suspension warning)',
                ])->default('low')->required(),
                Toggle::make('portal_access_enabled')->label('Enable Supplier Portal Access')->default(true),
                Toggle::make('is_onboarded')->default(true),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('code')->badge()->color('gray'),
                TextColumn::make('tier')->badge()->color('info'),
                TextColumn::make('country_code')->label('Country'),
                TextColumn::make('esg_risk_rating')->badge()->color(fn ($state) => match ($state) {
                    'critical', 'high' => 'danger',
                    'medium' => 'warning',
                    default => 'success',
                }),
                IconColumn::make('portal_access_enabled')->boolean()->label('Portal Access'),

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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
