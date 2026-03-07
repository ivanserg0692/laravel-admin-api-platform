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
            self::Queued => 'Queued',
            self::InProgress => 'In progress',
            self::RunningWithErrors => 'Running with errors',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
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
