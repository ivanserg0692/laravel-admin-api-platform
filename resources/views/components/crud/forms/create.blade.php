@props([
    'idPrefix' => 'create-item',
    'action' => '#',
    'fields' => [],
    'values' => [],
    'errorBag' => null,
    'livewireModelRoot' => null,
    'wireSubmit' => null,
    'livewireValidationActive' => false,
])

{{-- Intentionally kept as a CRUD wrapper: this is an extension point for future create-specific behavior. --}}
<form
    method="POST"
    action="{{ $action }}"
    @if(filled($wireSubmit))
        wire:submit.prevent="{{ $wireSubmit }}"
    @endif
>
    @csrf
    <x-forms.factory
        :id-prefix="$idPrefix"
        :fields="$fields"
        :values="$values"
        :error-bag="$errorBag"
        :livewire-model-root="$livewireModelRoot"
        :livewire-validation-active="$livewireValidationActive"
    />

    {{ $slot }}
</form>
