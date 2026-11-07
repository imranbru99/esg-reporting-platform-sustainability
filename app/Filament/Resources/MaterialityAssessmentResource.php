<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialityAssessmentResource\Pages;
use App\Models\MaterialityAssessment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialityAssessmentResource extends BaseModuleResource
{
    protected static ?string $model = MaterialityAssessment::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static \UnitEnum|string|null $navigationGroup = 'Materiality & Strategy';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Double Materiality Matrix';

    protected static ?string $moduleSlug = 'materiality';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Double Materiality Scoring (1.00 to 5.00)')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('materiality_topic_id')->relationship('topic', 'name')->required()->searchable(),
                TextInput::make('reporting_year')->default(date('Y'))->maxLength(4)->required(),
                TextInput::make('impact_materiality_score')->label('Impact Materiality Score (Inside-Out)')->numeric()->required(),
                TextInput::make('financial_materiality_score')->label('Financial Materiality Score (Outside-In)')->numeric()->required(),
                Toggle::make('is_double_material')->label('Double Material Topic (Priority 1 Disclosure)')->default(true),
                Select::make('stakeholder_priority')->options([
                    'low' => 'Low Priority',
                    'medium' => 'Medium Priority',
                    'high' => 'High Priority',
                ])->default('high')->required(),
                Select::make('status')->options([
                    'draft' => 'Draft Assessment',
                    'approved' => 'Approved by Committee',
                ])->default('approved')->required(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('topic.name')->label('ESG Topic')->searchable()->sortable()->weight('bold'),
                TextColumn::make('topic.category')->badge()->color('emerald'),
                TextColumn::make('reporting_year')->label('Year')->sortable(),
                TextColumn::make('impact_materiality_score')->label('Impact Score /5')->numeric(2)->sortable(),
                TextColumn::make('financial_materiality_score')->label('Financial Score /5')->numeric(2)->sortable(),
                IconColumn::make('is_double_material')->boolean()->label('Double Material'),
                TextColumn::make('stakeholder_priority')->badge()->color(fn ($state) => $state === 'high' ? 'danger' : 'info'),

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
            'index' => Pages\ListMaterialityAssessments::route('/'),
            'create' => Pages\CreateMaterialityAssessment::route('/create'),
            'edit' => Pages\EditMaterialityAssessment::route('/{record}/edit'),
        ];
    }
}
