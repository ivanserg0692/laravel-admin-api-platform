<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('welcome.meta_title') }}</title>
        <script>
            (function () {
                const theme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (theme === 'dark' || (!theme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-100">
    <section class="bg-white dark:bg-gray-950">
        <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6">
            @if (Route::has('login'))
                <div class="mb-14 flex items-center justify-end gap-5 text-sm font-medium">
                    <button
                        id="theme-toggle"
                        type="button"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800 dark:focus:ring-gray-700"
                    >
                        <span class="dark:hidden">{{ __('welcome.theme_toggle_dark') }}</span>
                        <span class="hidden dark:inline">{{ __('welcome.theme_toggle_light') }}</span>
                    </button>
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
            @endif

            <div class="mx-auto max-w-screen-md text-center">
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
                            <div class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                {{ __('welcome.status_in_progress') }}
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
    <script>
        (function () {
            const toggle = document.getElementById('theme-toggle');
            if (!toggle) return;
            toggle.addEventListener('click', function () {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        })();
    </script>
    </body>
</html>
