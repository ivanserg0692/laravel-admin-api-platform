@props([
    'item' => null,
    'title' => 'Update Item',
    'formAction' => '#',
    'formMethod' => 'PUT',
    'idPrefix' => 'page-update-item',
    'fields' => [],
    'values' => [],
    'errorBag' => null,
    'alpineModelRoot' => null,
    'livewireModelRoot' => null,
    'livewireValidationActive' => false,
    'submitLabel' => __('crud.save_label'),
    'deleteLabel' => __('crud.delete_label'),
    'backUrl' => null,
    'backLabel' => __('crud.back_label'),
    'deleteModalName' => null,
    'deleteModalTitle' => __('crud.delete_confirm_title'),
    'deleteModalMessage' => __('crud.delete_confirm_message'),
])

@php
    $resolvedValues = $values;
    $deleteConfirmModalId = is_string($deleteModalName) && trim($deleteModalName) !== ''
        ? trim($deleteModalName)
        : ($idPrefix . '-delete-confirm');
    $currentItemId = (int) (data_get($item, 'id') ?? 0);

    if (empty($resolvedValues) && $item) {
        foreach ($fields as $field) {
            $name = data_get($field, 'name');
            if ($name) {
                $resolvedValues[$name] = data_get($item, $name);
            }
        }
    }
@endphp

<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
            <div class="mb-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
            </div>

            <x-crud.forms.update
                :id-prefix="$idPrefix"
                :action="$formAction"
                :http-method="$formMethod"
                :fields="$fields"
                :values="$resolvedValues"
                :error-bag="$errorBag"
                :alpine-model-root="$alpineModelRoot"
                :livewire-model-root="$livewireModelRoot"
                :livewire-validation-active="$livewireValidationActive"
                :wire-submit="'saveUpdate'"
            >
                <div class="flex items-center gap-3">
                    <x-buttons.primary type="submit">
                        {{ $submitLabel }}
                    </x-buttons.primary>

                    <x-buttons.danger
                        type="button"
                        :disabled="$currentItemId <= 0"
                        wire:click="openDeleteModal({{ $currentItemId }})"
                        wire:loading.attr="disabled"
                        wire:target="openDeleteModal"
                    >{{ $deleteLabel }}</x-buttons.danger>

                    @if($backUrl)
                        <x-buttons.secondary :href="$backUrl">{{ $backLabel }}</x-buttons.secondary>
                    @endif
                </div>
            </x-crud.forms.update>

            <x-crud.modals.confirm-delete
                :name="$deleteConfirmModalId"
                :title="$deleteModalTitle"
                :message="$deleteModalMessage"
                :wire-confirm-action="'deleteSelectedNews'"
            />
        </div>
    </div>
</section>
