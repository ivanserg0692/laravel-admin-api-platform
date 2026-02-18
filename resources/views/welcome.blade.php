<x-guest-layout :wide="true">
    <x-slot name="topbar">
        <div class="flex items-center justify-end gap-5 text-sm font-medium">
            <x-theme-toggle/>
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                    {{ __('welcome.top_dashboard') }}
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                    {{ __('welcome.top_login') }}
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="ml-5 text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                        {{ __('welcome.top_register') }}
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="mx-auto mt-14 max-w-screen-md text-center">
                    <span
                        class="inline-flex rounded-full bg-primary-100 px-4 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-primary-800 dark:bg-primary-900 dark:text-primary-100">
                        {{ __('welcome.project_label') }}
                    </span>
        <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white md:text-5xl">
            {{ __('welcome.heading') }}
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600 dark:text-gray-300">
            {{ __('welcome.description') }}
        </p>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
            @auth
                <a
                    href="{{ url('/dashboard') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-5 py-3 text-sm font-semibold text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-900"
                >
                    {{ __('welcome.cta_dashboard') }}
                </a>
            @else
                <a
                    href="{{ route('register') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-5 py-3 text-sm font-semibold text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-900"
                >
                    {{ __('welcome.cta_register') }}
                </a>
                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800 dark:focus:ring-gray-700"
                >
                    {{ __('welcome.cta_login') }}
                </a>
            @endauth
        </div>
    </div>

    <div class="mx-auto mt-16 max-w-screen-lg text-center">
        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
            {{ __('welcome.in_progress_title') }}
        </h2>
        <p class="mx-auto mt-3 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ __('welcome.in_progress_description') }}
        </p>
        <div class="mt-8 grid grid-cols-1 gap-4 text-left sm:grid-cols-2 lg:grid-cols-3">
            @for ($i = 1; $i <= 6; $i++)
                <div
                    class="rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('welcome.feature_' . $i) }}
                    </div>
                    <div
                        class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                        {{ __('welcome.status_in_progress') }}
                    </div>
                </div>
            @endfor
        </div>
    </div>
</x-guest-layout>
