@props(['wide' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @livewireStyles
</head>
<body class="antialiased bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-100">
<section class="bg-white dark:bg-gray-950">
    <div class="mx-auto max-w-screen-xl px-4 py-8 lg:px-6">
        @if ($wide)
            <div class="flex items-center justify-between gap-4">
                <a href="/" class="shrink-0">
                    <x-application-logo class="h-10 w-auto fill-current text-gray-500"/>
                </a>

                @isset($topbar)
                    <div class="flex items-center">
                        {{ $topbar }}
                    </div>
                @endisset
            </div>

            {{ $slot }}
        @else
            <div class="flex justify-center">
                <a href="/" class="shrink-0">
                    <x-application-logo class="h-10 w-auto fill-current text-gray-500"/>
                </a>
            </div>

            <div
                class="mx-auto mt-6 w-full sm:max-w-md overflow-hidden bg-white px-6 py-4 shadow-md sm:rounded-lg dark:bg-gray-900">
                {{ $slot }}
            </div>
        @endif

        <footer class="{{ $wide ? 'mt-16' : 'mt-8' }} text-center text-xs text-gray-500 dark:text-gray-400">
            {{ __('welcome.footer_laravel_version', ['version' => Illuminate\Foundation\Application::VERSION]) }}
        </footer>
    </div>
</section>
@livewireScripts
@stack('scripts')
</body>
</html>
