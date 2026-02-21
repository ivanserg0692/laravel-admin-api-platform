@props([
    'name',
    'formId',
    'message' => __('crud.delete_confirm_message'),
    'cancelLabel' => __('crud.no_cancel'),
    'confirmLabel' => __('crud.yes_delete'),
])

<x-modals.panel
    :name="$name"
    maxWidth="md"
    panelClass="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5"
>
    <p class="mb-4 text-gray-500 dark:text-gray-300">
        {{ $message }}
    </p>
    <div class="flex justify-center items-center gap-3">
        <x-buttons.secondary
            type="button"
            x-on:click="$dispatch('close-modal', '{{ $name }}')"
        >
            {{ $cancelLabel }}
        </x-buttons.secondary>
        <x-buttons.danger type="submit" :form="$formId">
            {{ $confirmLabel }}
        </x-buttons.danger>
    </div>
</x-modals.panel>
