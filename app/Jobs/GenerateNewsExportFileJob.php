<?php

namespace App\Jobs;

use App\Models\News;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateNewsExportFileJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $offset,
        public int $limit,
        public string $fileName,
        public int $chunkNumber,
    ) {
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $newsItems = News::query()
            ->orderBy('id')
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();

        if ($newsItems->isEmpty()) {
            return;
        }

        $chunkPath = sprintf(
            'exports/news/chunks/%s_%d.csv',
            $this->fileName,
            $this->chunkNumber,
        );

        $stream = fopen('php://temp', 'r+');

        fputcsv($stream, ['id', 'title', 'slug', 'status', 'published_at', 'created_at']);

        foreach ($newsItems as $newsItem) {
            fputcsv($stream, [
                $newsItem->id,
                $newsItem->title,
                $newsItem->slug,
                $newsItem->status,
                optional($newsItem->published_at)?->toDateTimeString(),
                optional($newsItem->created_at)?->toDateTimeString(),
            ]);
        }

        rewind($stream);

        $csvContent = stream_get_contents($stream);
        fclose($stream);

        Storage::disk('s3')->put($chunkPath, (string) $csvContent);
    }
}
