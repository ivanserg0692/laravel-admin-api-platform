@php
    use App\UI\Lists\DTO\ListColumnDto;

    $newsColumns = [
        new ListColumnDto(key: 'id', label: __('news.labels.id'), sortable: true),
        new ListColumnDto(key: 'title', label: __('news.labels.title'), sortable: true),
        new ListColumnDto(key: 'status', label: __('news.labels.status'), sortable: true),
        new ListColumnDto(key: 'published_at', label: __('news.labels.published'), sortable: true),
        new ListColumnDto(key: 'author_id', label: __('news.labels.author'), sortable: true),
        new ListColumnDto(key: 'views_count', label: __('news.labels.views'), sortable: true),
    ];
@endphp

<x-app-layout>
    <x-crud.index
        :items="$news"
        detail-route-name="news.show"
        row-actions-component="news.rows.actions"
        :columns="$newsColumns"
        :sorts="request()->query('sort', [])"
        :search-placeholder="__('news.placeholders.search')"
        :create-fields="$newsCreateFields"
        :create-values="$newsCreateValues"
        :create-modal-title="__('news.create_modal_title')"
        :create-submit-label="__('news.create_submit_label')"
        :update-fields="$newsUpdateFields"
        :update-values="$newsUpdateValues"
        :create-button-label="__('news.create_button_label')"
        :update-modal-title="__('news.update_modal_title')"
        :preview-modal-title="__('news.preview_modal_title')"
        :delete-modal-title="__('news.delete_confirm_title')"
        :delete-modal-message="__('news.delete_confirm_template')"
    >
    </x-crud.index>
</x-app-layout>
