<?php

namespace App\Support\News;

use App\Models\News;
use App\UI\Forms\NewsFormConfig;
use Livewire\Component;
use RuntimeException;

class NewsCrudMutationService
{
    private ?Component $component = null;

    public function __construct(
        private readonly NewsValidationService $validationService,
        private readonly NewsCrudUiService     $crudUiService,
        private readonly NewsFormConfig        $newsFormConfig,
    )
    {
    }

    public function bind(Component $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function updated(string $property): void
    {
        $component = $this->component();

        if (!str_starts_with($property, 'newsUpdateValues.') && !str_starts_with($property, 'newsCreateValues.')) {
            return;
        }

        if (str_starts_with($property, 'newsCreateValues.')) {
            //processing a creation
            if (!$this->isIndexComponent() || !$component->createValidationActive) {
                return;
            }

            $component->resetValidation($property);
            $component->validateOnly(
                $property,
                $this->validationService->rules('newsCreateValues'),
                attributes: $this->validationService->attributes('newsCreateValues'),
            );

            return;
        }

        //processing an update
        if (!$component->updateValidationActive) {
            return;
        }

        $component->resetValidation($property);
        $component->validateOnly(
            $property,
            $this->validationService->rules('newsUpdateValues', $this->currentNewsIdForUpdate()),
            attributes: $this->validationService->attributes('newsUpdateValues'),
        );
    }

    public function openCreateModal(): void
    {
        if (!$this->isIndexComponent()) {
            return;
        }

        $component = $this->component();
        $component->newsCreateValues = $this->crudUiService->createInitialValues(
            $component->newsCreateFields,
            $this->newsFormConfig->createValues(),
        );
        $component->createValidationActive = false;
        $component->resetValidation();
        $this->crudUiService->dispatchModalEvent($component, 'open-modal', $component->createModalName);
    }

    //entity methods
    public function saveCreate(): void
    {
        if (!$this->isIndexComponent()) {
            return;
        }

        $component = $this->component();
        $component->createValidationActive = true;
        $validated = $component->validate(
            rules: $this->validationService->rules('newsCreateValues'),
            attributes: $this->validationService->attributes('newsCreateValues'),
        );

        $values = (array)data_get($validated, 'newsCreateValues', []);
        $this->createNewsFromValues($values, auth()->id());

        $component->newsCreateValues = $this->crudUiService->createInitialValues(
            $component->newsCreateFields,
            $this->newsFormConfig->createValues(),
        );
        $component->createValidationActive = false;
        $component->resetValidation();
        $this->crudUiService->dispatchModalEvent($component, 'close-modal', $component->createModalName);
    }

    public function saveUpdate(): void
    {
        $component = $this->component();
        $newsId = $this->currentNewsIdForUpdate();
        if (!$newsId) {
            return;
        }

        $component->updateValidationActive = true;
        $validated = $component->validate(
            rules: $this->validationService->rules('newsUpdateValues', $newsId),
            attributes: $this->validationService->attributes('newsUpdateValues'),
        );

        $news = $this->findOrFail($newsId);
        $values = (array)data_get($validated, 'newsUpdateValues', []);
        $this->updateNewsFromValues($news, $values);

        $component->newsUpdateValues = $this->newsFormConfig->updateValues($news);
        $component->updateValidationActive = false;
        $component->resetValidation();

        if ($this->isIndexComponent()) {
            $this->crudUiService->dispatchModalEvent($component, 'close-modal', $component->updateModalName);
        }
    }

    public function deleteSelectedNews(): mixed
    {
        $component = $this->component();
        if (!$component->deletingNewsId) {
            return null;
        }

        $deletedId = (int)$component->deletingNewsId;
        $this->deleteNewsById($deletedId);

        $component->deletingNewsId = null;
        $component->deletingNewsTitle = '';

        if ($this->isIndexComponent()) {
            if ($component->editingNewsId === $deletedId) {
                $component->editingNewsId = null;
                $this->crudUiService->dispatchModalEvent($component, 'close-modal', $component->updateModalName);
            }

            $this->crudUiService->dispatchModalEvent($component, 'close-modal', $component->deleteModalName);

            return null;
        }

        return redirect()->route('news.index', session('news.index.query', []));
    }

    //modals-methods

    public function openUpdateModal(int $newsId): void
    {
        if (!$this->isIndexComponent()) {
            return;
        }

        $component = $this->component();
        $news = $this->findOrFail($newsId);
        $component->editingNewsId = (int)$news->id;
        $component->newsUpdateValues = $this->newsFormConfig->updateValues($news);
        $component->updateValidationActive = false;
        $component->resetValidation();
        $this->crudUiService->dispatchModalEvent($component, 'open-modal', $component->updateModalName);
    }

    public function openPreviewModal(int $newsId): void
    {
        if (!$this->isIndexComponent()) {
            return;
        }

        $component = $this->component();
        $news = $this->findOrFail($newsId);
        $payload = $this->crudUiService->buildPreviewPayload($news, $component->previewModalName);

        $this->crudUiService->dispatchWindowEvent($component, 'news-preview-loaded', $payload);
        $this->crudUiService->dispatchModalEvent($component, 'open-modal', $component->previewModalName);
    }

    public function openDeleteModal(int $newsId): void
    {
        $component = $this->component();
        $news = $this->findOrFail($newsId);
        $payload = $this->crudUiService->buildDeletePayload($news, $component->deleteModalName);

        $component->deletingNewsId = (int)$payload['id'];
        $component->deletingNewsTitle = (string)$payload['title'];

        $this->crudUiService->dispatchWindowEvent($component, 'news-delete-values-loaded', $payload);
        if ($this->isIndexComponent()) {
            $this->crudUiService->dispatchModalEvent($component, 'open-modal', $component->deleteModalName);
        }
    }

    protected function findOrFail(int $id): News
    {
        return News::query()->findOrFail($id);
    }

    /**
     * @param array<string, mixed> $values
     */
    protected function createNewsFromValues(array $values, ?int $authorId): News
    {
        $news = new News();
        $news->title = (string)data_get($values, 'title', '');
        $news->slug = (string)data_get($values, 'slug', '');
        $news->status = (string)data_get($values, 'status', 'draft');
        $news->published_at = $this->validationService->normalizePublishedAt(data_get($values, 'published_at'));
        $news->preview = data_get($values, 'preview') ?: null;
        $news->content = (string)data_get($values, 'content', '');
        $news->cover_image = data_get($values, 'cover_image') ?: null;
        $news->meta_title = data_get($values, 'meta_title') ?: null;
        $news->meta_description = data_get($values, 'meta_description') ?: null;
        $news->sort_order = (int)data_get($values, 'sort_order', 0);
        $news->author_id = $authorId;
        $news->save();

        return $news;
    }

    /**
     * @param array<string, mixed> $values
     */
    protected function updateNewsFromValues(News $news, array $values): News
    {
        $news->title = (string)data_get($values, 'title', '');
        $news->slug = (string)data_get($values, 'slug', '');
        $news->status = (string)data_get($values, 'status', 'draft');
        $news->published_at = $this->validationService->normalizePublishedAt(data_get($values, 'published_at'));
        $news->preview = data_get($values, 'preview') ?: null;
        $news->content = (string)data_get($values, 'content', '');
        $news->cover_image = data_get($values, 'cover_image') ?: null;
        $news->meta_title = data_get($values, 'meta_title') ?: null;
        $news->meta_description = data_get($values, 'meta_description') ?: null;
        $news->sort_order = (int)data_get($values, 'sort_order', 0);
        $news->save();

        return $news;
    }

    protected function deleteNewsById(int $id): void
    {
        $news = $this->findOrFail($id);
        $news->delete();
    }

    private function component(): Component
    {
        if (!$this->component) {
            throw new RuntimeException('NewsCrudMutationService is not bound to a component.');
        }

        return $this->component;
    }

    protected function isIndexComponent(): bool
    {
        $component = $this->component();

        return property_exists($component, 'editingNewsId') && property_exists($component, 'createModalName');
    }

    protected function currentNewsIdForUpdate(): int
    {
        $component = $this->component();

        if ($this->isIndexComponent()) {
            return (int)($component->editingNewsId ?? 0);
        }

        return (int)($component->newsId ?? 0);
    }
}
