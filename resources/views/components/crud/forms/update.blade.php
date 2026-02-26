@props([
    'idPrefix' => 'update-item',
    'action' => '#',
    'httpMethod' => 'PUT',
    'fields' => [],
    'values' => [],
    'errorBag' => null,
    'alpineModelRoot' => null,
    'livewireModelRoot' => null,
    'wireSubmit' => null,
    'livewireValidationActive' => false,
])

{{-- Intentionally kept as a CRUD wrapper: this is an extension point for future update-specific behavior. --}}
<form
    method="POST"
    action="{{ $action }}"
    @if(filled($wireSubmit))
        wire:submit.prevent="{{ $wireSubmit }}"
    @endif
>
    @csrf
    @method($httpMethod)
    <x-forms.factory
        :id-prefix="$idPrefix"
        :fields="$fields"
        :values="$values"
        :error-bag="$errorBag"
        :alpine-model-root="$alpineModelRoot"
        :livewire-model-root="$livewireModelRoot"
        :livewire-validation-active="$livewireValidationActive"
    />

    {{ $slot }}
</form>
