<x-app-layout>
    <x-crud.update
        :item="$news ?? null"
        title="Update News"
        :form-action="route('news.update', $news)"
        form-method="PUT"
        id-prefix="page-update-news"
        :fields="$newsFields"
        :values="$newsValues"
        error-bag="updateNews"
        submit-label="Save changes"
        :delete-url="route('news.destroy', $news)"
        :back-url="route('news.show', $news)"
    />
</x-app-layout>
