@php
    $newsCreateFields = [
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

    $newsUpdateFields = $newsCreateFields;

    $newsCreateValues = [
        'status' => 'draft',
        'sort_order' => 0,
    ];

    $newsUpdateValues = [
        'title' => 'The 4th Digital Transformation',
        'slug' => 'the-4th-digital-transformation',
        'status' => 'published',
        'published_at' => '2026-01-02T12:00',
        'preview' => 'USA enterprises and governments have committed to a technology-driven future.',
        'content' => 'USA enterprises and governments have committed to a technology-driven future, making USA one of the fastest-growing markets for digital technologies.',
        'cover_image' => 'https://images.example.com/news/cover.jpg',
        'meta_title' => 'Digital Transformation',
        'meta_description' => 'News about digital transformation and cybersecurity trends.',
        'sort_order' => 10,
    ];
@endphp

<x-app-layout>
    <x-crud.index
        :items="$news"
        :columns="[
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'published_at', 'label' => 'Published'],
            ['key' => 'author_id', 'label' => 'Author'],
            ['key' => 'views_count', 'label' => 'Views'],
        ]"
        :create-fields="$newsCreateFields"
        :create-values="$newsCreateValues"
        :update-fields="$newsUpdateFields"
        :update-values="$newsUpdateValues"
    >
    </x-crud.index>
</x-app-layout>
