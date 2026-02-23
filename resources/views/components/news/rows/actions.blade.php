@props([
    'item',
    'editModal' => 'update-product',
    'previewModal' => 'read-product',
    'deleteModal' => 'delete-product',
])

@php
    $editInitUrl = route('news.edit-init', $item);
    $previewInitUrl = route('news.preview-init', $item);
    $deleteInitUrl = route('news.delete-init', $item);
@endphp

<li>
    <x-dropdown.link
        href="#"
        onclick="window.App.UI.NewsActions.editInit(event, this)"
        class="js-news-edit-init-link flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200"
        data-edit-init-url="{{ $editInitUrl }}"
        data-edit-modal="{{ $editModal }}"
    >
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
             fill="currentColor" aria-hidden="true">
            <path
                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
        </svg>
        {{ __('crud.edit_label') }}
    </x-dropdown.link>
</li>

<li>
    <x-dropdown.link
        href="#"
        onclick="window.App.UI.NewsActions.previewInit(event, this)"
        class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200"
        data-preview-init-url="{{ $previewInitUrl }}"
        data-preview-modal="{{ $previewModal }}"
    >
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
             fill="currentColor" aria-hidden="true">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        {{ __('crud.preview_label') }}
    </x-dropdown.link>
</li>

<li>
    <x-dropdown.link
        href="#"
        onclick="window.App.UI.NewsActions.deleteInit(event, this)"
        class="flex items-center text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-red-400"
        data-delete-init-url="{{ $deleteInitUrl }}"
        data-delete-modal="{{ $deleteModal }}"
    >
        <svg class="w-4 h-4 mr-2" viewbox="0 0 14 15" fill="none"
             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                  d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06631 0.30087 7.89922 0.300781H6.09922Z"/>
        </svg>
        {{ __('crud.delete_label') }}
    </x-dropdown.link>
</li>
