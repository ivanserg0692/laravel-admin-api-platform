<?php

namespace App\Filament\Resources\NewsExports\Tables;

use App\Models\NewsExport;
use App\Support\News\NewsExportProgressStatus;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('download')
                    ->label(__('filament.actions.download_export'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (NewsExport $record): bool => $record->progress_status === NewsExportProgressStatus::Completed
                        && filled($record->export_file)
                        && Storage::disk('local')->exists($record->export_file))
                    ->action(fn (NewsExport $record) => response()->download(
                        Storage::disk('local')->path($record->export_file),
                        basename($record->export_file),
                    )),
                ViewAction::make(),
            ]);
    }
}
