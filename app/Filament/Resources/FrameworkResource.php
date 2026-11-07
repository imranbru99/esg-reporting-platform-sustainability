<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrameworkResource\Pages;
use App\Models\Framework;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FrameworkResource extends BaseModuleResource
{
    protected static ?string $model = Framework::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    protected static \UnitEnum|string|null $navigationGroup = 'ESG Frameworks';

    protected static ?int $navigationSort = 1;

    protected static ?string $moduleSlug = 'frameworks_library';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Standard Information')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('version')->required()->maxLength(50),
                TextInput::make('publisher')->required()->maxLength(255),
                TextInput::make('documentation_url')->label('Official Standard Documentation URL')->url(),
                Textarea::make('description')->columnSpanFull(),
                Toggle::make('is_active')->label('Enabled in Organization Disclosures')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('version')->badge()->color('info'),
                TextColumn::make('publisher')->sortable()->limit(30),
                TextColumn::make('disclosures_count')->counts('disclosures')->label('Disclosures'),
                IconColumn::make('is_active')->boolean(),
                IconColumn::make('is_system')->boolean()->label('Official'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrameworks::route('/'),
            'create' => Pages\CreateFramework::route('/create'),
            'edit' => Pages\EditFramework::route('/{record}/edit'),
        ];
    }
}
