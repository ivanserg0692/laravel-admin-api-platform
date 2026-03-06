<?php

namespace App\Listeners;

use App\Events\NewsCreatedEvent;
use App\Models\User;
use App\Notifications\NewsCreated;
use Illuminate\Support\Facades\Notification;

class SendNewsCreatedNotification
{
    public function handle(NewsCreatedEvent $event): void
    {
        $news = $event->news;

        $recipients = User::query()
            ->where('is_blocked', false)
            ->whereKeyNot((int)$news->author_id)
            ->whereHas('tags', function ($query) {
                $query->whereIn('slug', ['admin', 'news_admin']);
            })
            ->get();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new NewsCreated($news));
        }
    }
}
