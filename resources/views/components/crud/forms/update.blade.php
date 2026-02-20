@props([
    'idPrefix' => 'update-product',
    'action' => '#',
    'values' => [],
])

@php
    $nameValue = $values['name'] ?? 'iPad Air Gen 5th Wi-Fi';
    $brandValue = $values['brand'] ?? 'Google';
    $priceValue = $values['price'] ?? 399;
    $categoryValue = $values['category'] ?? 'PC';
    $descriptionValue = $values['description'] ?? 'Standard glass, 3.8GHz 8-core 10th-generation Intel Core i7 processor, Turbo Boost up to 5.0GHz, 16GB 2666MHz DDR4 memory, Radeon Pro 5500 XT with 8GB of GDDR6 memory, 256GB SSD storage, Gigabit Ethernet, Magic Mouse 2, Magic Keyboard - US';
@endphp

<form action="{{ $action }}">
    <div class="grid gap-4 mb-4 sm:grid-cols-2">
        <div>
            <label for="{{ $idPrefix }}-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
            <input type="text" name="name" id="{{ $idPrefix }}-name" value="{{ $nameValue }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ex. Apple iMac 27&ldquo;">
        </div>
        <div>
            <label for="{{ $idPrefix }}-brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Brand</label>
            <input type="text" name="brand" id="{{ $idPrefix }}-brand" value="{{ $brandValue }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ex. Apple">
        </div>
        <div>
            <label for="{{ $idPrefix }}-price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
            <input type="number" value="{{ $priceValue }}" name="price" id="{{ $idPrefix }}-price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$299">
        </div>
        <div>
            <label for="{{ $idPrefix }}-category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
            <select id="{{ $idPrefix }}-category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                <option value="TV" @selected($categoryValue === 'TV')>TV/Monitors</option>
                <option value="PC" @selected($categoryValue === 'PC')>PC</option>
                <option value="GA" @selected($categoryValue === 'GA')>Gaming/Console</option>
                <option value="PH" @selected($categoryValue === 'PH')>Phones</option>
            </select>
        </div>
        <div class="sm:col-span-2">
            <label for="{{ $idPrefix }}-description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea id="{{ $idPrefix }}-description" name="description" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Write a description...">{{ $descriptionValue }}</textarea>
        </div>
    </div>

    {{ $slot }}
</form>
