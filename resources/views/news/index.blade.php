<x-app-layout>
    <livewire:news.index
        :news-create-fields="$newsCreateFields"
        :news-create-values="$newsCreateValues"
        :news-update-fields="$newsUpdateFields"
        :news-update-values="$newsUpdateValues"
        :sorts="request()->query('sort', [])"
    />
</x-app-layout>
