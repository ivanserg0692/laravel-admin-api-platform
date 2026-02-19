<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        $status = fake()->randomElement(['draft', 'published', 'archived']);
        $publishedAt = $status === 'published'
            ? fake()->dateTimeBetween('-1 year', 'now')
            : null;

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::lower(Str::random(6)),
            'preview' => fake()->optional()->paragraph(),
            'content' => implode("\n\n", fake()->paragraphs(4)),
            'status' => $status,
            'published_at' => $publishedAt,
            'author_id' => fake()->boolean(85) ? User::factory() : null,
            'cover_image' => fake()->optional()->imageUrl(1280, 720, 'news', true),
            'meta_title' => fake()->optional()->sentence(8),
            'meta_description' => fake()->optional()->text(155),
            'sort_order' => fake()->numberBetween(0, 1000),
            'views_count' => fake()->numberBetween(0, 500000),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
            'published_at' => fake()->optional()->dateTimeBetween('-2 years', '-1 day'),
        ]);
    }
}
