@props([
    'idPrefix' => 'update-item',
    'action' => '#',
    'httpMethod' => 'PUT',
    'fields' => [],
    'values' => [],
])

<form method="POST" action="{{ $action }}">
    @csrf
    @method($httpMethod)
    <x-forms.factory :id-prefix="$idPrefix" :fields="$fields" :values="$values" />

    {{ $slot }}
</form>
