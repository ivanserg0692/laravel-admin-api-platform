@props([
    'items' => collect(),
    'columns' => [],
    'sorts' => [],
    'createFields' => [],
    'createValues' => [],
    'updateFields' => [],
    'updateValues' => [],
    'detailRouteName' => null,
    'rowActionsComponent' => null,
    'updateModalTitle' => __('crud.edit_label'),
    'previewModalTitle' => __('crud.preview_label'),
    'deleteModalTitle' => __('crud.delete_confirm_title'),
    'deleteModalMessage' => __('crud.delete_confirm_template'),
    'createButtonLabel' => 'Add product',
    'createModalTitle' => 'Add Product',
    'createSubmitLabel' => 'Add new product',
    'createModal' => null,
    'createOpenAction' => null,
    'createSubmitAction' => null,
    'createLivewireModelRoot' => null,
    'tableLoadingTargets' => null,
    'searchPlaceholder' => 'Search',
    'livewireModel' => null,
    'updateModal' => null,
    'previewModal' => null,
    'deleteModal' => null,
    'updateCurrentItemId' => null,
    'updateCurrentItemTitle' => '',
])
@php
    $resolvedTableLoadingTargets = is_string($tableLoadingTargets) && trim($tableLoadingTargets) !== ''
        ? trim($tableLoadingTargets)
        : 'openCreateModal,openUpdateModal,openPreviewModal,openDeleteModal';
@endphp
<!-- Start block -->
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            {{--
            TODO(crud-toolbar): temporarily disabled while toolbar behavior is being implemented.
            Return this block after CRUD toolbar task is completed. --}}
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <x-forms.search :placeholder="$searchPlaceholder" :livewire-model="$livewireModel"/>
                </div>

                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <x-buttons.primary type="button" id="createProductModalButton"
                                       wire:click="{{ $createOpenAction }}"
                                       wire:loading.attr="disabled"
                                       wire:target="{{ $createOpenAction }}"
                                       class="!px-4 !font-medium">
                        <span wire:loading.remove wire:target="{{ $createOpenAction }}"
                              class="inline-flex items-center">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                            </svg>
                            {{ $createButtonLabel }}
                        </span>
                        <span wire:loading.inline-flex wire:target="{{ $createOpenAction }}" class="items-center">
                            <x-ui.spinner size-class="h-4 w-4" class="-ml-1 mr-2 text-white"/>
                            {{ $createButtonLabel }}
                        </span>
                    </x-buttons.primary>

                    {{--<div class="flex items-center space-x-3 w-full md:w-auto">
                        <x-crud.toolbar.actions-dropdown/>
                        <x-crud.toolbar.filter-dropdown/>
                    </div>--}}
                </div>
            </div>

            <div class="relative">
                <div
                    wire:loading.flex
                    wire:target="{{ $resolvedTableLoadingTargets }}"
                    class="absolute inset-0 z-40 items-center justify-center bg-white/70 dark:bg-gray-900/70 backdrop-blur-[1px]"
                >
                    <div
                        class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow dark:bg-gray-800 dark:text-gray-200">
                        <x-ui.spinner size-class="h-4 w-4" class="mr-2 text-gray-600 dark:text-gray-200"/>
                        Processing...
                    </div>
                </div>

                <div
                    wire:loading.class="pointer-events-none select-none opacity-70"
                    wire:target="{{ $resolvedTableLoadingTargets }}"
                >
                    <x-lists.table
                        :items="$items"
                        :columns="$columns"
                        :sorts="$sorts"
                        :detail-route-name="$detailRouteName"
                        :row-actions-component="$rowActionsComponent"
                        :edit-modal="$updateModal"
                        :preview-modal="$previewModal"
                        :delete-modal="$deleteModal"
                    />

                    <nav
                        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Showing  {{ $items->firstItem() ?? 0 }}-{{ $items->lastItem() ?? 0 }} of {{ $items->total() }}
                        </span>
                        {{ $items->onEachSide(1)->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End block -->
@include('components.crud.modals.create-product', [
    'name' => $createModal,
    'fields' => $createFields,
    'values' => $createValues,
    'title' => $createModalTitle,
    'submitLabel' => $createSubmitLabel,
    'wireSubmit' => $createSubmitAction,
    'livewireModelRoot' => $createLivewireModelRoot,
])
@include('components.crud.modals.update-product', [
    'fields' => $updateFields,
    'values' => $updateValues,
    'name' => $updateModal,
    'title' => $updateModalTitle,
    'deleteModal' => $deleteModal,
    'wireSubmit' => 'saveUpdate',
    'livewireModelRoot' => 'newsUpdateValues',
    'currentItemId' => $updateCurrentItemId,
    'currentItemTitle' => $updateCurrentItemTitle,
])
@include('components.crud.modals.read-product', [
    'name' => $previewModal,
    'title' => $previewModalTitle,
])
@include('components.crud.modals.confirm-delete', [
    'name' => $deleteModal,
    'title' => $deleteModalTitle,
    'message' => $deleteModalMessage,
    'wireConfirmAction' => 'deleteSelectedNews',
])
