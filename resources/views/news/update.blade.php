<x-app-layout>
    <x-crud.update
        :item="$news ?? null"
        :title="__('news.update_page_title')"
        :form-action="route('news.update', $news)"
        form-method="PUT"
        id-prefix="page-update-news"
        :fields="$newsFields"
        :values="$newsValues"
        error-bag="updateNews"
        :submit-label="__('news.update_submit_label')"
        :back-url="route('news.show', $news)"
    />
</x-app-layout>
