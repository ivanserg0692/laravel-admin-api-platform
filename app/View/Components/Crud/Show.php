<?php

namespace App\View\Components\Crud;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class Show extends Component
{
    public string $titleValue;
    public string $contentValue;
    public ?string $primaryMetaValue;
    public ?string $secondaryMetaValue;
    public array $metaItems;

    public function __construct(
        public mixed   $item,
        ?string        $title = null,
        ?string        $content = null,
        ?string        $primaryMeta = null,
        ?string        $secondaryMeta = null,
        ?array         $meta = null,
        public ?string $editUrl = null,
        public ?string $previewUrl = null,
        public ?string $deleteUrl = null,
    )
    {
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
