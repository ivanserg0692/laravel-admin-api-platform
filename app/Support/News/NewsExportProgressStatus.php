<?php

namespace App\Support\News;

enum NewsExportProgressStatus: string
{
    case Queued = 'queued';
    case InProgress = 'in_progress';
    case RunningWithErrors = 'running_with_errors';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Queued => __('filament.news_exports.statuses.queued'),
            self::InProgress => __('filament.news_exports.statuses.in_progress'),
            self::RunningWithErrors => __('filament.news_exports.statuses.running_with_errors'),
            self::Cancelled => __('filament.news_exports.statuses.cancelled'),
            self::Completed => __('filament.news_exports.statuses.completed'),
        };
    }

    public function barColorClass(): string
    {
        return match ($this) {
            self::Completed => 'bg-success-500',
            self::Cancelled => 'bg-gray-400',
            self::RunningWithErrors => 'bg-danger-500',
            self::Queued, self::InProgress => 'bg-primary-500',
        };
    }
}
