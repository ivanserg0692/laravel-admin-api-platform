@props([
    'title' => 'Create Item',
    'formAction' => '#',
    'idPrefix' => 'page-create-item',
    'fields' => [],
    'values' => [],
    'errorBag' => null,
    'submitLabel' => 'Create',
    'cancelUrl' => null,
    'cancelLabel' => 'Cancel',
])

<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
            <div class="mb-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
            </div>

            <x-crud.forms.create
                :id-prefix="$idPrefix"
                :action="$formAction"
                :fields="$fields"
                :values="$values"
                :error-bag="$errorBag"
            >
                <div class="flex items-center gap-3">
                    <x-buttons.primary type="submit">
                        {{ $submitLabel }}
                    </x-buttons.primary>

                    @if($cancelUrl)
                        <x-buttons.secondary :href="$cancelUrl">
                            {{ $cancelLabel }}
                        </x-buttons.secondary>
                    @else
                        <x-buttons.secondary type="button">
                            {{ $cancelLabel }}
                        </x-buttons.secondary>
                    @endif
                </div>
            </x-crud.forms.create>
        </div>
    </div>
</section>
