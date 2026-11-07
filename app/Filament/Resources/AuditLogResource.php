<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogResource extends BaseModuleResource
{
    protected static ?string $model = AuditLog::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static \UnitEnum|string|null $navigationGroup = 'Platform & Tenancy';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Audit Log & Activity';

    protected static ?string $moduleSlug = 'audit_log';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Audit Event Summary')->schema([
                TextInput::make('event')->disabled(),
                TextInput::make('user.name')->label('Actor / User')->disabled(),
                TextInput::make('organization.name')->label('Organization')->disabled(),
                TextInput::make('auditable_type')->label('Target Model')->disabled(),
                TextInput::make('auditable_id')->label('Record ID')->disabled(),
                TextInput::make('ip_address')->disabled(),
                TextInput::make('created_at')->disabled(),
                Textarea::make('url')->columnSpanFull()->disabled(),
            ])->columns(2),

            Section::make('Before / After Values Diff')->schema([
                KeyValue::make('old_values')->disabled(),
                KeyValue::make('new_values')->disabled(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable()->weight('medium'),
                TextColumn::make('event')->badge()->color(fn (string $state): string => match ($state) {
                    'created' => 'info',
                    'updated' => 'warning',
                    'deleted' => 'danger',
                    'approved', 'locked' => 'success',
                    'restated' => 'primary',
                    default => 'gray',
                }),
                TextColumn::make('user.name')->label('Actor')->searchable(),
                TextColumn::make('auditable_type')->label('Entity')->limit(25),
                TextColumn::make('auditable_id')->label('ID'),
                TextColumn::make('ip_address')->label('IP'),
                TextColumn::make('organization.name')->label('Organization')->limit(20),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')->options([
                    'created' => 'Created',
                    'updated' => 'Updated',
                    'deleted' => 'Deleted',
                    'approved' => 'Approved',
                    'locked' => 'Locked',
                    'restated' => 'Restated',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }
}
