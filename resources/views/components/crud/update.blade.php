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
    'submitLabel' => __('crud.save_label'),
    'deleteLabel' => __('crud.delete_label'),
    'backUrl' => null,
    'backLabel' => __('crud.back_label'),
])

@php
    $resolvedValues = $values;
    $deleteConfirmModalId = $idPrefix . '-delete-confirm';

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
                :wire-submit="'saveUpdate'"
                :livewireModelRoot="'newsUpdateValues'"
            >
                <div class="flex items-center gap-3">
                    <x-buttons.primary type="submit">
                        {{ $submitLabel }}
                    </x-buttons.primary>

                    <x-buttons.danger
                        type="button"
                        onclick="window.dispatchEvent(new CustomEvent('news-delete-values-loaded', { detail: {modal:'{{ $deleteConfirmModalId }}', id: {{ (int) data_get($item, 'id') }} }}))"
                    >{{ $deleteLabel }}</x-buttons.danger>

                    @if($backUrl)
                        <x-buttons.secondary :href="$backUrl">{{ $backLabel }}</x-buttons.secondary>
                    @endif
                </div>
            </x-crud.forms.update>

            <x-crud.modals.confirm-delete
                :name="$deleteConfirmModalId"
                :wireConfirmAction="'deleteSelectedNews'"
            />
        </div>
    </div>
</section>
