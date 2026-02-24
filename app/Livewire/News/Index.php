<?php

namespace App\Livewire\News;

use App\Support\News\NewsListQueryService;
use App\UI\Forms\DTO\FormFieldDto;
use App\UI\Lists\DTO\ListColumnDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public array $newsCreateFields = [];
    public array $newsCreateValues = [];
    public array $newsUpdateFields = [];
    public array $newsUpdateValues = [];

    public string $createButtonLabel = '';
    public string $createModalTitle = '';
    public string $createSubmitLabel = '';
    public string $updateModalTitle = '';
    public string $previewModalTitle = '';
    public string $deleteModalTitle = '';
    public string $deleteModalMessage = '';
    public string $searchPlaceholder = '';

    public string $detailRouteName = 'news.show';
    public string $rowActionsComponent = 'news.rows.actions';

    public int $perPage = 10;
    public array $allowedSortColumns = ['id', 'title', 'status', 'published_at', 'author_id', 'views_count'];
    public array $searchColumns = ['title', 'slug', 'preview', 'content'];

    /** @var array<int, string> */
    public array $sorts = [];

    #[Url(except: '')]
    public string $search = '';

    public string $updateModalName = '';
    public string $previewModalName = '';
    public string $deleteModalName = '';

    public function mount(
        array $newsCreateFields = [],
        array $newsCreateValues = [],
        array $newsUpdateFields = [],
        array $newsUpdateValues = [],
        array|string $sorts = [],
    ): void {
        $this->newsCreateFields = $this->normalizeFieldsForState($newsCreateFields);
        $this->newsCreateValues = $newsCreateValues;
        $this->newsUpdateFields = $this->normalizeFieldsForState($newsUpdateFields);
        $this->newsUpdateValues = $newsUpdateValues;
        $this->sorts = is_array($sorts) ? $sorts : [$sorts];

        $this->createButtonLabel = __('news.create_button_label');
        $this->createModalTitle = __('news.create_modal_title');
        $this->createSubmitLabel = __('news.create_submit_label');
        $this->updateModalTitle = __('news.update_modal_title');
        $this->previewModalTitle = __('news.preview_modal_title');
        $this->deleteModalTitle = __('news.delete_confirm_title');
        $this->deleteModalMessage = __('news.delete_confirm_template');
        $this->searchPlaceholder = __('news.placeholders.search');

        $crudInstanceId = Str::uuid()->toString();
        $this->updateModalName = 'update-product-' . $crudInstanceId;
        $this->previewModalName = 'read-product-' . $crudInstanceId;
        $this->deleteModalName = 'delete-product-' . $crudInstanceId;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        /** @var NewsListQueryService $service */
        $service = app(NewsListQueryService::class);

        $items = $service->paginate(
            search: $this->search,
            rawSorts: $this->sorts,
            allowedSortColumns: $this->allowedSortColumns,
            searchColumns: $this->searchColumns,
            perPage: $this->perPage,
        );

        $this->syncIndexQueryInSession($items);

        return view('livewire.news.index', [
            'items' => $items,
            'columns' => $this->buildColumns(),
            'hydratedNewsCreateFields' => $this->hydrateFields($this->newsCreateFields),
            'hydratedNewsUpdateFields' => $this->hydrateFields($this->newsUpdateFields),
        ]);
    }

    /**
     * @return array<int, ListColumnDto>
     */
    private function buildColumns(): array
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

    private function syncIndexQueryInSession(LengthAwarePaginator $items): void
    {
        $query = [];

        $search = trim($this->search);
        if ($search !== '') {
            $query['search'] = $search;
        }

        if (!empty($this->sorts)) {
            $query['sort'] = $this->sorts;
        }

        if ($items->currentPage() > 1) {
            $query['page'] = $items->currentPage();
        }

        session()->put('news.index.query', $query);
    }

    /**
     * @param array<int, mixed> $fields
     * @return array<int, array<string, mixed>>
     */
    private function normalizeFieldsForState(array $fields): array
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
                    'htmlName' => $field->htmlName,
                    'oldKey' => $field->oldKey,
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
    private function hydrateFields(array $fields): array
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
            $htmlName = data_get($field, 'htmlName');
            $oldKey = data_get($field, 'oldKey');
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
                htmlName: is_string($htmlName) ? $htmlName : null,
                oldKey: is_string($oldKey) ? $oldKey : null,
                alpineModel: is_string($alpineModel) ? $alpineModel : null,
            );
        }

        return $hydrated;
    }
}
