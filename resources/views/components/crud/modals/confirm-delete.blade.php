@props([
    'name',
    'title' => __('crud.delete_confirm_title'),
    'message' => __('crud.delete_confirm_message'),
    'wireConfirmAction' => 'deleteSelectedNews',
    'cancelLabel' => __('crud.no_cancel'),
    'confirmLabel' => __('crud.yes_delete'),
])

<x-modals.panel
    :name="$name"
    maxWidth="2xl"
    :title="$title"
>
    <div
        x-data="{
            modalName: @js($name),
            messageTemplate: @js($message),
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
            window.dispatchEvent(new CustomEvent('open-modal', {detail: modalName}));
        "
    >
        <p class="mb-4 text-gray-500 dark:text-gray-300" x-text="renderMessage()"></p>

        <div class="flex justify-center items-center gap-3">
            <x-buttons.secondary
                type="button"
                x-on:click="$dispatch('close-modal', '{{ $name }}')"
            >
                {{ $cancelLabel }}
            </x-buttons.secondary>
            <x-buttons.danger
                type="button"
                wire:click="{{ $wireConfirmAction }}"
                x-bind:disabled="!id"
                wire:loading.attr="disabled"
                wire:target="{{ $wireConfirmAction }}"
            >
                <span wire:loading.remove wire:target="{{ $wireConfirmAction }}">{{ $confirmLabel }}</span>
                <span wire:loading.inline-flex wire:target="{{ $wireConfirmAction }}" class="items-center">
                    <x-ui.spinner size-class="h-4 w-4" class="-ml-1 mr-2 text-white" />
                    {{ $confirmLabel }}
                </span>
            </x-buttons.danger>
        </div>
    </div>
</x-modals.panel>
