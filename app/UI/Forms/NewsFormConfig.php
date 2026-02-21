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
            new FormFieldDto(name: 'title', label: 'Title', placeholder: 'Enter news title', required: true),
            new FormFieldDto(name: 'slug', label: 'Slug', placeholder: 'news-title-slug', required: true),
            new FormFieldDto(name: 'status', label: 'Status', type: 'select', options: ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'], required: true),
            new FormFieldDto(name: 'published_at', label: 'Published at', type: 'datetime-local'),
            new FormFieldDto(name: 'preview', label: 'Preview', type: 'textarea', rows: 3, fullWidth: true),
            new FormFieldDto(name: 'content', label: 'Content', type: 'textarea', rows: 7, required: true, fullWidth: true),
            new FormFieldDto(name: 'cover_image', label: 'Cover image URL', type: 'url', placeholder: 'https://example.com/cover.jpg', fullWidth: true),
            new FormFieldDto(name: 'meta_title', label: 'Meta title', placeholder: 'SEO title'),
            new FormFieldDto(name: 'meta_description', label: 'Meta description', placeholder: 'SEO description'),
            new FormFieldDto(name: 'sort_order', label: 'Sort order', type: 'number', value: 0),
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
