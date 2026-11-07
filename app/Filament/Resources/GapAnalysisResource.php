<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GapAnalysisResource\Pages;
use App\Models\GapAnalysis;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GapAnalysisResource extends BaseModuleResource
{
    protected static ?string $model = GapAnalysis::class;

    protected static ?string $moduleSlug = 'gap_analysis';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';

    protected static \UnitEnum|string|null $navigationGroup = 'Analytics & Intelligence';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('framework_slug')
                ->required()
                ->maxLength(50),
            Forms\Components\TextInput::make('reporting_year')
                ->required()
                ->maxLength(4),
            Forms\Components\TextInput::make('overall_readiness_score')
                ->numeric()
                ->suffix('%')
                ->required(),
            Forms\Components\TextInput::make('completed_disclosures')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('total_required_disclosures')
                ->numeric()
                ->required(),
            Forms\Components\DateTimePicker::make('generated_at')
                ->default(now()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('framework_slug')->badge()->sortable(),
                Tables\Columns\TextColumn::make('reporting_year')->sortable(),
                Tables\Columns\TextColumn::make('overall_readiness_score')->label('Readiness')->suffix('%')->sortable(),
                Tables\Columns\TextColumn::make('completed_disclosures')->label('Completed'),
                Tables\Columns\TextColumn::make('total_required_disclosures')->label('Total Required'),
                Tables\Columns\TextColumn::make('generated_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGapAnalyses::route('/'),
            'create' => Pages\CreateGapAnalysis::route('/create'),
            'edit' => Pages\EditGapAnalysis::route('/{record}/edit'),
        ];
    }
}
