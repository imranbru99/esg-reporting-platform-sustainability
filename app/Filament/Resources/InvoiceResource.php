<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends BaseModuleResource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $moduleSlug = 'invoicing_billing';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static \UnitEnum|string|null $navigationGroup = 'Commercial & Operations';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('invoice_number')->required(),
            Forms\Components\TextInput::make('client_name')->required(),
            Forms\Components\TextInput::make('billing_email')->email()->required(),
            Forms\Components\TextInput::make('subtotal_cents')->numeric()->default(0),
            Forms\Components\TextInput::make('tax_amount_cents')->numeric()->default(0),
            Forms\Components\TextInput::make('total_amount_cents')->numeric()->default(0),
            Forms\Components\TextInput::make('currency')->default('USD'),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'sent' => 'Sent',
                    'paid' => 'Paid',
                    'overdue' => 'Overdue',
                ])
                ->default('draft')
                ->required(),
            Forms\Components\DatePicker::make('due_date')->default(now()->addDays(30))->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_name')->searchable(),
                Tables\Columns\TextColumn::make('total_amount_cents')
                    ->label('Total')
                    ->formatStateUsing(fn ($state, $record) => number_format($state / 100, 2).' '.$record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'paid' => 'success',
                    'sent' => 'info',
                    'overdue' => 'danger',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('due_date')->date(),
            ])
            ->recordActions([
                Tables\Actions\Action::make('generate_pdf')
                    ->label('Invoice PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Invoice $record) {
                        $path = $record->generatePdf();
                        Notification::make()
                            ->title('Invoice PDF Generated')
                            ->body("Generated via Unicode PDF at {$path}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
