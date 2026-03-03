<x-app-layout>
    <x-flash.result />
    <x-crud.show
        :item="$news"
        {{-- :preview-url="route('news.show', $news)"--}}
        :edit-url="$canUpdateNews ? route('news.edit', $news) : null"
        :can-delete="$canDeleteNews"
        :back-url="$backUrl"
    >
    </x-crud.show>
</x-app-layout>
