<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarbonCreditResource\Pages;
use App\Models\CarbonCredit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CarbonCreditResource extends BaseModuleResource
{
    protected static ?string $model = CarbonCredit::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static \UnitEnum|string|null $navigationGroup = 'Environmental (E)';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Carbon Credits & Offsets';

    protected static ?string $moduleSlug = 'carbon_credits';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Credit Register & Retirement Proofs')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('project_name')->required(),
                Select::make('standard')->options([
                    'Verra VCS' => 'Verra Verified Carbon Standard (VCS)',
                    'Gold Standard' => 'Gold Standard for the Global Goals',
                    'Puro.earth' => 'Puro.earth Carbon Removal',
                    'CDM' => 'Clean Development Mechanism (UNFCCC)',
                ])->required(),
                TextInput::make('project_type')->default('Reforestation & Afforestation')->required(),
                TextInput::make('vintage_year')->default('2023')->maxLength(4)->required(),
                TextInput::make('tonnes_purchased')->label('Purchased Credits (tCO2e)')->numeric()->required(),
                TextInput::make('tonnes_retired')->label('Retired Credits (tCO2e)')->numeric()->default(0),
                TextInput::make('cost_per_tonne_cents')->label('Cost Per Tonne (Cents)')->numeric()->default(1500),
                TextInput::make('registry_serial_number')->label('Registry Serial Number')->required(),
                Select::make('status')->options([
                    'active' => 'Active Holdings',
                    'retired' => 'Retired / Extinguished',
                ])->default('active')->required(),
                DatePicker::make('retirement_date'),
                TextInput::make('retirement_beneficiary')->label('Retirement Beneficiary / Reason'),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('project_name')->searchable()->sortable()->weight('bold')->limit(30),
                TextColumn::make('standard')->badge()->color('success'),
                TextColumn::make('vintage_year')->label('Vintage'),
                TextColumn::make('tonnes_purchased')->label('Purchased (t)')->numeric()->sortable(),
                TextColumn::make('tonnes_retired')->label('Retired (t)')->numeric()->sortable(),
                TextColumn::make('status')->badge()->color(fn ($state) => $state === 'retired' ? 'info' : 'warning'),
                TextColumn::make('registry_serial_number')->limit(20),

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
            'index' => Pages\ListCarbonCredits::route('/'),
            'create' => Pages\CreateCarbonCredit::route('/create'),
            'edit' => Pages\EditCarbonCredit::route('/{record}/edit'),
        ];
    }
}
