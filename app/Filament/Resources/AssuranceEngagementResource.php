<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssuranceEngagementResource\Pages;
use App\Models\AssuranceEngagement;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssuranceEngagementResource extends BaseModuleResource
{
    protected static ?string $model = AssuranceEngagement::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-check-badge';

    protected static \UnitEnum|string|null $navigationGroup = 'Data & Assurance';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Assurance Workspace (Auditor)';

    protected static ?string $moduleSlug = 'assurance_workspace';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Independent External Assurance Engagement')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('title')->label('Engagement Title')->required(),
                TextInput::make('audit_firm')->label('Assurance Firm (e.g. PwC, EY, KPMG, SGS, TÜV)')->required(),
                Select::make('standard')->options([
                    'ISAE 3000' => 'ISAE 3000 (Revised) Non-Financial Assurance',
                    'ISAE 3410' => 'ISAE 3410 Specific GHG Statement Assurance',
                    'AA1000AS' => 'AA1000AS v3 Assurance Standard',
                ])->default('ISAE 3000')->required(),
                Select::make('assurance_level')->options([
                    'limited' => 'Limited Assurance (Negative conclusion)',
                    'reasonable' => 'Reasonable Assurance (High level audit conclusion)',
                ])->default('limited')->required(),
                TextInput::make('reporting_period_covered')->default('FY2024')->required(),
                Select::make('status')->options([
                    'planning' => 'Audit Planning & Scoping',
                    'fieldwork' => 'Fieldwork & Evidence Testing',
                    'draft_statement' => 'Draft Assurance Statement Review',
                    'signed_off' => 'Signed Independent Assurance Statement Issued',
                ])->default('signed_off')->required(),
                DatePicker::make('signed_off_date'),
                TextInput::make('statement_pdf_path')->label('Signed Statement PDF URL'),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->searchable()->sortable()->weight('bold'),
                TextColumn::make('audit_firm')->badge()->color('emerald')->sortable(),
                TextColumn::make('standard')->badge()->color('gray'),
                TextColumn::make('assurance_level')->badge()->color('info'),
                TextColumn::make('reporting_period_covered')->label('Period'),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'signed_off' => 'success',
                    'fieldwork' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('signed_off_date')->date(),

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
            'index' => Pages\ListAssuranceEngagements::route('/'),
            'create' => Pages\CreateAssuranceEngagement::route('/create'),
            'edit' => Pages\EditAssuranceEngagement::route('/{record}/edit'),
        ];
    }
}
