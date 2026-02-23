<?php

namespace Tests\Feature\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsSortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_can_be_sorted_by_multiple_columns(): void
    {
        $user = User::factory()->create();

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

        $response = $this
            ->actingAs($user)
            ->get(route('news.index', [
                'sort' => ['status:asc', 'title:desc'],
            ]));

        $response->assertOk();

        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = $response->viewData('news');
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
        $user = User::factory()->create();

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

        $response = $this
            ->actingAs($user)
            ->get(route('news.index', [
                'sort' => [
                    'unknown:asc',
                    'title:bad',
                    'title:asc',
                    'title:desc',
                    'id:desc',
                ],
            ]));

        $response->assertOk();

        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = $response->viewData('news');
        $sortedIds = collect($paginator->items())->pluck('id')->all();

        $this->assertSame([
            $alphaNewer->id,
            $alphaOlder->id,
            $beta->id,
        ], $sortedIds);
    }

    public function test_default_sort_is_used_when_sort_query_is_empty(): void
    {
        $user = User::factory()->create();

        $oldest = News::factory()->create([
            'published_at' => now()->subDays(5),
        ]);

        $middle = News::factory()->create([
            'published_at' => now()->subDays(3),
        ]);

        $latest = News::factory()->create([
            'published_at' => now()->subDay(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('news.index'));

        $response->assertOk();

        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = $response->viewData('news');
        $sortedIds = collect($paginator->items())->pluck('id')->all();

        $this->assertSame([
            $latest->id,
            $middle->id,
            $oldest->id,
        ], $sortedIds);
    }
}
