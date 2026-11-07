<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataCollectionRequestResource\Pages;
use App\Models\DataCollectionRequest;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DataCollectionRequestResource extends BaseModuleResource
{
    protected static ?string $model = DataCollectionRequest::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static \UnitEnum|string|null $navigationGroup = 'Data & Assurance';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Data Collection Hub';

    protected static ?string $moduleSlug = 'data_collection_hub';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Collection Schedule & Assignment')->schema([
                Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->required()
                    ->searchable(),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->label('Target Facility / Site')
                    ->searchable(),
                TextInput::make('title')->label('Collection Schedule Name')->required()->maxLength(255),
                TextInput::make('reporting_year')->label('Reporting Year')->default(date('Y'))->maxLength(4)->required(),
                Select::make('reporting_period')->options([
                    'annual' => 'Annual (Full Year)',
                    'Q1' => 'Q1 (Jan - Mar)',
                    'Q2' => 'Q2 (Apr - Jun)',
                    'Q3' => 'Q3 (Jul - Sep)',
                    'Q4' => 'Q4 (Oct - Dec)',
                    'monthly_01' => 'January',
                    'monthly_02' => 'February',
                    'monthly_03' => 'March',
                    'monthly_04' => 'April',
                    'monthly_05' => 'May',
                    'monthly_06' => 'June',
                    'monthly_07' => 'July',
                    'monthly_08' => 'August',
                    'monthly_09' => 'September',
                    'monthly_10' => 'October',
                    'monthly_11' => 'November',
                    'monthly_12' => 'December',
                ])->required(),
                DatePicker::make('due_date')->required(),
                Select::make('assigned_user_id')
                    ->relationship('assignedUser', 'name')
                    ->label('Assigned Data Contributor / Site Manager')
                    ->searchable(),
                Select::make('status')->options([
                    'draft' => 'Draft',
                    'open' => 'Open for Entry',
                    'submitted' => 'Submitted for Review',
                    'approved' => 'Approved & Verified',
                    'locked' => 'Locked for Auditing',
                ])->default('open')->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->weight('bold'),
                TextColumn::make('branch.name')->label('Site / Facility')->sortable(),
                TextColumn::make('reporting_year')->label('Year')->sortable(),
                TextColumn::make('reporting_period')->badge()->color('info'),
                TextColumn::make('assignedUser.name')->label('Assigned Contributor'),
                TextColumn::make('due_date')->date()->sortable(),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'draft' => 'gray',
                    'open' => 'info',
                    'submitted' => 'warning',
                    'approved' => 'success',
                    'locked' => 'primary',
                    default => 'gray',
                }),
                TextColumn::make('metric_entries_count')->counts('metricEntries')->label('Entries Logged'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('reporting_period'),
                Tables\Filters\SelectFilter::make('reporting_year'),
            ])
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
            'index' => Pages\ListDataCollectionRequests::route('/'),
            'create' => Pages\CreateDataCollectionRequest::route('/create'),
            'edit' => Pages\EditDataCollectionRequest::route('/{record}/edit'),
        ];
    }
}
