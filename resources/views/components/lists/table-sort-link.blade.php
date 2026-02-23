@props([
    'sortKey',
    'label',
    'activeSorts' => [],
    'currentDirection' => null,
    'priority' => null,
])

@php
    $normalizedCurrentDirection = in_array($currentDirection, ['asc', 'desc'], true)
        ? $currentDirection
        : null;

    $nextDirection = match ($normalizedCurrentDirection) {
        null => 'asc',
        'asc' => 'desc',
        default => null,
    };

    $normalizedSorts = collect(is_array($activeSorts) ? $activeSorts : [])
        ->map(function ($item) {
            if (!is_array($item)) {
                return null;
            }

            $key = trim((string) data_get($item, 'key', ''));
            $direction = strtolower(trim((string) data_get($item, 'direction', '')));

            if ($key === '' || !in_array($direction, ['asc', 'desc'], true)) {
                return null;
            }

            return [
                'key' => $key,
                'direction' => $direction,
            ];
        })
        ->filter()
        ->values()
        ->all();

    $updatedSorts = [];
    $isColumnFound = false;

    foreach ($normalizedSorts as $sortItem) {
        if ($sortItem['key'] !== $sortKey) {
            $updatedSorts[] = $sortItem;
            continue;
        }

        $isColumnFound = true;

        if ($nextDirection !== null) {
            $updatedSorts[] = [
                'key' => $sortKey,
                'direction' => $nextDirection,
            ];
        }
    }

    if (!$isColumnFound && $nextDirection !== null) {
        $updatedSorts[] = [
            'key' => $sortKey,
            'direction' => $nextDirection,
        ];
    }

    $querySorts = collect($updatedSorts)
        ->map(fn (array $sortItem) => $sortItem['key'] . ':' . $sortItem['direction'])
        ->values()
        ->all();

    $query = request()->query();

    if (empty($querySorts)) {
        unset($query['sort']);
    } else {
        $query['sort'] = $querySorts;
    }

    unset($query['direction']);
    $query['page'] = 1;

    $sortUrl = request()->url();
    $queryString = http_build_query($query);

    if ($queryString !== '') {
        $sortUrl .= '?' . $queryString;
    }

    $activeClass = $normalizedCurrentDirection !== null
        ? 'text-gray-900 dark:text-white'
        : 'text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white';
@endphp

<a href="{{ $sortUrl }}" class="inline-flex items-center gap-1 {{ $activeClass }}">
    {{ $label }}

    @if($normalizedCurrentDirection !== null)
        <svg class="h-3 w-3" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            @if($normalizedCurrentDirection === 'asc')
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
