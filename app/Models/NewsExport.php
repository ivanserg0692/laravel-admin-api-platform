<?php

namespace App\Models;

use App\Support\News\NewsExportProgressStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsExport extends Model
{
    protected $fillable = [
        'export_file',
        'job_batch_id',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'bool',
    ];

    public function jobBatch(): BelongsTo
    {
        return $this->belongsTo(JobBatch::class, 'job_batch_id');
    }

    public function getProgressPercentAttribute(): int
    {
        $jobBatch = $this->jobBatch;

        if ($this->is_completed) {
            return 100;
        }

        if ($jobBatch === null || $jobBatch->total_jobs <= 0) {
            return 0;
        }

        if ($jobBatch->finished_at !== null) {
            return 100;
        }

        $processedJobs = max(0, $jobBatch->total_jobs - $jobBatch->pending_jobs);

        return (int) round(($processedJobs / $jobBatch->total_jobs) * 100);
    }

    public function getProgressStatusAttribute(): NewsExportProgressStatus
    {
        $jobBatch = $this->jobBatch;

        if ($this->is_completed || $jobBatch?->finished_at !== null) {
            return NewsExportProgressStatus::Completed;
        }

        if ($jobBatch?->cancelled_at !== null) {
            return NewsExportProgressStatus::Cancelled;
        }

        if (($jobBatch?->failed_jobs ?? 0) > 0 && ($jobBatch?->pending_jobs ?? 0) > 0) {
            return NewsExportProgressStatus::RunningWithErrors;
        }

        if (($jobBatch?->pending_jobs ?? 0) > 0) {
            return NewsExportProgressStatus::InProgress;
        }

        return NewsExportProgressStatus::Queued;
    }
}
