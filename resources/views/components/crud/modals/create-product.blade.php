@php
    $fields = [
        ['name' => 'name', 'label' => 'Name', 'placeholder' => 'Type product name', 'required' => true],
        ['name' => 'brand', 'label' => 'Brand', 'placeholder' => 'Product brand', 'required' => true],
        ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'placeholder' => '$2999', 'required' => true],
        [
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select',
            'options' => [
                '' => 'Select category',
                'TV' => 'TV/Monitors',
                'PC' => 'PC',
                'GA' => 'Gaming/Console',
                'PH' => 'Phones',
            ],
            'required' => true,
        ],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'placeholder' => 'Write product description here', 'full_width' => true],
    ];
@endphp

<x-modals.panel name="create-product" maxWidth="2xl" title="Add Product">
    <x-crud.forms.create id-prefix="create-product" :fields="$fields">
        <x-buttons.primary type="submit">
            <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Add new product
        </x-buttons.primary>
    </x-crud.forms.create>
</x-modals.panel>
