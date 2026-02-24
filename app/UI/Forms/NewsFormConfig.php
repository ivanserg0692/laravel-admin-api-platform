<?php

namespace App\UI\Forms;

use App\Models\News;
use App\UI\Forms\DTO\FormFieldDto;
use Illuminate\Support\Carbon;

class NewsFormConfig
{
    public function fields(): array
    {
        return [
            new FormFieldDto(name: 'title', label: __('news.labels.title'), placeholder: __('news.placeholders.title'), required: true),
            new FormFieldDto(name: 'slug', label: __('news.labels.slug'), placeholder: __('news.placeholders.slug'), required: true),
            new FormFieldDto(
                name: 'status',
                label: __('news.labels.status'),
                type: 'select',
                options: [
                    'draft' => __('news.labels.status_options.draft'),
                    'published' => __('news.labels.status_options.published'),
                    'archived' => __('news.labels.status_options.archived'),
                ],
                required: true
            ),
            new FormFieldDto(name: 'published_at', label: __('news.labels.published_at'), type: 'datetime-local'),
            new FormFieldDto(name: 'preview', label: __('news.labels.preview'), type: 'textarea', rows: 3, fullWidth: true),
            new FormFieldDto(name: 'content', label: __('news.labels.content'), type: 'textarea', rows: 7, required: true, fullWidth: true),
            new FormFieldDto(name: 'cover_image', label: __('news.labels.cover_image'), type: 'url', placeholder: __('news.placeholders.cover_image'), fullWidth: true),
            new FormFieldDto(name: 'meta_title', label: __('news.labels.meta_title'), placeholder: __('news.placeholders.meta_title')),
            new FormFieldDto(name: 'meta_description', label: __('news.labels.meta_description'), placeholder: __('news.placeholders.meta_description')),
            new FormFieldDto(name: 'sort_order', label: __('news.labels.sort_order'), type: 'number', value: 0),
        ];
    }

    public function createValues(): array
    {
        return [
            'status' => 'draft',
            'sort_order' => 0,
        ];
    }

    public function updateValues(News $news): array
    {
        return [
            'title' => data_get($news, 'title'),
            'slug' => data_get($news, 'slug'),
            'status' => data_get($news, 'status'),
            'published_at' => data_get($news, 'published_at') ? Carbon::parse(data_get($news, 'published_at'))->format('Y-m-d\TH:i') : null,
            'preview' => data_get($news, 'preview'),
            'content' => data_get($news, 'content'),
            'cover_image' => data_get($news, 'cover_image'),
            'meta_title' => data_get($news, 'meta_title'),
            'meta_description' => data_get($news, 'meta_description'),
            'sort_order' => data_get($news, 'sort_order'),
        ];
    }

    public function modalUpdateValues(): array
    {
        return [
            'title' => 'The 4th Digital Transformation',
            'slug' => 'the-4th-digital-transformation',
            'status' => 'published',
            'published_at' => '2026-01-02T12:00',
            'preview' => 'USA enterprises and governments have committed to a technology-driven future.',
            'content' => 'USA enterprises and governments have committed to a technology-driven future, making USA one of the fastest-growing markets for digital technologies.',
            'cover_image' => 'https://images.example.com/news/cover.jpg',
            'meta_title' => 'Digital Transformation',
            'meta_description' => 'News about digital transformation and cybersecurity trends.',
            'sort_order' => 10,
        ];
    }
}
