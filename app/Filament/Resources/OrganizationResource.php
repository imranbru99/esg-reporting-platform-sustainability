<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizationResource extends BaseModuleResource
{
    protected static ?string $model = Organization::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static \UnitEnum|string|null $navigationGroup = 'Platform & Tenancy';

    protected static ?int $navigationSort = 1;

    protected static ?string $moduleSlug = 'tenant_management';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Organization Profile')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('legal_name')->maxLength(255),
                TextInput::make('tax_id')->label('Tax Identification / BIN / EIN'),
                TextInput::make('domain')->label('Custom Domain')->unique(ignoreRecord: true),
                Select::make('industry')->options([
                    'textiles_apparel' => 'Textiles & Apparel (Garment)',
                    'banking_finance' => 'Banking & Financial Institutions',
                    'manufacturing' => 'Heavy Manufacturing & Engineering',
                    'technology' => 'Technology & IT Services',
                    'real_estate' => 'Real Estate & Construction',
                    'retail' => 'Retail & E-Commerce',
                    'agriculture' => 'Agriculture & Agro-Processing',
                    'energy' => 'Energy, Oil, Gas & Utilities',
                    'ngo' => 'NGO & Non-Profit',
                    'government' => 'Government & Public Sector',
                    'general' => 'General Enterprise',
                ])->required()->searchable(),
                Select::make('consolidation_method')->options([
                    'operational_control' => 'Operational Control (100% GHG accountability)',
                    'financial_control' => 'Financial Control',
                    'equity_share' => 'Equity Share (Proportional ownership)',
                ])->required(),
            ])->columns(2),

            Section::make('Localization & Branding')->schema([
                Select::make('country_code')->options([
                    'BGD' => 'Bangladesh (BGD)',
                    'USA' => 'United States (USA)',
                    'GBR' => 'United Kingdom (GBR)',
                    'DEU' => 'Germany (DEU)',
                    'IND' => 'India (IND)',
                    'ARE' => 'United Arab Emirates (ARE)',
                    'SAU' => 'Saudi Arabia (SAU)',
                    'CHN' => 'China (CHN)',
                    'FRA' => 'France (FRA)',
                    'ESP' => 'Spain (ESP)',
                ])->default('BGD')->required()->searchable(),
                Select::make('currency_code')->options([
                    'BDT' => 'BDT (৳ - Bangladeshi Taka)',
                    'USD' => 'USD ($ - US Dollar)',
                    'EUR' => 'EUR (€ - Euro)',
                    'GBP' => 'GBP (£ - British Pound)',
                    'SAR' => 'SAR (ر.س - Saudi Riyal)',
                    'INR' => 'INR (₹ - Indian Rupee)',
                ])->default('USD')->required(),
                Select::make('locale')->options([
                    'en' => 'English (en)',
                    'bn' => 'বাংলা / Bengali (bn)',
                    'ar' => 'العربية / Arabic (ar)',
                    'es' => 'Español (es)',
                    'fr' => 'Français (fr)',
                    'zh' => '中文 / Chinese (zh)',
                    'hi' => 'हिन्दी / Hindi (hi)',
                ])->default('en')->required(),
                Select::make('timezone')->options([
                    'Asia/Dhaka' => 'Asia/Dhaka (UTC+6)',
                    'UTC' => 'UTC',
                    'America/New_York' => 'America/New_York (EST)',
                    'Europe/London' => 'Europe/London (GMT/BST)',
                    'Asia/Dubai' => 'Asia/Dubai (GST)',
                    'Asia/Kolkata' => 'Asia/Kolkata (IST)',
                ])->default('UTC')->required()->searchable(),
                ColorPicker::make('brand_color')->default('#059669'),
                Select::make('status')->options([
                    'active' => 'Active',
                    'trial' => 'Trial Period',
                    'suspended' => 'Suspended',
                ])->default('active')->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('industry')->badge()->sortable(),
                TextColumn::make('country_code')->label('Country')->sortable(),
                TextColumn::make('currency_code')->label('Currency'),
                TextColumn::make('consolidation_method')->label('Consolidation')->limit(20),
                ColorColumn::make('brand_color')->label('Theme'),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'active' => 'success',
                    'trial' => 'warning',
                    'suspended' => 'danger',
                    default => 'gray',
                }),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'trial' => 'Trial',
                    'suspended' => 'Suspended',
                ]),
                Tables\Filters\SelectFilter::make('industry'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
