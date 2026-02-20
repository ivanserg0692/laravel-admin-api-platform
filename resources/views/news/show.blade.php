<x-app-layout>
    <x-crud.show
        :item="$news"
        :preview-url="route('news.show', $news)"
    >
    </x-crud.show>
</x-app-layout>
