@props([
    'items' => collect(),
    'columns' => [],
    'showActions' => true,
    'emptyText' => 'No data found.',
])

<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            @foreach($columns as $column)
                <th scope="col" class="{{ $column['header_class'] ?? 'px-4 py-3' }}">
                    {{ $column['label'] }}
                </th>
            @endforeach
            @if($showActions)
                <th scope="col" class="px-4 py-3">
                    <span class="sr-only">Actions</span>
                </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr class="border-b dark:border-gray-700">
                @foreach($columns as $index => $column)
                    @php
                        $value = data_get($item, $column['key']);

                        if ($value instanceof \DateTimeInterface) {
                            $value = $value->format('Y-m-d H:i');
                        }

                        $value = $value ?? '-';
                        $isPrimary = $index === 1 || ($index === 0 && count($columns) === 1);
                    @endphp

                    @if($isPrimary)
                        <th scope="row"
                            class="{{ $column['cell_class'] ?? 'px-4 py-3' }} font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $value }}
                        </th>
                    @else
                        <td class="{{ $column['cell_class'] ?? 'px-4 py-3' }}">{{ $value }}</td>
                    @endif
                @endforeach

                @if($showActions)
                    <td class="px-4 py-3 flex items-center justify-end">
                        @php
                            $rowId = data_get($item, 'id') ?? uniqid('row_', true);
                        @endphp
                        <x-dropdown.row-actions
                            :button-id="'row-' . $rowId . '-dropdown-button'"
                            :dropdown-id="'row-' . $rowId . '-dropdown'"
                            >
                            {{ $actions ?? '' }}
                        </x-dropdown.row-actions>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + ($showActions ? 1 : 0) }}"
                    class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                    {{ $emptyText }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
