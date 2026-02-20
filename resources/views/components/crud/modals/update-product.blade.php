<x-modals.panel name="update-product" maxWidth="2xl" title="Update Product">
    <x-crud.forms.update
        id-prefix="update-product"
        :values="[
            'name' => 'iPad Air Gen 5th Wi-Fi',
            'brand' => 'Google',
            'price' => 399,
            'category' => 'PC',
            'description' => 'Standard glass, 3.8GHz 8-core 10th-generation Intel Core i7 processor, Turbo Boost up to 5.0GHz, 16GB 2666MHz DDR4 memory, Radeon Pro 5500 XT with 8GB of GDDR6 memory, 256GB SSD storage, Gigabit Ethernet, Magic Mouse 2, Magic Keyboard - US',
        ]"
    >
        <div class="flex items-center space-x-4">
            <x-buttons.primary type="submit" class="px-5 py-2.5">
                Update product
            </x-buttons.primary>
            <x-buttons.danger type="button" class="px-5 py-2.5">
                <svg class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Delete
            </x-buttons.danger>
        </div>
    </x-crud.forms.update>
</x-modals.panel>
