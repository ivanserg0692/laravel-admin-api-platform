<a href="{{ $sortUrl }}" class="inline-flex items-center gap-1 {{ $activeClass }}">
    {{ $label }}

    @if($direction !== null)
        <svg class="h-3 w-3" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            @if($direction === 'asc')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            @endif
        </svg>
    @endif

    @if($priority !== null)
        <span class="inline-flex h-4 min-w-4 items-center justify-center rounded bg-gray-200 px-1 text-[10px] leading-none text-gray-800 dark:bg-gray-600 dark:text-gray-100">
            {{ $priority }}
        </span>
    @endif
</a>
