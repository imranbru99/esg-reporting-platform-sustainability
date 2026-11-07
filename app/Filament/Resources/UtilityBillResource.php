<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UtilityBillResource\Pages;
use App\Models\UtilityBill;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UtilityBillResource extends BaseModuleResource
{
    protected static ?string $model = UtilityBill::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-bolt';

    protected static \UnitEnum|string|null $navigationGroup = 'Environmental (E)';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Energy & Utility Bills';

    protected static ?string $moduleSlug = 'energy';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Utility Bill Details')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('branch_id')->relationship('branch', 'name')->searchable(),
                Select::make('utility_type')->options([
                    'electricity' => 'Electricity (Grid)',
                    'natural_gas' => 'Natural Gas',
                    'water' => 'Water Utility',
                    'steam' => 'District Steam',
                ])->required(),
                TextInput::make('provider_name')->label('Utility Provider'),
                TextInput::make('invoice_number')->label('Bill / Invoice #'),
                DatePicker::make('billing_period_start')->required(),
                DatePicker::make('billing_period_end')->required(),
                TextInput::make('consumption_quantity')->numeric()->required(),
                TextInput::make('consumption_unit')->default('kWh')->required(),
                TextInput::make('amount_cents')->label('Amount (Cents)')->numeric()->default(0),
                Select::make('ocr_status')->options([
                    'pending' => 'Pending AI OCR',
                    'processed' => 'Processed by AI Hub',
                    'verified' => 'Human Verified',
                ])->default('processed'),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('invoice_number')->searchable()->sortable()->weight('bold'),
                TextColumn::make('utility_type')->badge()->color('warning'),
                TextColumn::make('branch.name')->label('Facility'),
                TextColumn::make('consumption_quantity')->formatStateUsing(fn ($state, $record) => number_format($state).' '.$record->consumption_unit)->sortable(),
                TextColumn::make('billing_period_start')->date()->sortable(),
                TextColumn::make('ocr_status')->badge()->color(fn (string $state): string => match ($state) {
                    'verified' => 'success',
                    'processed' => 'info',
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
            'index' => Pages\ListUtilityBills::route('/'),
            'create' => Pages\CreateUtilityBill::route('/create'),
            'edit' => Pages\EditUtilityBill::route('/{record}/edit'),
        ];
    }
}
