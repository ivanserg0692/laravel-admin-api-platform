@props([
    'idPrefix' => 'create-item',
    'action' => '#',
    'fields' => [],
    'values' => [],
])

{{-- Intentionally kept as a CRUD wrapper: this is an extension point for future create-specific behavior. --}}
<form method="POST" action="{{ $action }}">
    @csrf
    <x-forms.factory :id-prefix="$idPrefix" :fields="$fields" :values="$values" />

    {{ $slot }}
</form>
