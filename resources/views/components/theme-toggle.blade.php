@props(['variant' => 'default'])

@php
$classes = $variant === 'menu'
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/60 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-800/60 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out'
    : 'rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800 dark:focus:ring-gray-700';
@endphp

<button
    data-theme-toggle
    type="button"
    {{ $attributes->merge(['class' => $classes]) }}
>
    <span class="dark:hidden">{{ __('welcome.theme_toggle_dark') }}</span>
    <span class="hidden dark:inline">{{ __('welcome.theme_toggle_light') }}</span>
</button>
