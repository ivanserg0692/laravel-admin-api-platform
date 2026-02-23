<?php

namespace App\View\Components\Lists;

use App\UI\Lists\DTO\SortOrderDto;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableSortLink extends Component
{
    public string $sortUrl;
    public ?string $direction;
    public string $activeClass;

    /**
     * @param array<int, SortOrderDto> $activeSorts Active multi-sort entries ordered by priority.
     */
    public function __construct(
        public string  $sortKey, // Unique sort key for the current sortable column.
        public string  $label, // Visible header label text.
        public array   $activeSorts = [],
        public ?string $currentDirection = null, // Current direction for this column: asc|desc|null.
        public ?int    $priority = null, // 1-based priority badge for this column in multi-sort.
    )
    {
        $this->direction = $this->resolveDirection($this->currentDirection);
        $this->sortUrl = $this->buildSortUrl();
        $this->activeClass = $this->direction !== null
            ? 'text-gray-900 dark:text-white'
            : 'text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white';
    }

    public function render(): View
    {
        return view('components.lists.table-sort-link');
    }

    private function buildSortUrl(): string
    {
        $query = request()->query();
        $querySorts = array_map(
            static fn(SortOrderDto $sortItem): string => $sortItem->toQueryValue(),
            $this->buildSortsForNextDirection(),
        );

        if (empty($querySorts)) {
            unset($query['sort']);
        } else {
            $query['sort'] = $querySorts;
        }

        unset($query['direction']);
        $query['page'] = 1;

        $url = request()->url();
        $queryString = http_build_query($query);

        return $queryString !== '' ? $url . '?' . $queryString : $url;
    }

    /**
     * @return array<int, SortOrderDto>
     */
    private function buildSortsForNextDirection(): array
    {
        $updatedSorts = [];
        $isColumnFound = false;
        $nextDirection = $this->nextDirection();

        foreach ($this->activeSorts as $sortItem) {
            if (!$sortItem instanceof SortOrderDto) {
                continue;
            }

            if ($sortItem->key !== $this->sortKey) {
                $updatedSorts[] = $sortItem;
                continue;
            }

            $isColumnFound = true;

            if ($nextDirection !== null) {
                $updatedSorts[] = new SortOrderDto(
                    key: $this->sortKey,
                    direction: $nextDirection,
                );
            }
        }

        if (!$isColumnFound && $nextDirection !== null) {
            $updatedSorts[] = new SortOrderDto(
                key: $this->sortKey,
                direction: $nextDirection,
            );
        }

        return $updatedSorts;
    }

    private function resolveDirection(?string $direction): ?string
    {
        return in_array($direction, ['asc', 'desc'], true)
            ? $direction
            : null;
    }

    private function nextDirection(): ?string
    {
        return match ($this->direction) {
            null => 'asc',
            'asc' => 'desc',
            default => null,
        };
    }
}
