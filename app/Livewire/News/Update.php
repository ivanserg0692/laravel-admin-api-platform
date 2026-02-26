<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Support\News\NewsCrudMutationService;
use App\Support\News\NewsCrudUiService;
use App\UI\Forms\NewsFormConfig;
use Livewire\Component;

class Update extends Component
{
    protected NewsCrudUiService $crudUiService;
    protected NewsCrudMutationService $crudMutationService;
    protected NewsFormConfig $newsFormConfig;

    public int $newsId;
    public array $newsUpdateValues = [];
    public bool $updateValidationActive = false;
    public ?int $deletingNewsId = null;
    public string $deletingNewsTitle = '';
    public string $backUrl = '';
    public string $pageTitle = '';
    public string $submitLabel = '';
    public string $deleteLabel = '';
    public string $backLabel = '';
    public string $deleteModalName = '';
    public string $deleteModalTitle = '';
    public string $deleteModalMessage = '';

    public function boot(
        NewsCrudUiService $crudUiService,
        NewsCrudMutationService $crudMutationService,
        NewsFormConfig $newsFormConfig,
    ): void {
        $this->crudUiService = $crudUiService;
        $this->crudMutationService = $crudMutationService->bind($this);
        $this->newsFormConfig = $newsFormConfig;
    }

    public function mount(News $news, ?string $backUrl = null): void
    {
        $this->newsId = (int) $news->id;
        $this->newsUpdateValues = $this->newsFormConfig->updateValues($news);
        $this->backUrl = $backUrl ?: route('news.index', session('news.index.query', []));

        $this->pageTitle = __('news.update_page_title');
        $this->submitLabel = __('news.update_submit_label');
        $this->deleteLabel = __('crud.delete_label');
        $this->backLabel = __('crud.back_label');
        $this->deleteModalTitle = __('news.delete_confirm_title');
        $this->deleteModalMessage = __('news.delete_confirm_template');
        $this->deleteModalName = $this->crudUiService->makeDeleteModalName();
    }

    public function updated(string $property): void
    {
        $this->crudMutationService->updated($property);
    }

    public function saveUpdate(): void
    {
        $this->crudMutationService->saveUpdate();
    }

    public function openDeleteModal(int $newsId): void
    {
        $this->crudMutationService->openDeleteModal($newsId);
    }

    public function deleteSelectedNews(): mixed
    {
        return $this->crudMutationService->deleteSelectedNews();
    }

    public function render()
    {
        return view('livewire.news.update', [
            'newsFields' => $this->newsFormConfig->fields(),
            'itemForCrudUpdate' => [
                'id' => $this->newsId,
                'title' => (string) data_get($this->newsUpdateValues, 'title', ''),
            ],
        ]);
    }
}
