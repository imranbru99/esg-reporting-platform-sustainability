<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends BaseModuleResource
{
    protected static ?string $model = User::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static \UnitEnum|string|null $navigationGroup = 'Platform & Tenancy';

    protected static ?int $navigationSort = 4;

    protected static ?string $moduleSlug = 'users_roles';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('User Account & Security')->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => ! empty($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Toggle::make('is_active')->label('Account Active')->default(true),
            ])->columns(2),

            Section::make('Organization Assignment')->schema([
                Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->searchable(),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->label('Assigned Site / Facility'),
                TextInput::make('job_title')->maxLength(100),
                TextInput::make('phone')->tel()->maxLength(30),
                Select::make('locale')->options([
                    'en' => 'English',
                    'bn' => 'বাংলা (Bengali)',
                    'ar' => 'العربية (Arabic)',
                    'es' => 'Español',
                    'fr' => 'Français',
                    'zh' => '中文 (Chinese)',
                    'hi' => 'हिन्दी (Hindi)',
                ])->default('en'),
                Select::make('timezone')->options([
                    'Asia/Dhaka' => 'Asia/Dhaka (UTC+6)',
                    'UTC' => 'UTC',
                    'America/New_York' => 'America/New_York',
                    'Europe/London' => 'Europe/London',
                    'Asia/Dubai' => 'Asia/Dubai',
                ])->default('UTC'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('roles.name')->badge()->color('emerald'),
                TextColumn::make('organization.name')->sortable()->limit(25),
                TextColumn::make('branch.name')->label('Site')->limit(20),
                TextColumn::make('job_title'),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('last_login_at')->dateTime()->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')->relationship('roles', 'name'),
                Tables\Filters\TernaryFilter::make('is_active'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
