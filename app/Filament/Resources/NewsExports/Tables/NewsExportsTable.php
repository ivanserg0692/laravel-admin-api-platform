<?php

namespace App\Filament\Resources\NewsExports\Tables;

use App\Models\NewsExport;
use App\Support\News\NewsExportProgressStatus;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class NewsExportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('export_file')
                    ->searchable(),
                TextColumn::make('progress_percent')
                    ->label(__('filament.news_exports.progress'))
                    ->state(fn(NewsExport $record): int => $record->progress_percent)
                    ->formatStateUsing(function (int $state, NewsExport $record): string {
                        $progressStatus = $record->progress_status;

                        return sprintf(
                            '<div class="min-w-40"><div class="mb-1 flex items-center justify-between gap-2 text-xs"><span>%s</span> <span>%d%%</span></div><div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700"><div class="h-2 rounded-full %s" style="width: %d%%"></div></div></div>',
                            e($progressStatus->label()),
                            $state,
                            $progressStatus->barColorClass(),
                            $state,
                        );
                    })
                    ->html(),
                TextColumn::make('jobBatch.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('download')
                        ->label(__('filament.actions.download_export'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn (NewsExport $record): bool => $record->progress_status === NewsExportProgressStatus::Completed
                            && filled($record->export_file)
                            && Storage::disk('s3')->exists($record->export_file))
                        ->schema([
                            Select::make('version_id')
                                ->label(__('filament.news_exports.version'))
                                ->options(fn (NewsExport $record): array => $record->exportVersionOptions())
                                ->default(fn (NewsExport $record): ?string => $record->latestExportVersionId())
                                ->required()
                                ->native(false),
                        ])
                        ->action(function (array $data, NewsExport $record) {
                            $stream = $record->openExportVersionStream($data['version_id']);

                            if (! is_resource($stream)) {
                                abort(404);
                            }

                            return response()->streamDownload(function () use ($stream): void {
                                fpassthru($stream);
                                fclose($stream);
                            }, basename($record->export_file));
                        }),
                    ViewAction::make(),
                ]),
            ]);
    }
}
