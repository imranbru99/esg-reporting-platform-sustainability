<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EsgScorecardResource\Pages;
use App\Models\EsgScorecard;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class EsgScorecardResource extends BaseModuleResource
{
    protected static ?string $model = EsgScorecard::class;

    protected static ?string $moduleSlug = 'esg_scorecards';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-trophy';

    protected static \UnitEnum|string|null $navigationGroup = 'Analytics & Intelligence';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('reporting_year')
                ->required()
                ->maxLength(4),
            Forms\Components\TextInput::make('overall_score')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('environmental_score')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('social_score')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('governance_score')
                ->numeric()
                ->required(),
            Forms\Components\Select::make('rating_grade')
                ->options([
                    'AAA' => 'AAA - Global ESG Leader',
                    'AA' => 'AA - Industry Outperformer',
                    'A' => 'A - Robust ESG Management',
                    'BBB' => 'BBB - Average ESG Profile',
                    'BB' => 'BB - Moderate Exposure',
                    'B' => 'B - Significant ESG Gaps',
                    'CCC' => 'CCC - High ESG Risk',
                ])
                ->default('BBB')
                ->required(),
            Forms\Components\TextInput::make('methodology')
                ->default('Weighted Pillar Algorithm (E:40%, S:30%, G:30%)')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reporting_year')->sortable(),
                Tables\Columns\TextColumn::make('rating_grade')->badge()->color(fn (string $state): string => match ($state) {
                    'AAA', 'AA' => 'success',
                    'A', 'BBB' => 'info',
                    'BB', 'B' => 'warning',
                    default => 'danger',
                })->sortable(),
                Tables\Columns\TextColumn::make('overall_score')->label('Overall Score')->suffix('/100')->sortable(),
                Tables\Columns\TextColumn::make('environmental_score')->label('E Score')->suffix('/100'),
                Tables\Columns\TextColumn::make('social_score')->label('S Score')->suffix('/100'),
                Tables\Columns\TextColumn::make('governance_score')->label('G Score')->suffix('/100'),
                Tables\Columns\TextColumn::make('generated_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEsgScorecards::route('/'),
            'create' => Pages\CreateEsgScorecard::route('/create'),
            'edit' => Pages\EditEsgScorecard::route('/{record}/edit'),
        ];
    }
}
