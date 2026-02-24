<?php

namespace Tests\Unit\News;

use App\Models\News;
use App\Support\News\NewsListQueryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsListQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_can_be_sorted_by_multiple_columns(): void
    {
        $service = app(NewsListQueryService::class);

        $draftBanana = News::factory()->create([
            'status' => 'draft',
            'title' => 'Banana',
            'published_at' => now()->subDay(),
        ]);

        $draftApple = News::factory()->create([
            'status' => 'draft',
            'title' => 'Apple',
            'published_at' => now()->subDays(2),
        ]);

        $archivedZebra = News::factory()->create([
            'status' => 'archived',
            'title' => 'Zebra',
            'published_at' => now()->subDays(3),
        ]);

        $archivedAlpha = News::factory()->create([
            'status' => 'archived',
            'title' => 'Alpha',
            'published_at' => now()->subDays(4),
        ]);

        $paginator = $service->paginate(
            search: '',
            rawSorts: ['status:asc', 'title:desc'],
            allowedSortColumns: ['id', 'title', 'status', 'published_at', 'author_id', 'views_count'],
            searchColumns: ['title', 'slug', 'preview', 'content'],
            perPage: 10,
        );

        $sortedIds = collect($paginator->items())->pluck('id')->all();

        $this->assertSame([
            $archivedZebra->id,
            $archivedAlpha->id,
            $draftBanana->id,
            $draftApple->id,
        ], $sortedIds);
    }

    public function test_invalid_sort_entries_are_ignored(): void
    {
        $service = app(NewsListQueryService::class);

        $alphaOlder = News::factory()->create([
            'title' => 'Alpha',
            'published_at' => now()->subDays(3),
        ]);

        $alphaNewer = News::factory()->create([
            'title' => 'Alpha',
            'published_at' => now()->subDays(2),
        ]);

        $beta = News::factory()->create([
            'title' => 'Beta',
            'published_at' => now()->subDay(),
        ]);

        $paginator = $service->paginate(
            search: '',
            rawSorts: [
                'unknown:asc',
                'title:bad',
                'title:asc',
                'title:desc',
                'id:desc',
            ],
            allowedSortColumns: ['id', 'title', 'status', 'published_at', 'author_id', 'views_count'],
            searchColumns: ['title', 'slug', 'preview', 'content'],
            perPage: 10,
        );

        $sortedIds = collect($paginator->items())->pluck('id')->all();

        $this->assertSame([
            $alphaNewer->id,
            $alphaOlder->id,
            $beta->id,
        ], $sortedIds);
    }

    public function test_default_sort_is_used_when_sort_query_is_empty(): void
    {
        $service = app(NewsListQueryService::class);

        $oldest = News::factory()->create([
            'published_at' => now()->subDays(5),
        ]);

        $middle = News::factory()->create([
            'published_at' => now()->subDays(3),
        ]);

        $latest = News::factory()->create([
            'published_at' => now()->subDay(),
        ]);

        $paginator = $service->paginate(
            search: '',
            rawSorts: [],
            allowedSortColumns: ['id', 'title', 'status', 'published_at', 'author_id', 'views_count'],
            searchColumns: ['title', 'slug', 'preview', 'content'],
            perPage: 10,
        );

        $sortedIds = collect($paginator->items())->pluck('id')->all();

        $this->assertSame([
            $latest->id,
            $middle->id,
            $oldest->id,
        ], $sortedIds);
    }
}

