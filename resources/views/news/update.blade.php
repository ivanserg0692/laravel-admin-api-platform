@php
    $newsFields = [
        ['name' => 'title', 'label' => 'Title', 'placeholder' => 'Enter news title', 'required' => true],
        ['name' => 'slug', 'label' => 'Slug', 'placeholder' => 'news-title-slug', 'required' => true],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'], 'required' => true],
        ['name' => 'published_at', 'label' => 'Published at', 'type' => 'datetime-local'],
        ['name' => 'preview', 'label' => 'Preview', 'type' => 'textarea', 'rows' => 3, 'full_width' => true],
        ['name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'rows' => 7, 'required' => true, 'full_width' => true],
        ['name' => 'cover_image', 'label' => 'Cover image URL', 'type' => 'url', 'placeholder' => 'https://example.com/cover.jpg', 'full_width' => true],
        ['name' => 'meta_title', 'label' => 'Meta title', 'placeholder' => 'SEO title'],
        ['name' => 'meta_description', 'label' => 'Meta description', 'placeholder' => 'SEO description'],
        ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'value' => 0],
    ];

    $newsValues = [
        'title' => data_get($news, 'title'),
        'slug' => data_get($news, 'slug'),
        'status' => data_get($news, 'status'),
        'published_at' => data_get($news, 'published_at') ? \Illuminate\Support\Carbon::parse(data_get($news, 'published_at'))->format('Y-m-d\TH:i') : null,
        'preview' => data_get($news, 'preview'),
        'content' => data_get($news, 'content'),
        'cover_image' => data_get($news, 'cover_image'),
        'meta_title' => data_get($news, 'meta_title'),
        'meta_description' => data_get($news, 'meta_description'),
        'sort_order' => data_get($news, 'sort_order'),
    ];
@endphp

<x-app-layout>
    <x-crud.update
        :item="$news ?? null"
        title="Update News"
        :form-action="route('news.update', $news)"
        form-method="PUT"
        id-prefix="page-update-news"
        :fields="$newsFields"
        :values="$newsValues"
        submit-label="Save changes"
        :delete-url="route('news.destroy', $news)"
        :back-url="route('news.index')"
    />
</x-app-layout>
