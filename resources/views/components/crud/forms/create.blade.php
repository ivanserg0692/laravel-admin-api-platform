@props([
    'idPrefix' => 'create-item',
    'action' => '#',
    'fields' => [],
    'values' => [],
])

<form method="POST" action="{{ $action }}">
    @csrf
    <x-forms.factory :id-prefix="$idPrefix" :fields="$fields" :values="$values" />

    {{ $slot }}
</form>
