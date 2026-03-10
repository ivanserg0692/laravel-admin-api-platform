<?php

return [
    'navigation' => [
        'news_group' => 'News',
    ],
    'resources' => [
        'news' => [
            'navigation_label' => 'News',
        ],
        'news_exports' => [
            'navigation_label' => 'News exports',
        ],
    ],
    'actions' => [
        'export_news' => 'Start export',
        'export_started' => 'Export started',
        'download_export' => 'Download',
    ],
    'news_exports' => [
        'progress' => 'Progress',
        'version' => 'Version',
        'latest_version' => 'latest',
        'unknown_version_date' => 'Unknown date',
        'statuses' => [
            'queued' => 'Queued',
            'in_progress' => 'In progress',
            'running_with_errors' => 'Running with errors',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
        ],
    ],
];
