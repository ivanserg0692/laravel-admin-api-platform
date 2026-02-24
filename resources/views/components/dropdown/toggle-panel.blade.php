@props([
    'buttonId',
    'dropdownId',
    'buttonClass' => 'w-full md:w-auto py-2 px-4',
    'panelClass' => '',
])

<x-buttons.secondary id="{{ $buttonId }}" data-dropdown-toggle="{{ $dropdownId }}"
                    class="{{ $buttonClass }}"
                    type="button">
    {{ $trigger }}
</x-buttons.secondary>

<div id="{{ $dropdownId }}" class="{{ $panelClass }}">
    {{ $content }}
</div>
