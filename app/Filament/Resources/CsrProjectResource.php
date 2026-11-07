<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CsrProjectResource\Pages;
use App\Models\CsrProject;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CsrProjectResource extends BaseModuleResource
{
    protected static ?string $model = CsrProject::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-heart';

    protected static \UnitEnum|string|null $navigationGroup = 'Social (S)';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Community & Social Impact (CSR)';

    protected static ?string $moduleSlug = 'community_csr';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('CSR Project & Community Engagement')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('title')->label('Project Title')->required(),
                Select::make('sdg_tag')->options([
                    'SDG 1' => 'SDG 1: No Poverty',
                    'SDG 3' => 'SDG 3: Good Health and Well-being',
                    'SDG 4' => 'SDG 4: Quality Education',
                    'SDG 5' => 'SDG 5: Gender Equality',
                    'SDG 6' => 'SDG 6: Clean Water and Sanitation',
                    'SDG 8' => 'SDG 8: Decent Work and Economic Growth',
                    'SDG 13' => 'SDG 13: Climate Action',
                ])->default('SDG 3')->required(),
                TextInput::make('budget_cents')->label('Budget (Cents)')->numeric()->default(0),
                TextInput::make('actual_spend_cents')->label('Actual Spend (Cents)')->numeric()->default(0),
                TextInput::make('beneficiaries_count')->label('Total Direct Beneficiaries')->numeric()->default(0),
                TextInput::make('location')->label('Community / Village Location'),
                Textarea::make('impact_description')->columnSpanFull(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->searchable()->sortable()->weight('bold'),
                TextColumn::make('sdg_tag')->badge()->color('emerald'),
                TextColumn::make('beneficiaries_count')->label('Beneficiaries')->numeric()->sortable(),
                TextColumn::make('location'),
                TextColumn::make('actual_spend_cents')->label('Spend (USD)')->formatStateUsing(fn ($state) => '$'.number_format($state / 100, 2)),

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
            'index' => Pages\ListCsrProjects::route('/'),
            'create' => Pages\CreateCsrProject::route('/create'),
            'edit' => Pages\EditCsrProject::route('/{record}/edit'),
        ];
    }
}
