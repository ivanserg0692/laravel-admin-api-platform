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
