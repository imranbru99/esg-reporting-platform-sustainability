<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CrmLeadResource\Pages;
use App\Models\CrmLead;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CrmLeadResource extends BaseModuleResource
{
    protected static ?string $model = CrmLead::class;

    protected static ?string $moduleSlug = 'crm_pipeline';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-funnel';

    protected static \UnitEnum|string|null $navigationGroup = 'Commercial & Operations';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('contact_name')->required(),
            Forms\Components\TextInput::make('company_name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('phone'),
            Forms\Components\Select::make('stage')
                ->options([
                    'new' => 'New Lead',
                    'qualified' => 'Qualified',
                    'proposal' => 'Proposal Sent',
                    'negotiation' => 'Negotiation',
                    'won' => 'Closed Won',
                    'lost' => 'Closed Lost',
                ])
                ->default('new')
                ->required(),
            Forms\Components\TextInput::make('estimated_value_cents')
                ->label('Estimated Value (cents)')
                ->numeric()
                ->default(0),
            Forms\Components\TextInput::make('currency')->default('USD'),
            Forms\Components\Textarea::make('notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact_name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('stage')->badge()->color(fn (string $state): string => match ($state) {
                    'won' => 'success',
                    'proposal', 'negotiation' => 'warning',
                    'lost' => 'danger',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('estimated_value_cents')
                    ->label('Estimated Value')
                    ->formatStateUsing(fn ($state, $record) => number_format($state / 100, 2).' '.$record->currency)
                    ->sortable(),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCrmLeads::route('/'),
            'create' => Pages\CreateCrmLead::route('/create'),
            'edit' => Pages\EditCrmLead::route('/{record}/edit'),
        ];
    }
}
