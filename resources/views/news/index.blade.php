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
        :update-modal-title="__('news.update_modal_title')"
        :preview-modal-title="__('news.preview_modal_title')"
        :delete-modal-title="__('news.delete_confirm_title')"
        :delete-modal-message="__('news.delete_confirm_template')"
    >
    </x-crud.index>
</x-app-layout>
