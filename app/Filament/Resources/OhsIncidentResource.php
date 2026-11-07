<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OhsIncidentResource\Pages;
use App\Models\OhsIncident;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OhsIncidentResource extends BaseModuleResource
{
    protected static ?string $model = OhsIncident::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static \UnitEnum|string|null $navigationGroup = 'Social (S)';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Health & Safety (OHS)';

    protected static ?string $moduleSlug = 'health_safety';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Incident Record & Investigation')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('branch_id')->relationship('branch', 'name')->searchable(),
                TextInput::make('incident_number')->required(),
                DatePicker::make('incident_date')->required(),
                Select::make('incident_type')->options([
                    'near_miss' => 'Near Miss (Proactive hazard identified)',
                    'first_aid' => 'First Aid Case',
                    'lost_time' => 'Lost Time Injury (LTI)',
                    'medical_treatment' => 'Medical Treatment Case',
                    'fatality' => 'Fatality',
                ])->required(),
                Select::make('severity')->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'critical' => 'Critical',
                ])->required(),
                TextInput::make('lost_work_days')->numeric()->default(0),
                Select::make('status')->options([
                    'open' => 'Open',
                    'investigating' => 'Under Investigation',
                    'resolved' => 'CAPA Implemented',
                    'closed' => 'Closed',
                ])->default('open')->required(),
                Textarea::make('description')->columnSpanFull()->required(),
                Textarea::make('root_cause')->columnSpanFull(),
                Textarea::make('corrective_action_plan')->label('Corrective & Preventive Actions (CAPA)')->columnSpanFull(),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('incident_number')->searchable()->sortable()->weight('bold'),
                TextColumn::make('incident_date')->date()->sortable(),
                TextColumn::make('branch.name')->label('Facility'),
                TextColumn::make('incident_type')->badge()->color(fn ($state) => match ($state) {
                    'fatality', 'lost_time' => 'danger',
                    'first_aid' => 'warning',
                    'near_miss' => 'info',
                    default => 'gray',
                }),
                TextColumn::make('severity')->badge(),
                TextColumn::make('lost_work_days')->label('Lost Days')->numeric()->sortable(),
                TextColumn::make('status')->badge()->color(fn ($state) => $state === 'closed' ? 'success' : 'warning'),

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
            'index' => Pages\ListOhsIncidents::route('/'),
            'create' => Pages\CreateOhsIncident::route('/create'),
            'edit' => Pages\EditOhsIncident::route('/{record}/edit'),
        ];
    }
}
