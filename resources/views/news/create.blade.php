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
@endphp

<x-app-layout>
    <x-crud.create
        title="Create News"
        :form-action="route('news.store')"
        id-prefix="page-create-news"
        :fields="$newsFields"
        submit-label="Create news"
        :cancel-url="route('news.index')"
    />
</x-app-layout>
