<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplianceObligationResource\Pages;
use App\Models\ComplianceObligation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComplianceObligationResource extends BaseModuleResource
{
    protected static ?string $model = ComplianceObligation::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';

    protected static \UnitEnum|string|null $navigationGroup = 'Governance (G)';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Compliance & Permits';

    protected static ?string $moduleSlug = 'compliance_management';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Statutory Permit & Obligation')->schema([
                Select::make('organization_id')->relationship('organization', 'name')->required()->searchable(),
                Select::make('branch_id')->relationship('branch', 'name')->searchable(),
                TextInput::make('title')->label('Permit / License Title')->required(),
                TextInput::make('regulatory_authority')->label('Regulatory Body (e.g. DoE, Fire Service, OSHA)')->required(),
                TextInput::make('permit_number')->label('License / Clearance #'),
                DatePicker::make('issue_date'),
                DatePicker::make('expiry_date')->required(),
                Select::make('status')->options([
                    'active' => 'Active & Valid',
                    'expiring_soon' => 'Expiring within 60 Days',
                    'expired' => 'Expired (Compliance Breach)',
                    'renewing' => 'Renewal in Progress',
                ])->default('active')->required(),
                TextInput::make('fine_penalty_cents')->label('Penalty / Fine Paid (Cents)')->numeric()->default(0),
            ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')->searchable()->sortable()->weight('bold'),
                TextColumn::make('regulatory_authority')->sortable(),
                TextColumn::make('branch.name')->label('Facility'),
                TextColumn::make('expiry_date')->date()->sortable(),
                TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'active' => 'success',
                    'renewing' => 'info',
                    'expiring_soon' => 'warning',
                    'expired' => 'danger',
                    default => 'gray',
                }),
                TextColumn::make('fine_penalty_cents')->label('Fines')->formatStateUsing(fn ($state) => $state > 0 ? '$'.number_format($state / 100, 2) : 'None'),

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
            'index' => Pages\ListComplianceObligations::route('/'),
            'create' => Pages\CreateComplianceObligation::route('/create'),
            'edit' => Pages\EditComplianceObligation::route('/{record}/edit'),
        ];
    }
}
