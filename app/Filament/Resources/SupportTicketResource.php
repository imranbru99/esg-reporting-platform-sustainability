<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SupportTicketResource extends BaseModuleResource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $moduleSlug = 'helpdesk_tickets';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-lifebuoy';

    protected static \UnitEnum|string|null $navigationGroup = 'Commercial & Operations';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('ticket_number')->required(),
            Forms\Components\TextInput::make('requester_name')->required(),
            Forms\Components\TextInput::make('requester_email')->email()->required(),
            Forms\Components\TextInput::make('subject')->required(),
            Forms\Components\Select::make('category')
                ->options([
                    'esg_data' => 'ESG Data & Calculations',
                    'billing' => 'Invoicing & Subscription',
                    'api' => 'API & Integrations',
                    'compliance' => 'Assurance & Compliance',
                    'general' => 'General Inquiry',
                ])
                ->default('general')
                ->required(),
            Forms\Components\Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ])
                ->default('medium')
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed',
                ])
                ->default('open')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('requester_name')->searchable(),
                Tables\Columns\TextColumn::make('subject')->limit(30)->searchable(),
                Tables\Columns\TextColumn::make('priority')->badge()->color(fn (string $state): string => match ($state) {
                    'urgent' => 'danger',
                    'high' => 'warning',
                    'medium' => 'info',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'open' => 'danger',
                    'in_progress' => 'warning',
                    'resolved', 'closed' => 'success',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'create' => Pages\CreateSupportTicket::route('/create'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
