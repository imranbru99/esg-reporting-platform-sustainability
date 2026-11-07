<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegulationWatchResource\Pages;
use App\Models\RegulationWatch;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegulationWatchResource extends BaseModuleResource
{
    protected static ?string $model = RegulationWatch::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-newspaper';

    protected static \UnitEnum|string|null $navigationGroup = 'ESG Frameworks';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Regulation Watch (AI Scraper)';

    protected static ?string $moduleSlug = 'regulation_watch';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Scraped Regulation Update')->schema([
                TextInput::make('headline')->required()->disabled(),
                TextInput::make('framework_slug')->label('Impacted Framework')->disabled(),
                TextInput::make('source_url')->label('Source URL')->url()->disabled(),
                TextInput::make('published_at')->disabled(),
                Select::make('impact_level')->options([
                    'low' => 'Low Impact',
                    'medium' => 'Medium Impact',
                    'high' => 'High Impact',
                    'critical' => 'Critical Compliance Mandate',
                ])->disabled(),
                Select::make('status')->options([
                    'pending_review' => 'Pending Review Queue',
                    'acknowledged' => 'Acknowledged by Team',
                    'action_created' => 'Task & Action Plan Created',
                    'archived' => 'Archived',
                ])->required(),
            ])->columns(2),

            Section::make('AI Synthesis & Action Plan (via laravel-ai-catalog & ai-hub)')->schema([
                Textarea::make('ai_summary')->label('Executive Impact Summary')->columnSpanFull()->disabled(),
                Textarea::make('raw_text')->label('Original Scraped Text')->columnSpanFull()->disabled(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('headline')->searchable()->sortable()->weight('bold')->limit(45),
                TextColumn::make('framework_slug')->badge()->color('emerald')->label('Framework'),
                TextColumn::make('impact_level')->badge()->color(fn (string $state): string => match ($state) {
                    'critical' => 'danger',
                    'high' => 'warning',
                    'medium' => 'info',
                    default => 'gray',
                }),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'pending_review' => 'warning',
                    'acknowledged' => 'info',
                    'action_created' => 'success',
                    default => 'gray',
                }),
                TextColumn::make('published_at')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('impact_level'),
                Tables\Filters\SelectFilter::make('status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegulationWatches::route('/'),
            'view' => Pages\ViewRegulationWatch::route('/{record}'),
            'edit' => Pages\EditRegulationWatch::route('/{record}/edit'),
        ];
    }
}
