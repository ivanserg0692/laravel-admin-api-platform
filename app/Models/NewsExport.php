<?php

namespace App\Models;

use Aws\S3\S3Client;
use App\Support\News\NewsExportProgressStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsExport extends Model
{
    protected ?array $cachedExportVersions = null;

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

    public function exportVersionOptions(): array
    {
        $options = [];

        foreach ($this->exportVersions() as $version) {
            $options[$version['version_id']] = $version['label'];
        }

        return $options;
    }

    public function latestExportVersionId(): ?string
    {
        return $this->exportVersions()[0]['version_id'] ?? null;
    }

    public function openExportVersionStream(string $versionId)
    {
        $result = static::makeS3Client()->getObject([
            'Bucket' => (string) config('filesystems.disks.s3.bucket'),
            'Key' => $this->export_file,
            'VersionId' => $versionId,
        ]);

        return $result->get('Body')?->detach();
    }

    public function exportVersions(): array
    {
        if ($this->cachedExportVersions !== null) {
            return $this->cachedExportVersions;
        }

        if (blank($this->export_file)) {
            return $this->cachedExportVersions = [];
        }

        $result = static::makeS3Client()->listObjectVersions([
            'Bucket' => (string) config('filesystems.disks.s3.bucket'),
            'Prefix' => $this->export_file,
        ]);

        $versions = collect($result->get('Versions') ?? [])
            ->filter(fn (array $version): bool => ($version['Key'] ?? null) === $this->export_file)
            ->sortByDesc(fn (array $version): string => (string) ($version['LastModified'] ?? ''))
            ->values()
            ->map(function (array $version): array {
                $lastModified = $version['LastModified'] ?? null;

                return [
                    'version_id' => (string) $version['VersionId'],
                    'label' => sprintf(
                        '%s%s%s',
                        $lastModified instanceof \DateTimeInterface ? $lastModified->format('Y-m-d H:i:s') : __('filament.news_exports.unknown_version_date'),
                        ! empty($version['IsLatest']) ? ' [' . __('filament.news_exports.latest_version') . ']' : '',
                        isset($version['Size']) ? sprintf(' (%d B)', $version['Size']) : '',
                    ),
                ];
            })
            ->all();

        return $this->cachedExportVersions = $versions;
    }

    protected static function makeS3Client(): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region' => (string) config('filesystems.disks.s3.region'),
            'endpoint' => config('filesystems.disks.s3.endpoint'),
            'use_path_style_endpoint' => (bool) config('filesystems.disks.s3.use_path_style_endpoint'),
            'credentials' => [
                'key' => (string) config('filesystems.disks.s3.key'),
                'secret' => (string) config('filesystems.disks.s3.secret'),
            ],
        ]);
    }
}
