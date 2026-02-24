<?php

namespace Tests\Feature\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsSortingHtmlTest extends TestCase
{
    use RefreshDatabase;

    public function test_sort_link_keeps_page_when_current_page_is_greater_than_one(): void
    {
        $user = User::factory()->create();
        News::factory()->count(15)->create();

        $response = $this
            ->actingAs($user)
            ->get(route('news.index', ['page' => 2]));

        $response->assertOk();

        $href = $this->extractIdSortHref($response->getContent());
        $query = $this->parseHrefQuery($href);

        $this->assertSame(['id:asc'], $query['sort'] ?? null);
        $this->assertSame('2', $query['page'] ?? null);
    }

    public function test_sort_link_does_not_include_page_when_current_page_is_first(): void
    {
        $user = User::factory()->create();
        News::factory()->count(15)->create();

        $response = $this
            ->actingAs($user)
            ->get(route('news.index'));

        $response->assertOk();

        $href = $this->extractIdSortHref($response->getContent());
        $query = $this->parseHrefQuery($href);

        $this->assertSame(['id:asc'], $query['sort'] ?? null);
        $this->assertArrayNotHasKey('page', $query);
    }

    private function extractIdSortHref(string $html): string
    {
        $matches = [];
        $found = preg_match('/<a href="([^"]*sort%5B0%5D=id%3Aasc[^"]*)"/', $html, $matches);

        $this->assertSame(1, $found, 'Expected to find id sort link in response HTML.');

        return html_entity_decode($matches[1], ENT_QUOTES);
    }

    /**
     * @return array<string, mixed>
     */
    private function parseHrefQuery(string $href): array
    {
        $parts = parse_url($href);
        $query = [];

        if (!empty($parts['query'])) {
            parse_str((string) $parts['query'], $query);
        }

        return $query;
    }
}