@props([
    'idPrefix' => 'update-item',
    'action' => '#',
    'httpMethod' => 'PUT',
    'fields' => [],
    'values' => [],
    'errorBag' => null,
    'nameMode' => 'plain',
    'inputNamespace' => null,
    'alpineModelRoot' => null,
    'livewireModelRoot' => null,
    'wireSubmit' => null,
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
        :name-mode="$nameMode"
        :input-namespace="$inputNamespace"
        :alpine-model-root="$alpineModelRoot"
        :livewire-model-root="$livewireModelRoot"
    />

    {{ $slot }}
</form>
