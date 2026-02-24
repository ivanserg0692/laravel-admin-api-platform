@props([
    'fields' => [],
    'values' => [],
    'action' => '#',
    'title' => 'Add Product',
    'submitLabel' => 'Add new product',
])

<x-modals.panel name="create-product" maxWidth="2xl" :title="$title">
    <x-crud.forms.create id-prefix="create-product" :fields="$fields" :values="$values" :action="$action">
        <x-buttons.primary type="submit">
            <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            {{ $submitLabel }}
        </x-buttons.primary>
    </x-crud.forms.create>
</x-modals.panel>
