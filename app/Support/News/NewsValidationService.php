<?php

namespace App\Support\News;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class NewsValidationService
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(string $valueRoot, ?int $ignoreNewsId = null): array
    {
        $slugRule = Rule::unique('news', 'slug');
        if ($ignoreNewsId !== null) {
            $slugRule = $slugRule->ignore($ignoreNewsId);
        }

        return [
            $valueRoot . '.title' => ['required', 'string', 'max:255'],
            $valueRoot . '.slug' => ['required', 'string', 'max:255', $slugRule],
            $valueRoot . '.status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            $valueRoot . '.published_at' => ['nullable', 'date'],
            $valueRoot . '.preview' => ['nullable', 'string'],
            $valueRoot . '.content' => ['required', 'string'],
            $valueRoot . '.cover_image' => ['nullable', 'url', 'max:2048'],
            $valueRoot . '.meta_title' => ['nullable', 'string', 'max:255'],
            $valueRoot . '.meta_description' => ['nullable', 'string', 'max:255'],
            $valueRoot . '.sort_order' => ['nullable', 'integer'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(string $valueRoot): array
    {
        return [
            $valueRoot . '.title' => __('news.labels.title'),
            $valueRoot . '.slug' => __('news.labels.slug'),
            $valueRoot . '.status' => __('news.labels.status'),
            $valueRoot . '.published_at' => __('news.labels.published_at'),
            $valueRoot . '.preview' => __('news.labels.preview'),
            $valueRoot . '.content' => __('news.labels.content'),
            $valueRoot . '.cover_image' => __('news.labels.cover_image'),
            $valueRoot . '.meta_title' => __('news.labels.meta_title'),
            $valueRoot . '.meta_description' => __('news.labels.meta_description'),
            $valueRoot . '.sort_order' => __('news.labels.sort_order'),
        ];
    }

    public function normalizePublishedAt(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }
}
