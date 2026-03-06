<?php

namespace App\Filament\Resources\NewsExports\Pages;

use App\Filament\Resources\NewsExports\NewsExportResource;
use App\Jobs\GenerateNewsExportFileJob;
use App\Models\News;
use App\Models\NewsExport;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class ListNewsExports extends ListRecords
{
    protected static string $resource = NewsExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportNews')
                ->label(__('filament.actions.export_news'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): void {
                    $chunkSize = 100;
                    $totalNewsCount = News::query()->count();
                    $fileName = sprintf('news_export_%s', now()->format('Ymd'));
                    $exportFilePrefix = sprintf('exports/news/chunks/%s', $fileName);

                    Storage::disk('local')->makeDirectory('exports/news/chunks');

                    $jobs = [];
                    $chunkNumber = 1;

                    for ($offset = 0; $offset < $totalNewsCount; $offset += $chunkSize) {
                        $jobs[] = new GenerateNewsExportFileJob(
                            offset: $offset,
                            limit: $chunkSize,
                            fileName: $fileName,
                            chunkNumber: $chunkNumber,
                        );

                        $chunkNumber++;
                    }

                    $batch = Bus::batch($jobs)
                        ->name(sprintf('news-export-%s', $fileName))
                        ->dispatch();

                    NewsExport::query()->create([
                        'export_file' => $exportFilePrefix,
                        'job_batch_id' => $batch->id,
                    ]);

                    Notification::make()
                        ->title(__('filament.actions.export_started'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
