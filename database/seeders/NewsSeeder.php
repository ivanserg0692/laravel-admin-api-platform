<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = (int)env('NEWS_SEED_COUNT', 20);
        $existingUserIds = User::query()->pluck('id')->all();

        News::factory()
            ->count($count)
            ->state(function () use (&$existingUserIds): array {
                $roll = fake()->numberBetween(1, 100);

                // 70%: assign an existing user when available
                if ($roll <= 70 && !empty($existingUserIds)) {
                    return ['author_id' => fake()->randomElement($existingUserIds)];
                }

                // 20%: create a new user and assign it as author
                if ($roll <= 90) {
                    $user = User::factory()->create();
                    $existingUserIds[] = $user->id;

                    return ['author_id' => $user->id];
                }

                // 10%: leave news item without an author
                return ['author_id' => null];
            })
            ->create();
    }
}
