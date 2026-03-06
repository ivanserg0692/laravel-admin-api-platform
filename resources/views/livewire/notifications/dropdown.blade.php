<div class="shrink-0" wire:key="notifications-dropdown" wire:poll.10s>
    <x-dropdown.menu align="right" width="80" contentClasses="py-0 bg-slate-800">
        <x-slot name="trigger">
            <button
                type="button"
                class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-700 bg-slate-800 text-slate-200 transition hover:border-slate-600 hover:text-white focus:outline-none"
                aria-label="{{ __('notifications.aria_label') }}"
            >
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M10 2a4 4 0 00-4 4v1.19c0 .67-.26 1.31-.73 1.78L4.2 10.05a1 1 0 00.7 1.7h10.2a1 1 0 00.7-1.7l-1.07-1.08A2.52 2.52 0 0114 7.2V6a4 4 0 00-4-4zm-2 12a2 2 0 104 0H8z"
                          clip-rule="evenodd"/>
                </svg>

                @if ($unreadCount > 0)
                    <span
                        class="absolute -top-1 -right-1 min-w-5 rounded-full bg-sky-500 px-1.5 py-0.5 text-center text-xs font-semibold leading-none text-white">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="divide-y divide-slate-700 text-slate-200">
                <div class="flex items-start justify-between gap-3 px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ __('notifications.title') }}</p>
                        <p class="text-xs text-slate-400">
                            {{ __('notifications.unread') }}: {{ $unreadCount }} / {{ __('notifications.total') }}
                            : {{ $totalCount }}
                        </p>
                    </div>
                    <button
                        type="button"
                        wire:click="deleteReadNotifications"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-slate-600 bg-slate-800 text-slate-300 transition hover:bg-slate-700/60 hover:text-white focus:outline-none"
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

                <div class="max-h-80 overflow-y-auto overflow-x-hidden">
                    @forelse ($notifications as $notification)
                        <div class="border-b border-slate-700/50 last:border-b-0">
                            <div class="flex items-start gap-1">
                                <div class="min-w-0 flex-1">
                                    @if ($notification['isClickable'])
                                        <button
                                            type="button"
                                            wire:click="openNotification('{{ $notification['id'] }}')"
                                            class="w-full px-4 py-3 text-left transition hover:bg-slate-700/40 focus:outline-none"
                                        >
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="mt-1 inline-flex h-2.5 w-2.5 rounded-full {{ $notification['state'] === 'unread' ? 'bg-sky-400' : 'bg-slate-500' }}"></span>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm text-slate-100">{{ $notification['title'] }}</p>
                                                    @if (!empty($notification['message']))
                                                        <p class="text-sm text-slate-100 whitespace-normal break-words line-clamp-2"
                                                           title="{{ $notification['message'] }}">{{ $notification['message'] }}</p>
                                                    @endif
                                                    <p class="mt-1 text-xs text-slate-400">{{ $notification['meta'] }}</p>
                                                </div>
                                            </div>
                                        </button>
                                    @else
                                        <div class="px-4 py-3 transition hover:bg-slate-700/40">
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="mt-1 inline-flex h-2.5 w-2.5 rounded-full {{ $notification['state'] === 'unread' ? 'bg-sky-400' : 'bg-slate-500' }}"></span>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm text-slate-100">{{ $notification['title'] }}</p>
                                                    @if (!empty($notification['message']))
                                                        <p class="text-sm text-slate-100 whitespace-normal break-words line-clamp-2"
                                                           title="{{ $notification['message'] }}">{{ $notification['message'] }}</p>
                                                    @endif
                                                    <p class="mt-1 text-xs text-slate-400">{{ $notification['meta'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <button
                                    type="button"
                                    wire:click.stop="deleteNotification('{{ $notification['id'] }}')"
                                    class="mr-2 mt-2 shrink-0 rounded p-1 text-slate-400 transition hover:bg-slate-700/60 hover:text-white focus:outline-none"
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
                        <div class="px-4 py-6 text-center text-sm text-slate-400">
                            {{ __('notifications.empty') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </x-slot>
    </x-dropdown.menu>
</div>
