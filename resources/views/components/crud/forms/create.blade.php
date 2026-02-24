@props([
    'idPrefix' => 'create-item',
    'action' => '#',
    'fields' => [],
    'values' => [],
    'errorBag' => null,
    'nameMode' => 'plain',
    'inputNamespace' => null,
])

{{-- Intentionally kept as a CRUD wrapper: this is an extension point for future create-specific behavior. --}}
<form method="POST" action="{{ $action }}">
    @csrf
    <x-forms.factory
        :id-prefix="$idPrefix"
        :fields="$fields"
        :values="$values"
        :error-bag="$errorBag"
        :name-mode="$nameMode"
        :input-namespace="$inputNamespace"
    />

    {{ $slot }}
</form>
