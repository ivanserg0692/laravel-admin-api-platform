<x-app-layout>
    <x-crud.show
        :item="$news"
        {{-- :preview-url="route('news.show', $news)"--}}
        :edit-url="route('news.edit', $news)"
        :delete-url="route('news.destroy', $news)"
    >
    </x-crud.show>
</x-app-layout>
