<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportProjectResource\Pages;
use App\Models\ReportProject;
use App\Services\Reporting\ReportGeneratorService;
use App\Services\Reporting\UnicodePdfExporter;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ReportProjectResource extends BaseModuleResource
{
    protected static ?string $model = ReportProject::class;

    protected static ?string $moduleSlug = 'report_builder';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static \UnitEnum|string|null $navigationGroup = 'Reporting Engine';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('reporting_period')
                ->label('Reporting Year')
                ->required()
                ->default(date('Y')),
            Forms\Components\Select::make('primary_framework')
                ->options([
                    'gri' => 'GRI Universal Standards',
                    'csrd_esrs' => 'CSRD / ESRS',
                    'issb' => 'ISSB (IFRS S1/S2)',
                    'tcfd' => 'TCFD Climate Disclosures',
                    'bangladesh_bank' => 'Bangladesh Bank & BSEC ESG Guidelines',
                    'brsr' => 'BRSR (India)',
                ])
                ->default('gri')
                ->required(),
            Forms\Components\Select::make('boundary_type')
                ->options([
                    'operational_control' => 'Operational Control',
                    'financial_control' => 'Financial Control',
                    'equity_share' => 'Equity Share',
                ])
                ->default('operational_control')
                ->required(),
            Forms\Components\TextInput::make('baseline_year')
                ->default('2022')
                ->required(),
            Forms\Components\Select::make('language')
                ->options([
                    'en' => 'English (International)',
                    'bn' => 'বাংলা (Bengali)',
                    'ar' => 'العربية (Arabic)',
                    'es' => 'Español (Spanish)',
                    'fr' => 'Français (French)',
                    'zh' => '中文 (Chinese)',
                ])
                ->default('en')
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'in_review' => 'In Review',
                    'approved' => 'Approved',
                    'published' => 'Published',
                    'locked' => 'Locked & Assured',
                ])
                ->default('draft')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('reporting_period')->sortable(),
                Tables\Columns\TextColumn::make('primary_framework')->badge(),
                Tables\Columns\TextColumn::make('language')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'locked' => 'danger',
                    'published' => 'success',
                    'approved' => 'info',
                    'in_review' => 'warning',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('sha256_hash')->label('SHA-256 Fingerprint')->limit(12)->copyable(),
            ])
            ->recordActions([
                Tables\Actions\Action::make('generate_ai_draft')
                    ->label('AI Auto-Draft')
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->action(function (ReportProject $record) {
                        $service = app(ReportGeneratorService::class);
                        $service->initializeOutline($record);
                        foreach ($record->sections as $section) {
                            $service->generateSectionNarrative($section);
                        }
                        Notification::make()
                            ->title('AI Report Sections Drafted')
                            ->body('All standard sections have been generated with verified metric citations.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('export_pdf')
                    ->label('Export Unicode PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function (ReportProject $record) {
                        $exporter = app(UnicodePdfExporter::class);
                        $path = $exporter->export($record);
                        Notification::make()
                            ->title('PDF Export Ready')
                            ->body("Generated via Unicode PDF at: {$path}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('lock_and_publish')
                    ->label('Lock & Fingerprint')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (ReportProject $record) {
                        $record->lockAndPublish();
                        Notification::make()
                            ->title('Report Locked')
                            ->body("SHA-256 hash calculated: {$record->sha256_hash}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReportProjects::route('/'),
            'create' => Pages\CreateReportProject::route('/create'),
            'edit' => Pages\EditReportProject::route('/{record}/edit'),
        ];
    }
}
