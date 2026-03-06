<div class="px-4 pb-3" wire:key="notifications-mobile" wire:poll.10s x-data="{ openNotifications: false }">
    <button
        type="button"
        @click="openNotifications = !openNotifications"
        class="flex w-full items-center justify-between rounded-lg border border-gray-200 bg-white px-3 py-2 text-left text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
    >
        <span>{{ __('notifications.title') }}</span>
        <span class="flex items-center gap-2">
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $unreadCount }}/{{ $totalCount }}</span>
            <span
                class="relative inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M10 2a4 4 0 00-4 4v1.19c0 .67-.26 1.31-.73 1.78L4.2 10.05a1 1 0 00.7 1.7h10.2a1 1 0 00.7-1.7l-1.07-1.08A2.52 2.52 0 0114 7.2V6a4 4 0 00-4-4zm-2 12a2 2 0 104 0H8z"
                          clip-rule="evenodd"/>
                </svg>
                @if ($unreadCount > 0)
                    <span
                        class="absolute -top-1 -right-1 min-w-4 rounded-full bg-sky-500 px-1 text-center text-[10px] font-semibold leading-4 text-white">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </span>
        </span>
    </button>

    <div x-show="openNotifications" x-transition
         class="mt-2 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
        <div class="flex items-start justify-between gap-3 border-b border-gray-200 px-3 py-2 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('notifications.unread') }}: {{ $unreadCount }} / {{ __('notifications.total') }}
                : {{ $totalCount }}
            </p>
            <button
                type="button"
                wire:click="deleteReadNotifications"
                class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                title="{{ __('notifications.clear_read') }}"
                aria-label="{{ __('notifications.clear_read') }}"
            >
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M9 2a1 1 0 00-.894.553L7.382 4H5a1 1 0 000 2h.293l.72 9.314A2 2 0 008.007 17h3.986a2 2 0 001.994-1.686L14.707 6H15a1 1 0 100-2h-2.382l-.724-1.447A1 1 0 0011 2H9zm-1.705 4l.674 8.707a1 1 0 00.997.843h3.986a1 1 0 00.997-.843L14.623 6H7.295z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        <div class="max-h-72 overflow-y-auto overflow-x-hidden">
            @forelse ($notifications as $notification)
                <div class="border-b border-gray-200 last:border-b-0 dark:border-gray-700">
                    <div class="flex items-start gap-1">
                        <div class="min-w-0 flex-1">
                            @if ($notification['isClickable'])
                                <button
                                    type="button"
                                    wire:click="openNotification('{{ $notification['id'] }}')"
                                    class="w-full px-3 py-2 text-left transition hover:bg-gray-50 focus:outline-none dark:hover:bg-gray-800"
                                >
                                    <div class="flex items-start gap-2">
                                        <span
                                            class="mt-1 inline-flex h-2 w-2 rounded-full {{ $notification['state'] === 'unread' ? 'bg-sky-500' : 'bg-gray-400 dark:bg-gray-600' }}"></span>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm text-gray-800 dark:text-gray-100">{{ $notification['title'] }}</p>
                                            @if (!empty($notification['message']))
                                                <p class="text-sm text-gray-700 whitespace-normal break-words line-clamp-2 dark:text-gray-200"
                                                   title="{{ $notification['message'] }}">{{ $notification['message'] }}</p>
                                            @endif
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $notification['meta'] }}</p>
                                        </div>
                                    </div>
                                </button>
                            @else
                                <div class="px-3 py-2">
                                    <div class="flex items-start gap-2">
                                        <span
                                            class="mt-1 inline-flex h-2 w-2 rounded-full {{ $notification['state'] === 'unread' ? 'bg-sky-500' : 'bg-gray-400 dark:bg-gray-600' }}"></span>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm text-gray-800 dark:text-gray-100">{{ $notification['title'] }}</p>
                                            @if (!empty($notification['message']))
                                                <p class="text-sm text-gray-700 whitespace-normal break-words line-clamp-2 dark:text-gray-200"
                                                   title="{{ $notification['message'] }}">{{ $notification['message'] }}</p>
                                            @endif
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $notification['meta'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button
                            type="button"
                            wire:click.stop="deleteNotification('{{ $notification['id'] }}')"
                            class="mr-2 mt-2 shrink-0 rounded p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                            title="{{ __('notifications.delete_single') }}"
                            aria-label="{{ __('notifications.delete_single') }}"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M9 2a1 1 0 00-.894.553L7.382 4H5a1 1 0 000 2h.293l.72 9.314A2 2 0 008.007 17h3.986a2 2 0 001.994-1.686L14.707 6H15a1 1 0 100-2h-2.382l-.724-1.447A1 1 0 0011 2H9zm-1.705 4l.674 8.707a1 1 0 00.997.843h3.986a1 1 0 00.997-.843L14.623 6H7.295z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('notifications.empty') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
