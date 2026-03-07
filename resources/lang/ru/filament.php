<?php

return [
    'navigation' => [
        'news_group' => 'Новости',
    ],
    'resources' => [
        'news' => [
            'navigation_label' => 'Новости',
        ],
        'news_exports' => [
            'navigation_label' => 'Экспорт новостей',
        ],
    ],
    'actions' => [
        'export_news' => 'Запустить экспорт',
        'export_started' => 'Экспорт запущен',
        'download_export' => 'Скачать',
    ],
    'news_exports' => [
        'progress' => 'Прогресс',
        'statuses' => [
            'queued' => 'В очереди',
            'in_progress' => 'В процессе',
            'running_with_errors' => 'В процессе с ошибками',
            'cancelled' => 'Отменен',
            'completed' => 'Завершен',
        ],
    ],
];
