<?php

namespace App\Filament\Resources\NewsExports\Pages;

use App\Filament\Resources\NewsExports\NewsExportResource;
use App\Jobs\FinalizeNewsExportFileJob;
use App\Jobs\GenerateNewsExportFileJob;
use App\Models\News;
use App\Models\NewsExport;
use Illuminate\Bus\Batch;
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
                    $fileName = sprintf('news_export_%s', now()->format('Ymd')) . '.csv';
                    $exportFilePath = sprintf('exports/news/%s', $fileName);

                    Storage::disk('local')->makeDirectory('exports/news/chunks');

                    $jobs = [];
                    $chunkNumber = 0;

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
                        ->then(function (Batch $batch) use ($fileName, $chunkNumber): void {
                            FinalizeNewsExportFileJob::dispatch(
                                fileName: $fileName,
                                totalChunks: $chunkNumber,
                                batchId: $batch->id,
                            );
                        })
                        ->dispatch();

                    NewsExport::query()->create([
                        'export_file' => $exportFilePath,
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
