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
    >
    </x-crud.index>
</x-app-layout>
