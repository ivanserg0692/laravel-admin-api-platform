@props([
    'name',
    'formId' => null,
    'title' => __('crud.delete_confirm_title'),
    'message' => __('crud.delete_confirm_template'),
    'cancelLabel' => __('crud.no_cancel'),
    'confirmLabel' => __('crud.yes_delete'),
])

@php
    $resolvedFormId = $formId ?: 'delete-form-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', (string) $name);
@endphp

<x-modals.panel
    :name="$name"
    maxWidth="2xl"
    :title="$title"
>
    <div
        x-data="{
            modalName: @js($name),
            formId: @js($resolvedFormId),
            messageTemplate: @js($message),
            deleteUrl: '',
            id: null,
            title: '',
            renderMessage() {
                const resolvedId = this.id ?? '?';
                const resolvedTitle = this.title || '';
                return this.messageTemplate
                    .replace('{id}', String(resolvedId))
                    .replace('{title}', resolvedTitle);
            },
        }"
        x-on:news-delete-values-loaded.window="
            if ($event.detail?.modal !== modalName) return;
            id = $event.detail?.id ?? null;
            title = typeof $event.detail?.title === 'string' ? $event.detail.title : '';
            deleteUrl = typeof $event.detail?.deleteUrl === 'string' ? $event.detail.deleteUrl : '';
        "
    >
        <p class="mb-4 text-gray-500 dark:text-gray-300" x-text="renderMessage()"></p>

        @if(!$formId)
            <form :id="formId" method="POST" x-bind:action="deleteUrl || '#'" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
    <div class="flex justify-center items-center gap-3">
        <x-buttons.secondary
            type="button"
            x-on:click="$dispatch('close-modal', '{{ $name }}')"
        >
            {{ $cancelLabel }}
        </x-buttons.secondary>
        <x-buttons.danger type="submit" :form="$resolvedFormId" x-bind:disabled="!deleteUrl">
            {{ $confirmLabel }}
        </x-buttons.danger>
    </div>
</x-modals.panel>
