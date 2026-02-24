<?php

namespace Tests\Feature\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsModalActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_news_action_links_and_modal_titles(): void
    {
        $user = User::factory()->create();
        $news = News::factory()->create([
            'title' => 'Modal Test News',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('news.index'));

        $response->assertOk();

        $response->assertSee('data-edit-init-url="' . route('news.edit-init', $news) . '"', false);
        $response->assertSee('data-preview-init-url="' . route('news.preview-init', $news) . '"', false);
        $response->assertSee('data-delete-init-url="' . route('news.delete-init', $news) . '"', false);

        $response->assertSee('data-edit-modal="update-product-', false);
        $response->assertSee('data-preview-modal="read-product-', false);
        $response->assertSee('data-delete-modal="delete-product-', false);

        $response->assertSee(__('news.update_modal_title'));
        $response->assertSee(__('news.preview_modal_title'));
        $response->assertSee(__('news.delete_confirm_title'));
    }

    public function test_edit_init_returns_modal_payload_for_selected_news(): void
    {
        $user = User::factory()->create();
        $news = News::factory()->create([
            'title' => 'Edit Init Payload',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('news.edit-init', $news));

        $response
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'data' => [
                    'id' => $news->id,
                    'title' => $news->title,
                    'delete_url' => route('news.destroy', $news),
                ],
            ])
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'id',
                    'title',
                    'delete_url',
                    'values',
                ],
            ]);

        $payload = $response->json('data.values');
        $this->assertIsArray($payload);
        $this->assertNotEmpty($payload);
    }

    public function test_preview_init_returns_preview_payload_for_selected_news(): void
    {
        $user = User::factory()->create();
        $news = News::factory()->create([
            'title' => 'Preview Init Payload',
            'preview' => 'Preview body',
            'content' => 'Full content',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('news.preview-init', $news));

        $response
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'data' => [
                    'id' => $news->id,
                ],
            ])
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'id',
                    'preview' => [
                        'title',
                        'status',
                        'published_at',
                        'preview',
                        'content',
                        'cover_image',
                    ],
                ],
            ]);
    }

    public function test_delete_init_returns_delete_payload_for_selected_news(): void
    {
        $user = User::factory()->create();
        $news = News::factory()->create([
            'title' => 'Delete Init Payload',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('news.delete-init', $news));

        $response
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'data' => [
                    'id' => $news->id,
                    'title' => $news->title,
                    'delete_url' => route('news.destroy', $news),
                ],
            ])
            ->assertJsonStructure([
                'ok',
                'data' => [
                    'id',
                    'title',
                    'delete_url',
                ],
            ]);
    }
}

