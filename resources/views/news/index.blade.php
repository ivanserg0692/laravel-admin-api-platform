<x-app-layout>
    <x-crud.index
        :items="$news"
        detail-route-name="news.show"
        row-actions-component="news.rows.actions"
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
