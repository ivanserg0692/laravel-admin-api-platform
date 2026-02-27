@props([
    'type' => 'success',
])

@php
    $resolvedType = is_string($type) && trim($type) !== '' ? trim($type) : 'success';
    $message = session($resolvedType);

    $stylesByType = [
        'success' => [
            'wrapper' => 'mx-auto mt-4 max-w-screen-xl px-4 lg:px-12',
            'box' => 'rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800 dark:border-green-900/60 dark:bg-green-900/20 dark:text-green-300',
        ],
    ];

    $resolvedStyles = data_get($stylesByType, $resolvedType, data_get($stylesByType, 'success'));
@endphp

@if(filled($message))
    <div class="{{ data_get($resolvedStyles, 'wrapper') }}">
        <div class="{{ data_get($resolvedStyles, 'box') }}">
            {{ $message }}
        </div>
    </div>
    <br>
@endif
