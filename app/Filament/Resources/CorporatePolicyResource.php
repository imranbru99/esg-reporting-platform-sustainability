<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CorporatePolicyResource\Pages;
use App\Models\CorporatePolicy;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CorporatePolicyResource extends BaseModuleResource
{
    protected static ?string $model = CorporatePolicy::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static \UnitEnum|string|null $navigationGroup = 'Governance (G)';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Policies & Code of Conduct';

    protected static ?string $moduleSlug = 'policies_conduct';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Corporate Policy Library')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('title')->label('Policy Title')->required(),
                Select::make('policy_type')->options([
                    'code_of_conduct' => 'Code of Conduct & Business Ethics',
                    'anti_bribery' => 'Anti-Bribery & Corruption Policy',
                    'environmental' => 'Environmental & Climate Policy',
                    'whistleblower' => 'Whistleblower Protection Policy',
                    'human_rights' => 'Human Rights & Fair Labor Policy',
                    'data_privacy' => 'Data Privacy & Information Security Policy',
                ])->required(),
                TextInput::make('version')->default('1.0')->required(),
                DatePicker::make('effective_date')->required(),
                DatePicker::make('next_review_date'),
                TextInput::make('document_url')->label('PDF Document URL / Storage Link'),
                Toggle::make('mandatory_acknowledgment')->label('Mandatory Annual Employee Sign-off')->default(true),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->searchable()->sortable()->weight('bold'),
                TextColumn::make('policy_type')->badge()->color('info'),
                TextColumn::make('version')->badge()->color('gray'),
                TextColumn::make('effective_date')->date()->sortable(),
                IconColumn::make('mandatory_acknowledgment')->boolean()->label('Mandatory Sign-off'),
                TextColumn::make('acknowledgments_count')->counts('acknowledgments')->label('Signed'),

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
            'index' => Pages\ListCorporatePolicys::route('/'),
            'create' => Pages\CreateCorporatePolicy::route('/create'),
            'edit' => Pages\EditCorporatePolicy::route('/{record}/edit'),
        ];
    }
}
