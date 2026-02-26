@props([
    'name' => 'update-product',
    'fields' => [],
    'values' => [],
    'action' => '#',
    'title' => __('crud.edit_label'),
    'submitLabel' => 'Update product',
    'deleteModal' => null,
    'wireSubmit' => null,
    'livewireModelRoot' => null,
    'currentItemId' => null,
    'currentItemTitle' => '',
])

@php
    $isDeleteDisabled = !$currentItemId;
    $deleteActionMethod = $currentItemId ? ('openDeleteModal(' . (int) $currentItemId . ')') : null;
@endphp

<x-modals.panel :name="$name" maxWidth="2xl" :title="$title">
    <div>
        <x-crud.forms.update
            id-prefix="update-product"
            :action="$action"
            :fields="$fields"
            :values="$values"
            :wire-submit="$wireSubmit"
            :livewire-model-root="$livewireModelRoot"
        >
            <div class="flex items-center space-x-4">
                <x-buttons.primary
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="saveUpdate"
                >
                    <span wire:loading.remove wire:target="saveUpdate">{{ $submitLabel }}</span>
                    <span wire:loading.inline-flex wire:target="saveUpdate" class="items-center">
                        <x-ui.spinner size-class="h-4 w-4" class="-ml-1 mr-2 text-white" />
                        {{ $submitLabel }}
                    </span>
                </x-buttons.primary>
                <x-buttons.danger
                    type="button"
                    :disabled="$isDeleteDisabled"
                    wire:click="{{ $deleteActionMethod }}"
                    wire:loading.attr="disabled"
                    wire:target="openDeleteModal"
                >
                    <span wire:loading.remove wire:target="openDeleteModal" class="inline-flex items-center">
                        <svg class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                  clip-rule="evenodd"/>
                        </svg>
                        {{ __('crud.delete_label') }}
                    </span>
                    <span wire:loading.inline-flex wire:target="openDeleteModal" class="items-center">
                        <x-ui.spinner size-class="h-4 w-4" class="-ml-1 mr-2 text-white" />
                        {{ __('crud.delete_label') }}
                    </span>
                </x-buttons.danger>
            </div>
        </x-crud.forms.update>
    </div>
</x-modals.panel>
