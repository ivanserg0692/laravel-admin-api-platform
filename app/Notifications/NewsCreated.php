<?php

namespace App\Notifications;

use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected readonly News $news)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('news.notifications.created.subject'))
            ->line(__('news.notifications.created.intro', ['title' => $this->news->title]))
            ->action(__('news.notifications.created.action'), route('news.show', $this->news))
            ->line(__('news.notifications.created.outro'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'news_id' => $this->news->id,
            'title' => $this->news->title,
            'message' => __('news.notifications.created.database_message', ['title' => $this->news->title]),
            'url' => route('news.show', $this->news),
        ];
    }
}
