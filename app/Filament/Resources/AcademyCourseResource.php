<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademyCourseResource\Pages;
use App\Models\AcademyCourse;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AcademyCourseResource extends BaseModuleResource
{
    protected static ?string $model = AcademyCourse::class;

    protected static ?string $moduleSlug = 'academy_courses';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static \UnitEnum|string|null $navigationGroup = 'Commercial & Operations';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('slug'),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Select::make('level')
                ->options([
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                ])
                ->default('beginner')
                ->required(),
            Forms\Components\TextInput::make('duration_hours')->numeric()->default(2)->required(),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('level')->badge(),
                Tables\Columns\TextColumn::make('duration_hours')->label('Duration (hrs)'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('certificates_count')->counts('certificates')->label('Issued Certs'),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademyCourses::route('/'),
            'create' => Pages\CreateAcademyCourse::route('/create'),
            'edit' => Pages\EditAcademyCourse::route('/{record}/edit'),
        ];
    }
}
