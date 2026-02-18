<x-guest-layout :wide="true">
    <x-slot name="topbar">
        @include('layouts.navigation')
    </x-slot>

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-900 shadow dark:shadow-none border-b border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    {{ $slot }}
</x-guest-layout>
