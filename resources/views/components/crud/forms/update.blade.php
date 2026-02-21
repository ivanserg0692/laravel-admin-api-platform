@props([
    'idPrefix' => 'update-item',
    'action' => '#',
    'httpMethod' => 'PUT',
    'fields' => [],
    'values' => [],
])

{{-- Intentionally kept as a CRUD wrapper: this is an extension point for future update-specific behavior. --}}
<form method="POST" action="{{ $action }}">
    @csrf
    @method($httpMethod)
    <x-forms.factory :id-prefix="$idPrefix" :fields="$fields" :values="$values" />

    {{ $slot }}
</form>
