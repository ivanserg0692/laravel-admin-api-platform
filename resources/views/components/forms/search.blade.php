@props([
    'placeholder' => __('news.placeholders.search'),
])

@php
    $searchValue = request()->query('search', '');
    $queryWithoutSearch = request()->except('search');
    $actionUrl = request()->url();

    if (!empty($queryWithoutSearch)) {
        $actionUrl .= '?' . http_build_query($queryWithoutSearch);
    }
@endphp

<form method="GET" action="{{ $actionUrl }}" class="flex items-stretch gap-2">
    <label for="simple-search" class="sr-only">{{ $placeholder }}</label>
    <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                 fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"/>
            </svg>
        </div>
        <input type="text" id="simple-search" name="search"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full h-10 pl-10 pr-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
               placeholder="{{ $placeholder }}" value="{{ is_string($searchValue) ? $searchValue : '' }}">
    </div>
    <x-buttons.secondary type="submit" class="!h-10 !px-4 !font-medium" aria-label="{{ $placeholder }}">
        <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m21 21-4.35-4.35M11 19a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
        </svg>
    </x-buttons.secondary>
</form>
