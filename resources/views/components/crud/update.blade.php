@props([
    'item' => null,
])

@php
    $nameValue = data_get($item, 'title') ?? data_get($item, 'name') ?? 'iPad Air Gen 5th Wi-Fi';
    $brandValue = data_get($item, 'brand') ?? 'Google';
    $priceValue = data_get($item, 'price') ?? 399;
    $categoryValue = data_get($item, 'category') ?? 'PC';
    $descriptionValue = data_get($item, 'content') ?? data_get($item, 'description') ?? 'Standard glass, 3.8GHz 8-core 10th-generation Intel Core i7 processor, Turbo Boost up to 5.0GHz, 16GB 2666MHz DDR4 memory, Radeon Pro 5500 XT with 8GB of GDDR6 memory, 256GB SSD storage, Gigabit Ethernet, Magic Mouse 2, Magic Keyboard - US';
@endphp

<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
            <div class="mb-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Update Product</h1>
            </div>

            <x-crud.forms.update
                id-prefix="page-update-product"
                :values="[
                    'name' => $nameValue,
                    'brand' => $brandValue,
                    'price' => $priceValue,
                    'category' => $categoryValue,
                    'description' => $descriptionValue,
                ]"
            >
                <div class="flex items-center gap-3">
                    <x-buttons.primary type="submit" class="px-5 py-2.5">
                        Update product
                    </x-buttons.primary>
                    <x-buttons.danger type="button" class="px-5 py-2.5">
                        Delete
                    </x-buttons.danger>
                    <x-buttons.secondary :href="route('news.index')">
                        Back
                    </x-buttons.secondary>
                </div>
            </x-crud.forms.update>
        </div>
    </div>
</section>
