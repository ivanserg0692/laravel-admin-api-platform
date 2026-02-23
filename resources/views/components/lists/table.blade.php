@props([
    'items' => collect(),
    'columns' => [],
    'sorts' => [],
    'emptyText' => 'No data found.',
    'detailRouteName' => null,
    'rowActionsComponent' => null,
    'editModal' => null,
    'previewModal' => null,
    'deleteModal' => null,
])

@php
    use App\UI\Lists\DTO\SortOrderDto;

    $rawSorts = $sorts;
    if (is_string($rawSorts)) {
        $rawSorts = [$rawSorts];
    }

    if (!is_array($rawSorts)) {
        $rawSorts = [];
    }

    $activeSorts = collect($rawSorts)
        ->map(function ($item) {
            if (!is_string($item)) {
                return null;
            }

            [$key, $direction] = array_pad(explode(':', $item, 2), 2, null);
            $key = trim((string) $key);
            $direction = strtolower(trim((string) $direction));

            if ($key === '' || !in_array($direction, ['asc', 'desc'], true)) {
                return null;
            }

            return new SortOrderDto(
                key: $key,
                direction: $direction,
            );
        })
        ->filter()
        ->values()
        ->all();

    $activeSortByKey = [];
    $sortPriorityMap = [];

    foreach ($activeSorts as $index => $activeSort) {
        $activeSortByKey[$activeSort->key] = $activeSort;
        $sortPriorityMap[$activeSort->key] = $index + 1;
    }
@endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            @foreach($columns as $column)
                @php
                    $sortKey = $column->sortKey ?? $column->key;
                    $currentDirection = $activeSortByKey[$sortKey]->direction ?? null;
                    $priority = $sortPriorityMap[$sortKey] ?? null;
                @endphp
                <th scope="col" class="{{ $column->headerClass ?? 'px-4 py-3' }}">
                    @if($column->sortable)
                        <x-lists.table-sort-link
                            :sort-key="$sortKey"
                            :label="$column->label"
                            :active-sorts="$activeSorts"
                            :current-direction="$currentDirection"
                            :priority="$priority"
                        />
                    @else
                        {{ $column->label }}
                    @endif
                </th>
            @endforeach
            @if($rowActionsComponent)
                <th scope="col" class="px-4 py-3">
                    <span class="sr-only">Actions</span>
                </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            @php
                $rowUrl = $detailRouteName ? route($detailRouteName, $item) : null;
                $rowClass = $rowUrl
                    ? 'border-b dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50'
                    : 'border-b dark:border-gray-700';
            @endphp
            <tr
                class="{{ $rowClass }}"
                @if($rowUrl)
                    onclick='window.location.href = @json($rowUrl)'
                @endif
            >
                @foreach($columns as $index => $column)
                    @php
                        $value = data_get($item, $column->key);

                        if ($value instanceof \DateTimeInterface) {
                            $value = $value->format('Y-m-d H:i');
                        }

                        $value = $value ?? '-';
                        $isPrimary = $index === 1 || ($index === 0 && count($columns) === 1);
                    @endphp

                    @if($isPrimary)
                        <th scope="row"
                            class="{{ $column->cellClass ?? 'px-4 py-3' }} font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $value }}
                        </th>
                    @else
                        <td class="{{ $column->cellClass ?? 'px-4 py-3' }}">{{ $value }}</td>
                    @endif
                @endforeach

                @if($rowActionsComponent)
                    <td class="px-4 py-3 flex items-center justify-end" onclick="event.stopPropagation()">
                        @php
                            $rowId = data_get($item, 'id') ?? \Illuminate\Support\Str::uuid()->toString();
                        @endphp
                        <x-dropdown.row-actions
                            :button-id="'row-' . $rowId . '-dropdown-button'"
                            :dropdown-id="'row-' . $rowId . '-dropdown'"
                        >
                            <x-dynamic-component
                                :component="$rowActionsComponent"
                                :item="$item"
                                :edit-modal="$editModal"
                                :preview-modal="$previewModal"
                                :delete-modal="$deleteModal"
                            />
                        </x-dropdown.row-actions>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + ($rowActionsComponent ? 1 : 0) }}"
                    class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                    {{ $emptyText }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
