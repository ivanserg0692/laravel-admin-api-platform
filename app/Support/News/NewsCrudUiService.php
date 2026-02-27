<?php

namespace App\Support\News;

use App\UI\Forms\DTO\FormFieldDto;
use App\UI\Lists\DTO\ListColumnDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\News;

class NewsCrudUiService
{
    /**
     * @param array<int, mixed> $fields
     * @return array<int, array<string, mixed>>
     */
    public function normalizeFieldsForState(array $fields): array
    {
        $normalized = [];

        foreach ($fields as $field) {
            if ($field instanceof FormFieldDto) {
                $normalized[] = [
                    'name' => $field->name,
                    'label' => $field->label,
                    'type' => $field->type,
                    'placeholder' => $field->placeholder,
                    'required' => $field->required,
                    'rows' => $field->rows,
                    'fullWidth' => $field->fullWidth,
                    'options' => $field->options,
                    'id' => $field->id,
                    'value' => $field->value,
                    'alpineModel' => $field->alpineModel,
                ];
                continue;
            }

            if (is_array($field)) {
                $normalized[] = $field;
            }
        }

        return $normalized;
    }

    /**
     * @param array<int, array<string, mixed>> $fields
     * @return array<int, FormFieldDto>
     */
    public function hydrateFields(array $fields): array
    {
        $hydrated = [];

        foreach ($fields as $field) {
            $name = data_get($field, 'name');
            $label = data_get($field, 'label');

            if (!is_string($name) || $name === '' || !is_string($label) || $label === '') {
                continue;
            }

            $placeholder = data_get($field, 'placeholder');
            $id = data_get($field, 'id');
            $alpineModel = data_get($field, 'alpineModel');

            $hydrated[] = new FormFieldDto(
                name: $name,
                label: $label,
                type: (string) data_get($field, 'type', 'text'),
                placeholder: is_string($placeholder) ? $placeholder : null,
                required: (bool) data_get($field, 'required', false),
                rows: (int) data_get($field, 'rows', 4),
                fullWidth: (bool) data_get($field, 'fullWidth', false),
                options: (array) data_get($field, 'options', []),
                id: is_string($id) ? $id : null,
                value: data_get($field, 'value'),
                alpineModel: is_string($alpineModel) ? $alpineModel : null,
            );
        }

        return $hydrated;
    }

    /**
     * @param array<int, array<string, mixed>> $newsCreateFields
     * @param array<string, mixed> $configuredCreateValues
     * @return array<string, mixed>
     */
    public function createInitialValues(array $newsCreateFields, array $configuredCreateValues): array
    {
        $initial = [];

        foreach ($newsCreateFields as $field) {
            $name = data_get($field, 'name');
            if (!is_string($name) || $name === '') {
                continue;
            }

            $initial[$name] = '';
        }

        foreach ($configuredCreateValues as $key => $value) {
            if (is_string($key) && $key !== '') {
                $initial[$key] = $value;
            }
        }

        return $initial;
    }

    /**
     * @return array<int, ListColumnDto>
     */
    public function buildColumns(): array
    {
        return [
            new ListColumnDto(key: 'id', label: __('news.labels.id'), sortable: true),
            new ListColumnDto(key: 'title', label: __('news.labels.title'), sortable: true),
            new ListColumnDto(key: 'status', label: __('news.labels.status'), sortable: true),
            new ListColumnDto(key: 'published_at', label: __('news.labels.published'), sortable: true),
            new ListColumnDto(key: 'author_id', label: __('news.labels.author'), sortable: true),
            new ListColumnDto(key: 'views_count', label: __('news.labels.views'), sortable: true),
        ];
    }

    /**
     * @param array<int, string> $sorts
     */
    public function syncIndexQueryInSession(string $search, array $sorts, LengthAwarePaginator $items): void
    {
        $query = [];
        $trimmedSearch = trim($search);

        if ($trimmedSearch !== '') {
            $query['search'] = $trimmedSearch;
        }

        if (!empty($sorts)) {
            $query['sort'] = $sorts;
        }

        if ($items->currentPage() > 1) {
            $query['page'] = $items->currentPage();
        }

        session()->put('news.index.query', $query);
    }

    /**
     * @return array{modal: string, id: int, preview: array<string, mixed>}
     */
    public function buildPreviewPayload(News $news, string $modalName): array
    {
        $publishedAt = data_get($news, 'published_at');

        return [
            'modal' => $modalName,
            'id' => (int) $news->id,
            'preview' => [
                'title' => data_get($news, 'title'),
                'status' => data_get($news, 'status'),
                'published_at' => $publishedAt instanceof \DateTimeInterface
                    ? $publishedAt->format('Y-m-d H:i')
                    : null,
                'preview' => data_get($news, 'preview'),
                'content' => data_get($news, 'content'),
                'cover_image' => data_get($news, 'cover_image'),
            ],
        ];
    }

    /**
     * @return array{modal: string, id: int, title: string}
     */
    public function buildDeletePayload(News $news, string $modalName): array
    {
        return [
            'modal' => $modalName,
            'id' => (int) $news->id,
            'title' => (string) data_get($news, 'title', ''),
        ];
    }

    public function dispatchWindowEvent(Component $component, string $eventName, mixed $detail): void
    {
        $component->js(
            'window.dispatchEvent(new CustomEvent(' . json_encode($eventName) . ', { detail: ' . json_encode($detail) . ' }))'
        );
    }

    public function dispatchModalEvent(Component $component, string $eventName, string $modalName): void
    {
        $this->dispatchWindowEvent($component, $eventName, $modalName);
    }

    /**
     * @return array{create: string, update: string, preview: string, delete: string}
     */
    public function makeCrudModalNames(): array
    {
        $crudInstanceId = Str::uuid()->toString();

        return [
            'create' => 'create-product-' . $crudInstanceId,
            'update' => 'update-product-' . $crudInstanceId,
            'preview' => 'read-product-' . $crudInstanceId,
            'delete' => 'delete-product-' . $crudInstanceId,
        ];
    }

    public function makeDeleteModalName(): string
    {
        return 'delete-product-' . Str::uuid()->toString();
    }
}
