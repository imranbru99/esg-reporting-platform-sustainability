<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhistleblowerCaseResource\Pages;
use App\Models\WhistleblowerCase;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WhistleblowerCaseResource extends BaseModuleResource
{
    protected static ?string $model = WhistleblowerCase::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';

    protected static \UnitEnum|string|null $navigationGroup = 'Social (S)';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Ethics Hotline / Whistleblower';

    protected static ?string $moduleSlug = 'ethics_hotline';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Confidential Whistleblower Case')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('case_token')->label('Anonymous Case Token (Encrypted)')->disabled(),
                Select::make('category')->options([
                    'bribery' => 'Bribery & Financial Corruption',
                    'sexual_harassment' => 'Harassment & Abuse',
                    'safety_violation' => 'Major Safety or Environmental Hazard',
                    'discrimination' => 'Discrimination & Human Rights Violation',
                ])->required(),
                TextInput::make('subject')->required(),
                Select::make('priority')->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent / Whistleblower Protection',
                ])->default('high')->required(),
                Select::make('status')->options([
                    'submitted' => 'Submitted / New',
                    'under_investigation' => 'Under Confidential Investigation',
                    'resolved' => 'Resolved / Action Taken',
                    'dismissed' => 'Dismissed (Unsubstantiated)',
                ])->default('submitted')->required(),
                Textarea::make('report_details')->columnSpanFull()->disabled(),
                Textarea::make('resolution_summary')->columnSpanFull(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('case_token')->label('Case Token')->weight('bold')->limit(12),
                TextColumn::make('category')->badge()->color('gray'),
                TextColumn::make('subject')->limit(30),
                TextColumn::make('priority')->badge()->color(fn ($state) => match ($state) {
                    'urgent', 'high' => 'danger',
                    'medium' => 'warning',
                    default => 'info',
                }),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'resolved' => 'success',
                    'under_investigation' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('created_at')->dateTime()->sortable(),

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
            'index' => Pages\ListWhistleblowerCases::route('/'),
            'create' => Pages\CreateWhistleblowerCase::route('/create'),
            'edit' => Pages\EditWhistleblowerCase::route('/{record}/edit'),
        ];
    }
}
