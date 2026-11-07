<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectEngagementResource\Pages;
use App\Models\ProjectEngagement;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectEngagementResource extends BaseModuleResource
{
    protected static ?string $model = ProjectEngagement::class;

    protected static ?string $moduleSlug = 'project_management';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static \UnitEnum|string|null $navigationGroup = 'Commercial & Operations';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('client_name')->required(),
            Forms\Components\Select::make('engagement_type')
                ->options([
                    'consultancy' => 'ESG Advisory & Gap Remediation',
                    'audit_preparation' => 'External Assurance Preparation',
                    'decarbonization_roadmap' => 'Net Zero & SBTi Roadmap',
                ])
                ->default('consultancy')
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'planned' => 'Planned',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'on_hold' => 'On Hold',
                ])
                ->default('in_progress')
                ->required(),
            Forms\Components\TextInput::make('budget_amount_cents')
                ->label('Budget (cents)')
                ->numeric()
                ->default(0),
            Forms\Components\DatePicker::make('start_date')->default(now())->required(),
            Forms\Components\DatePicker::make('end_date'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_name')->searchable(),
                Tables\Columns\TextColumn::make('engagement_type')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'completed' => 'success',
                    'in_progress' => 'info',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('budget_amount_cents')
                    ->label('Budget')
                    ->formatStateUsing(fn ($state) => '$'.number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')->date(),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectEngagements::route('/'),
            'create' => Pages\CreateProjectEngagement::route('/create'),
            'edit' => Pages\EditProjectEngagement::route('/{record}/edit'),
        ];
    }
}
