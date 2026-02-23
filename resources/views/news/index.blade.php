@php
    use App\UI\Lists\DTO\ListColumnDto;

    $newsColumns = [
        new ListColumnDto(key: 'id', label: 'ID', sortable: true),
        new ListColumnDto(key: 'title', label: 'Title', sortable: true),
        new ListColumnDto(key: 'status', label: 'Status', sortable: true),
        new ListColumnDto(key: 'published_at', label: 'Published', sortable: true),
        new ListColumnDto(key: 'author_id', label: 'Author', sortable: true),
        new ListColumnDto(key: 'views_count', label: 'Views', sortable: true),
    ];
@endphp

<x-app-layout>
    <x-crud.index
        :items="$news"
        detail-route-name="news.show"
        row-actions-component="news.rows.actions"
        :columns="$newsColumns"
        :sorts="request()->query('sort', [])"
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
