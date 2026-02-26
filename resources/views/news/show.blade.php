<x-app-layout>
    <x-crud.show
        :item="$news"
        {{-- :preview-url="route('news.show', $news)"--}}
        :edit-url="route('news.edit', $news)"
        :back-url="$backUrl"
    >
    </x-crud.show>
</x-app-layout>
