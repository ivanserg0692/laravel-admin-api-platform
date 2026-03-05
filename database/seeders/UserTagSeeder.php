<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserTag;
use Illuminate\Database\Seeder;

class UserTagSeeder extends Seeder
{
    public function run(): void
    {
        $adminTag = UserTag::query()->firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin']
        );

        UserTag::query()->upsert([
            ['slug' => 'news_admin', 'name' => 'News Admin'],
            ['slug' => 'author', 'name' => 'Author'],
        ], ['slug']);

        $user = User::query()->find(1);

        if ($user === null) {
            return;
        }

        $user->tags()->syncWithoutDetaching([$adminTag->id]);
    }
}
