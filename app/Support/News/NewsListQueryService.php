<?php

namespace App\Support\News;

use App\Models\News;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsListQueryService
{
    /**
     * @param array<int, string> $rawSorts
     * @param array<int, string> $allowedSortColumns
     * @param array<int, string> $searchColumns
     */
    public function paginate(
        string $search,
        array $rawSorts,
        array $allowedSortColumns,
        array $searchColumns,
        int $perPage,
    ): LengthAwarePaginator {
        /** @var Builder $query */
        $query = News::query();
        $search = trim($search);

        if ($search !== '' && !empty($searchColumns)) {
            $query->where(function (Builder $nestedQuery) use ($search, $searchColumns) {
                foreach ($searchColumns as $index => $column) {
                    if (!is_string($column) || $column === '') {
                        continue;
                    }

                    if ($index === 0) {
                        $nestedQuery->where($column, 'like', '%' . $search . '%');
                    } else {
                        $nestedQuery->orWhere($column, 'like', '%' . $search . '%');
                    }
                }
            });
        }

        $sortOrders = $this->buildSortOrders($rawSorts, $allowedSortColumns);

        if (!empty($sortOrders)) {
            foreach ($sortOrders as $sortOrder) {
                $query->orderBy($sortOrder['column'], $sortOrder['direction']);
            }
        } else {
            $query->orderByDesc('published_at');
        }

        return $query->paginate($perPage);
    }

    /**
     * @param array<int, string> $rawSorts
     * @param array<int, string> $allowedSortColumns
     * @return array<int, array{column:string, direction:string}>
     */
    private function buildSortOrders(array $rawSorts, array $allowedSortColumns): array
    {
        $orders = [];
        $seenColumns = [];

        foreach ($rawSorts as $sortEntry) {
            if (!is_string($sortEntry)) {
                continue;
            }

            [$column, $direction] = array_pad(explode(':', $sortEntry, 2), 2, null);
            $column = trim((string) $column);
            $direction = strtolower(trim((string) $direction));

            if (
                $column === ''
                || !in_array($column, $allowedSortColumns, true)
                || !in_array($direction, ['asc', 'desc'], true)
                || isset($seenColumns[$column])
            ) {
                continue;
            }

            $orders[] = [
                'column' => $column,
                'direction' => $direction,
            ];
            $seenColumns[$column] = true;
        }

        return $orders;
    }
}