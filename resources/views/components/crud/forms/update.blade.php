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
    <x-crud.forms.form :id-prefix="$idPrefix" :fields="$fields" :values="$values" />

    {{ $slot }}
</form>
