<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-900">

            <div class="grid gap-6 p-4 sm:gap-8 sm:p-8 lg:grid-cols-[1fr_340px]">
                <article class="space-y-6">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                        {{ $titleValue }}
                    </h1>

                    <div
                        class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm font-semibold text-gray-700 dark:text-gray-200 sm:text-base">
                        @if($primaryMetaValue)
                            <span class="inline-flex items-center">
                                <svg class="mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
                                     aria-hidden="true">
                                    <path
                                        d="M6 2a1 1 0 011 1v1h6V3a1 1 0 112 0v1h1a2 2 0 012 2v2H2V6a2 2 0 012-2h1V3a1 1 0 011-1z"/>
                                    <path d="M2 10h16v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                </svg>
                                {{ $primaryMetaValue }}
                            </span>
                        @endif

                        @if($secondaryMetaValue)
                            <span class="inline-flex items-center">
                                <svg class="mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
                                     aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M10 2a6 6 0 016 6c0 4.166-5.145 9.705-5.364 9.938a.9.9 0 01-1.272 0C9.145 17.705 4 12.166 4 8a6 6 0 016-6zm0 8a2 2 0 100-4 2 2 0 000 4z"
                                          clip-rule="evenodd"/>
                                </svg>
                                {{ $secondaryMetaValue }}
                            </span>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Details:</h2>
                        <p class="max-w-3xl text-base leading-relaxed text-gray-700 dark:text-gray-300 sm:text-xl">
                            {{ $contentValue }}
                        </p>
                    </div>

                    @if(isset($actions))
                        {{ $actions }}
                    @else
                        <div class="flex flex-wrap gap-3 pt-2">
                            @if($editUrl)
                                <x-buttons.primary :href="$editUrl"
                                                   class="!px-4 !py-2 !text-sm sm:!px-5 sm:!py-2.5 sm:!text-base">
                                    <svg class="mr-2 h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.212l-4.5 1.288 1.288-4.5L16.862 3.487z"/>
                                    </svg>
                                    Edit
                                </x-buttons.primary>
                            @endif

                            @if($previewUrl)
                                <x-buttons.secondary :href="$previewUrl"
                                                     class="!px-4 !py-2 !text-sm sm:!px-5 sm:!py-2.5 sm:!text-base">
                                    Preview
                                </x-buttons.secondary>
                            @endif

                                @php
                                    $deleteConfirmMessage = strtr(
                                        __('news.delete_confirm_template'),
                                        [
                                            '{id}' => (string) (data_get($item, 'id') ?? '?'),
                                            '{title}' => (string) (data_get($item, 'title') ?? ''),
                                        ]
                                    );
                                @endphp
                                <x-buttons.danger type="button"
                                                  onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: '{{ $deleteConfirmModalId }}' }))"
                                                  class="!px-4 !py-2 !text-sm sm:!px-5 sm:!py-2.5 sm:!text-base">
                                    <svg class="mr-2 h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 14 15" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                              d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06631 0.30087 7.89922 0.300781H6.09922Z"/>
                                    </svg>
                                    {{ $deleteLabel }}
                                </x-buttons.danger>
                                <x-crud.modals.confirm-delete
                                    :name="$deleteConfirmModalId"
                                    :title="__('news.delete_confirm_title')"
                                    :message="$deleteConfirmMessage"
                                />
                            @if($backUrl)
                                <x-buttons.secondary :href="$backUrl">
                                    {{ $backLabel }}
                                </x-buttons.secondary>
                            @endif
                        </div>
                    @endif
                </article>

                <aside
                    class="h-fit rounded-lg border border-gray-200 bg-gray-100 p-6 dark:border-gray-700 dark:bg-gray-800">
                    <div class="space-y-4">
                        @foreach($metaItems as $metaItem)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ data_get($metaItem, 'label') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">{{ data_get($metaItem, 'value') }}</p>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
