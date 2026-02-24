@props([
    'name',
    'maxWidth' => '2xl',
    'title' => null,
    'panelClass' => 'relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5',
    'headerClass' => 'flex items-center justify-between pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600',
    'titleClass' => 'w-full text-center text-lg font-semibold text-gray-900 dark:text-white',
])

<x-modals.modal :name="$name" :maxWidth="$maxWidth">
    <div class="{{ $panelClass }}">
        @if(isset($header) || $title)
            <div class="{{ $headerClass }}">
                <div class="w-full">
                    @isset($header)
                        {{ $header }}
                    @else
                        <h3 class="{{ $titleClass }}">{{ $title }}</h3>
                    @endisset
                </div>
                <x-buttons.icon-close class="ml-auto" x-on:click="$dispatch('close-modal', '{{ $name }}')" />
            </div>
        @else
            <x-buttons.icon-close class="absolute top-2.5 right-2.5 ml-auto" x-on:click="$dispatch('close-modal', '{{ $name }}')" />
        @endif

        {{ $slot }}
    </div>
</x-modals.modal>
