@props([
    'idPrefix' => 'create-item',
    'action' => '#',
    'fields' => [],
    'values' => [],
])

<form method="POST" action="{{ $action }}">
    @csrf
    <x-crud.forms.form :id-prefix="$idPrefix" :fields="$fields" :values="$values" />

    {{ $slot }}
</form>
