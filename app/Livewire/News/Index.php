<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Support\News\NewsListQueryService;
use App\UI\Forms\NewsFormConfig;
use App\UI\Forms\DTO\FormFieldDto;
use App\UI\Lists\DTO\ListColumnDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
    public ?int $editingNewsId = null;
    public ?int $deletingNewsId = null;
    public string $deletingNewsTitle = '';

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

    public function openCreateModal(): void
    {
        $this->newsCreateValues = $this->createInitialValues();
        $this->resetValidation();

        $this->dispatchModalEvent('open-modal', 'create-product');
    }

    public function saveCreate(): void
    {
        $validated = $this->validate(
            rules: $this->newsRules('newsCreateValues'),
            attributes: $this->newsValidationAttributes('newsCreateValues'),
        );

        $values = (array) data_get($validated, 'newsCreateValues', []);

        $news = new News();
        $news->title = (string) data_get($values, 'title', '');
        $news->slug = (string) data_get($values, 'slug', '');
        $news->status = (string) data_get($values, 'status', 'draft');
        $news->published_at = $this->normalizePublishedAt(data_get($values, 'published_at'));
        $news->preview = data_get($values, 'preview') ?: null;
        $news->content = (string) data_get($values, 'content', '');
        $news->cover_image = data_get($values, 'cover_image') ?: null;
        $news->meta_title = data_get($values, 'meta_title') ?: null;
        $news->meta_description = data_get($values, 'meta_description') ?: null;
        $news->sort_order = (int) data_get($values, 'sort_order', 0);
        $news->author_id = auth()->id();
        $news->save();

        $this->newsCreateValues = $this->createInitialValues();
        $this->resetValidation();

        $this->dispatchModalEvent('close-modal', 'create-product');
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

    public function openUpdateModal(int $newsId): void
    {
        $news = News::query()->findOrFail($newsId);

        $this->editingNewsId = (int)$news->id;
        $this->newsUpdateValues = app(NewsFormConfig::class)->updateValues($news);
        $this->resetValidation();

        $this->dispatchModalEvent('open-modal', $this->updateModalName);
    }

    public function saveUpdate(): void
    {
        if (!$this->editingNewsId) {
            return;
        }


        $news = News::query()->findOrFail($this->editingNewsId);

        $validated = $this->validate(
            rules: $this->newsRules('newsUpdateValues', $news->id),
            attributes: $this->newsValidationAttributes('newsUpdateValues'),
        );

        $values = (array)data_get($validated, 'newsUpdateValues', []);

        $news->title = (string)data_get($values, 'title', '');
        $news->slug = (string)data_get($values, 'slug', '');
        $news->status = (string)data_get($values, 'status', 'draft');
        $news->published_at = $this->normalizePublishedAt(data_get($values, 'published_at'));
        $news->preview = data_get($values, 'preview') ?: null;
        $news->content = (string)data_get($values, 'content', '');
        $news->cover_image = data_get($values, 'cover_image') ?: null;
        $news->meta_title = data_get($values, 'meta_title') ?: null;
        $news->meta_description = data_get($values, 'meta_description') ?: null;
        $news->sort_order = (int)data_get($values, 'sort_order', 0);
        $news->save();

        $this->newsUpdateValues = app(NewsFormConfig::class)->updateValues($news);
        $this->resetValidation();

        $this->dispatchModalEvent('close-modal', $this->updateModalName);
    }

    public function openPreviewModal(int $newsId): void
    {
        $news = News::query()->findOrFail($newsId);
        $publishedAt = data_get($news, 'published_at');

        $this->dispatchWindowEvent('news-preview-loaded', [
            'modal' => $this->previewModalName,
            'id' => $news->id,
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
        ]);

        $this->dispatchModalEvent('open-modal', $this->previewModalName);
    }

    public function openDeleteModal(int $newsId): void
    {
        $news = News::query()->findOrFail($newsId);

        $this->deletingNewsId = (int) $news->id;
        $this->deletingNewsTitle = (string) data_get($news, 'title', '');

        $this->dispatchWindowEvent('news-delete-values-loaded', [
            'modal' => $this->deleteModalName,
            'id' => $this->deletingNewsId,
            'title' => $this->deletingNewsTitle,
        ]);

        $this->dispatchModalEvent('open-modal', $this->deleteModalName);
    }

    public function deleteSelectedNews(): void
    {
        if (!$this->deletingNewsId) {
            return;
        }

        $news = News::query()->findOrFail($this->deletingNewsId);
        $news->delete();

        if ($this->editingNewsId === $this->deletingNewsId) {
            $this->editingNewsId = null;
            $this->dispatchModalEvent('close-modal', $this->updateModalName);
        }

        $this->deletingNewsId = null;
        $this->deletingNewsTitle = '';

        $this->dispatchModalEvent('close-modal', $this->deleteModalName);
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

    private function dispatchModalEvent(string $eventName, string $modalName): void
    {
        $this->dispatchWindowEvent($eventName, $modalName);
    }

    private function dispatchWindowEvent(string $eventName, mixed $detail): void
    {
        $this->js(
            'window.dispatchEvent(new CustomEvent(' . json_encode($eventName) . ', { detail: ' . json_encode($detail) . ' }))'
        );
    }

    private function normalizePublishedAt(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function newsRules(string $valueRoot, ?int $ignoreNewsId = null): array
    {
        $slugRule = Rule::unique('news', 'slug');
        if ($ignoreNewsId !== null) {
            $slugRule = $slugRule->ignore($ignoreNewsId);
        }

        return [
            $valueRoot . '.title' => ['required', 'string', 'max:255'],
            $valueRoot . '.slug' => ['required', 'string', 'max:255', $slugRule],
            $valueRoot . '.status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            $valueRoot . '.published_at' => ['nullable', 'date'],
            $valueRoot . '.preview' => ['nullable', 'string'],
            $valueRoot . '.content' => ['required', 'string'],
            $valueRoot . '.cover_image' => ['nullable', 'url', 'max:2048'],
            $valueRoot . '.meta_title' => ['nullable', 'string', 'max:255'],
            $valueRoot . '.meta_description' => ['nullable', 'string', 'max:255'],
            $valueRoot . '.sort_order' => ['nullable', 'integer'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function newsValidationAttributes(string $valueRoot): array
    {
        return [
            $valueRoot . '.title' => __('news.labels.title'),
            $valueRoot . '.slug' => __('news.labels.slug'),
            $valueRoot . '.status' => __('news.labels.status'),
            $valueRoot . '.published_at' => __('news.labels.published_at'),
            $valueRoot . '.preview' => __('news.labels.preview'),
            $valueRoot . '.content' => __('news.labels.content'),
            $valueRoot . '.cover_image' => __('news.labels.cover_image'),
            $valueRoot . '.meta_title' => __('news.labels.meta_title'),
            $valueRoot . '.meta_description' => __('news.labels.meta_description'),
            $valueRoot . '.sort_order' => __('news.labels.sort_order'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function createInitialValues(): array
    {
        $initial = [];

        foreach ($this->newsCreateFields as $field) {
            $name = data_get($field, 'name');
            if (!is_string($name) || $name === '') {
                continue;
            }

            $initial[$name] = '';
        }

        foreach (app(NewsFormConfig::class)->createValues() as $key => $value) {
            if (is_string($key) && $key !== '') {
                $initial[$key] = $value;
            }
        }

        return $initial;
    }
}
