<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternalControlResource\Pages;
use App\Models\InternalControl;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InternalControlResource extends BaseModuleResource
{
    protected static ?string $model = InternalControl::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-check-badge';

    protected static \UnitEnum|string|null $navigationGroup = 'Governance (G)';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Internal Controls (ESG Data)';

    protected static ?string $moduleSlug = 'internal_controls';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('ESG Data Control Objective')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                TextInput::make('control_code')->label('Control ID (e.g. CTRL-GHG-01)')->required(),
                TextInput::make('title')->label('Control Activity')->required(),
                Select::make('esg_pillar')->options([
                    'environmental' => 'Environmental Data Controls',
                    'social' => 'Social & HR Data Controls',
                    'governance' => 'Governance & Ethics Controls',
                ])->required(),
                Select::make('frequency')->options([
                    'monthly' => 'Monthly Testing',
                    'quarterly' => 'Quarterly Sign-off',
                    'annual' => 'Annual Pre-Audit Sign-off',
                ])->default('quarterly')->required(),
                Select::make('test_status')->options([
                    'effective' => 'Effective (No Deficiencies)',
                    'deficient' => 'Deficiency Identified',
                    'not_tested' => 'Not Yet Tested',
                ])->default('effective')->required(),
                Select::make('sign_off_user_id')->relationship('signOffUser', 'name')->label('Control Owner Sign-off'),
                DatePicker::make('last_tested_date'),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('control_code')->badge()->color('gray')->weight('bold'),
                TextColumn::make('title')->searchable()->sortable()->limit(35),
                TextColumn::make('esg_pillar')->badge()->color('emerald'),
                TextColumn::make('frequency'),
                TextColumn::make('test_status')->badge()->color(fn ($state) => $state === 'effective' ? 'success' : 'danger'),
                TextColumn::make('signOffUser.name')->label('Sign-off Owner'),
                TextColumn::make('last_tested_date')->date(),

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
            'index' => Pages\ListInternalControls::route('/'),
            'create' => Pages\CreateInternalControl::route('/create'),
            'edit' => Pages\EditInternalControl::route('/{record}/edit'),
        ];
    }
}
