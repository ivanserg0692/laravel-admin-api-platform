<?php

namespace App\View\Components\Crud;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Show extends Component
{
    public string $titleValue;
    public string $contentValue;
    public ?string $primaryMetaValue;
    public ?string $secondaryMetaValue;
    public array $metaItems;
    public ?string $deleteConfirmModalId;
    public ?string $deleteFormId;
    public string $submitLabel;
    public string $deleteLabel;
    public string $backLabel;

    public function __construct(
        public mixed   $item,
        ?string        $title = null,
        ?string        $content = null,
        ?string        $primaryMeta = null,
        ?string        $secondaryMeta = null,
        ?array         $meta = null,
        public ?string $editUrl = null,
        public ?string $previewUrl = null,
        public ?string $backUrl = null,
        public string  $idPrefix = 'page-show-item',
        ?string        $submitLabel = null,
        ?string        $deleteLabel = null,
        ?string        $backLabel = null,
    )
    {
        $this->deleteConfirmModalId = $idPrefix . '-delete-confirm' . Str::uuid()->toString();
        $this->deleteFormId = $idPrefix . '-delete-form';
        $this->submitLabel = $submitLabel ?? __('crud.save_label');
        $this->deleteLabel = $deleteLabel ?? __('crud.delete_label');
        $this->backLabel = $backLabel ?? __('crud.back_label');
        $this->titleValue = $title
            ?? data_get($item, 'title')
            ?? data_get($item, 'name')
            ?? 'Untitled item';

        $this->contentValue = $content
            ?? data_get($item, 'content')
            ?? data_get($item, 'description')
            ?? data_get($item, 'body')
            ?? data_get($item, 'preview')
            ?? 'No details available.';

        $this->primaryMetaValue = $primaryMeta ?? $this->resolvePrimaryMetaValue($item);
        $this->secondaryMetaValue = $secondaryMeta
            ?? (data_get($item, 'status') ? ucfirst((string)data_get($item, 'status')) : null);

        $this->metaItems = $meta ?? $this->defaultMetaItems($item);
    }

    public function render(): View
    {
        return view('components.crud.show');
    }

    private function resolvePrimaryMetaValue(mixed $item): ?string
    {
        $rawDate = data_get($item, 'published_at') ?? data_get($item, 'created_at');
        if (!$rawDate) {
            return null;
        }

        try {
            return Carbon::parse($rawDate)->format('jS \o\f F, Y');
        } catch (\Throwable) {
            return (string)$rawDate;
        }
    }

    private function defaultMetaItems(mixed $item): array
    {
        return [
            ['label' => 'Status', 'value' => ucfirst((string)(data_get($item, 'status') ?? 'N/A'))],
            ['label' => 'Author', 'value' => data_get($item, 'author_id') ? ('#' . data_get($item, 'author_id')) : 'Unknown'],
            ['label' => 'Views', 'value' => number_format((int)(data_get($item, 'views_count') ?? 0))],
        ];
    }
}
