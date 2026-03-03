<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Support\News\NewsCrudMutationService;
use App\Support\News\NewsCrudUiService;
use App\Support\News\NewsListQueryService;
use App\UI\Forms\NewsFormConfig;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected NewsCrudUiService $crudUiService;
    protected NewsCrudMutationService $crudMutationService;
    protected NewsListQueryService $newsListQueryService;
    protected NewsFormConfig $newsFormConfig;

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

    public string $createModalName = '';
    public string $updateModalName = '';
    public string $previewModalName = '';
    public string $deleteModalName = '';
    public ?int $editingNewsId = null;
    public ?int $deletingNewsId = null;
    public string $deletingNewsTitle = '';
    public bool $createValidationActive = false;
    public bool $updateValidationActive = false;

    public function boot(
        NewsCrudUiService $crudUiService,
        NewsCrudMutationService $crudMutationService,
        NewsListQueryService $newsListQueryService,
        NewsFormConfig $newsFormConfig,
    ): void {
        $this->crudUiService = $crudUiService;
        $this->crudMutationService = $crudMutationService->bind($this);
        $this->newsListQueryService = $newsListQueryService;
        $this->newsFormConfig = $newsFormConfig;
    }

    public function mount(
        array $newsCreateFields = [],
        array $newsCreateValues = [],
        array $newsUpdateFields = [],
        array $newsUpdateValues = [],
        array|string $sorts = [],
    ): void {
        $this->newsCreateFields = $this->crudUiService->normalizeFieldsForState($newsCreateFields);
        $this->newsCreateValues = $newsCreateValues;
        $this->newsUpdateFields = $this->crudUiService->normalizeFieldsForState($newsUpdateFields);
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

        $modalNames = $this->crudUiService->makeCrudModalNames();
        $this->createModalName = $modalNames['create'];
        $this->updateModalName = $modalNames['update'];
        $this->previewModalName = $modalNames['preview'];
        $this->deleteModalName = $modalNames['delete'];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updated(string $property): void
    {
        $this->crudMutationService->updated($property);
    }

    public function openCreateModal(): void
    {
        $this->crudMutationService->openCreateModal();
    }

    public function saveCreate(): void
    {
        $this->crudMutationService->saveCreate();
    }

    public function render()
    {
        $items = $this->newsListQueryService->paginate(
            search: $this->search,
            rawSorts: $this->sorts,
            allowedSortColumns: $this->allowedSortColumns,
            searchColumns: $this->searchColumns,
            perPage: $this->perPage,
        );

        $this->crudUiService->syncIndexQueryInSession($this->search, $this->sorts, $items);

        return view('livewire.news.index', [
            'items' => $items,
            'columns' => $this->crudUiService->buildColumns(),
            'hydratedNewsCreateFields' => $this->crudUiService->hydrateFields($this->newsCreateFields),
            'hydratedNewsUpdateFields' => $this->crudUiService->hydrateFields($this->newsUpdateFields),
            'createValidationActive' => $this->createValidationActive,
            'updateValidationActive' => $this->updateValidationActive,
            'canCreateNews' => auth()->user()?->can('create', News::class) ?? false,
        ]);
    }

    public function openUpdateModal(int $newsId): void
    {
        $this->crudMutationService->openUpdateModal($newsId);
    }

    public function saveUpdate(): void
    {
        $this->crudMutationService->saveUpdate();
        $this->crudUiService->dispatchModalEvent($this, 'close-modal', $this->updateModalName);

    }

    public function openPreviewModal(int $newsId): void
    {
        $this->crudMutationService->openPreviewModal($newsId);
    }

    public function openDeleteModal(int $newsId): void
    {
        $this->crudMutationService->openDeleteModal($newsId);
    }

    public function deleteSelectedNews(): void
    {
        $this->crudMutationService->deleteSelectedNews();
    }
}
