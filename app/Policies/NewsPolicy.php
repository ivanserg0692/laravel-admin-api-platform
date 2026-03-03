<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($this->isBlocked($user)) {
            return false;
        }
        return $this->hasAnyTag($user, ['admin', 'news_admin', 'author']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, News $news): bool
    {
        return $this->canManageNews($user, $news);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): bool
    {
        return $this->canManageNews($user, $news);
    }

    private function canManageNews(User $user, News $news): bool
    {
        if ($this->isBlocked($user)) {
            return false;
        }

        if ($this->hasAnyTag($user, ['admin', 'news_admin'])) {
            return true;
        }

        if ($this->hasAnyTag($user, ['author'])) {
            return (int)$news->author_id === (int)$user->id;
        }

        return false;
    }

    private function isBlocked(User $user): bool
    {
        return (bool)$user->is_blocked;
    }

    private function hasAnyTag(User $user, array $slugs): bool
    {
        return $user->tags()
            ->whereIn('slug', $slugs)
            ->exists();
    }
}
