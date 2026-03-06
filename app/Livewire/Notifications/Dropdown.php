<?php

namespace App\Livewire\Notifications;

use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dropdown extends Component
{
    public int $limit = 8;
    public bool $mobile = false;

    public function mount(bool $mobile = false): void
    {
        $this->mobile = $mobile;
    }

    public function deleteNotification(string $notificationId): void
    {
        $user = auth()->user();
        if ($user === null) {
            return;
        }

        /** @var DatabaseNotification|null $notification */
        $notification = $user->notifications()->find($notificationId);
        if ($notification === null) {
            return;
        }

        $notification->delete();
    }

    public function deleteReadNotifications(): void
    {
        $user = auth()->user();
        if ($user === null) {
            return;
        }

        $user->readNotifications()->delete();
    }

    public function openNotification(string $notificationId)
    {
        $user = auth()->user();
        if ($user === null) {
            return null;
        }

        /** @var DatabaseNotification|null $notification */
        $notification = $user->notifications()->find($notificationId);
        if ($notification === null) {
            return null;
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        $data = is_array($notification->data) ? $notification->data : [];
        $url = (string) ($data['url'] ?? '');
        if ($url !== '') {
            return $this->redirect($url, navigate: true);
        }

        return null;
    }

    public function render(): View
    {
        $user = auth()->user();
        $notificationsQuery = $user?->notifications();
        $notifications = $notificationsQuery
            ->latest()
            ->limit($this->limit)
            ->get() ?? collect();
        $viewName = $this->mobile
            ? 'livewire.notifications.dropdown-mobile'
            : 'livewire.notifications.dropdown';

        return view($viewName, [
            'notifications' => $this->mapNotifications($notifications),
            'unreadCount' => $user?->unreadNotifications()->count() ?? 0,
            'totalCount' => $notificationsQuery?->count() ?? 0,
        ]);
    }

    /**
     * @param Collection<int, DatabaseNotification> $notifications
     * @return Collection<int, array<string, mixed>>
     */
    private function mapNotifications(Collection $notifications): Collection
    {
        return $notifications->map(function (DatabaseNotification $notification): array {
            $data = is_array($notification->data) ? $notification->data : [];
            $title = (string)($data['title'] ?? $data['message'] ?? $data['text'] ?? __('notifications.fallback_title'));
            $message = (string) ($data['message'] ?? '');
            $url = (string) ($data['url'] ?? '');
            $meta = $notification->created_at?->diffForHumans() ?? '';

            return [
                'id' => $notification->id,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'isClickable' => $url !== '',
                'meta' => $meta,
                'state' => $notification->read_at ? 'read' : 'unread',
            ];
        });
    }
}
