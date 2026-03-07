<?php

namespace App\Jobs;

use App\Models\NewsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FinalizeNewsExportFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $fileName,
        public int    $totalChunks,
        public string $batchId,
    )
    {
    }

    public function handle(): void
    {
        $disk = Storage::disk('local');
        $finalPath = sprintf('exports/news/%s', $this->fileName);

        $disk->makeDirectory('exports/news');

        $finalStream = fopen($disk->path($finalPath), 'w');

        if ($finalStream === false) {
            throw new RuntimeException(sprintf('Unable to open export file [%s] for writing.', $finalPath));
        }

        try {
            for ($chunkNumber = 1; $chunkNumber <= $this->totalChunks; $chunkNumber++) {
                $chunkPath = sprintf('exports/news/chunks/%s_%d.csv', $this->fileName, $chunkNumber);

                if (!$disk->exists($chunkPath)) {
                    throw new RuntimeException(sprintf('Export chunk [%s] not found.', $chunkPath));
                }

                $chunkStream = $disk->readStream($chunkPath);

                if ($chunkStream === false) {
                    throw new RuntimeException(sprintf('Unable to read export chunk [%s].', $chunkPath));
                }

                try {
                    if ($chunkNumber > 1) {
                        fgets($chunkStream);
                    }

                    stream_copy_to_stream($chunkStream, $finalStream);
                } finally {
                    fclose($chunkStream);
                }
            }
        } finally {
            fclose($finalStream);
        }

        NewsExport::query()
            ->where('job_batch_id', $this->batchId)
            ->update([
                'export_file' => $finalPath,
                'is_completed' => true,
            ]);
    }
}
