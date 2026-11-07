<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierAssessmentResource\Pages;
use App\Models\SupplierAssessment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupplierAssessmentResource extends BaseModuleResource
{
    protected static ?string $model = SupplierAssessment::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-check';

    protected static \UnitEnum|string|null $navigationGroup = 'Supply Chain';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Supplier ESG Assessments';

    protected static ?string $moduleSlug = 'supplier_assessments';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Supplier ESG Questionnaire & Scorecard')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('supplier_id')->relationship('supplier', 'name')->required()->searchable(),
                TextInput::make('questionnaire_name')->default('Annual Higg / ESG Supplier Assessment')->required(),
                TextInput::make('score_percentage')->label('ESG Score (%)')->numeric()->default(0),
                Select::make('status')->options([
                    'draft' => 'Draft',
                    'sent' => 'Sent to Supplier',
                    'submitted' => 'Submitted by Vendor',
                    'reviewed' => 'Under Buyer Review',
                    'approved' => 'Approved & Verified',
                    'corrective_action_required' => 'CAP Required',
                ])->default('approved')->required(),
                DatePicker::make('reassessment_due_date'),
                Textarea::make('corrective_action_plan')->label('Corrective Action Plan (CAP)')->columnSpanFull(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('supplier.name')->label('Supplier')->searchable()->sortable()->weight('bold'),
                TextColumn::make('questionnaire_name')->limit(30),
                TextColumn::make('score_percentage')->label('Score')->suffix('%')->sortable(),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'approved' => 'success',
                    'corrective_action_required' => 'danger',
                    'submitted', 'reviewed' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('reassessment_due_date')->date()->sortable(),

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
            'index' => Pages\ListSupplierAssessments::route('/'),
            'create' => Pages\CreateSupplierAssessment::route('/create'),
            'edit' => Pages\EditSupplierAssessment::route('/{record}/edit'),
        ];
    }
}
