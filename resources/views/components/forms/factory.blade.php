@php use App\View\Components\Forms\Factory; @endphp

<div class="{{ $gridClass }}">
    @php
        $errorBag = $resolvedErrorBag === Factory::DEFAULT_ERROR_BAG
            ? $errors
            : $errors->getBag($resolvedErrorBag);
    @endphp

    @foreach($normalizedFields as $field)
        @php
            $wireModel = filled($livewireModelRoot) ? $livewireModelRoot . '.' . $field->name : null;
            $errorKey = $wireModel ?: ($field->oldKey ?? $field->name);
            $hasError = $errorBag->has($errorKey);
            $errorClass = $hasError
                ? '!border-red-500 focus:!border-red-500 focus:!ring-red-500 dark:!border-red-500 dark:focus:!border-red-500 dark:focus:!ring-red-500'
                : '';
            $modelBinding = null;
            if ($wireModel) {
                $modelBinding = new \Illuminate\View\ComponentAttributeBag(
                    $livewireValidationActive
                        ? ['wire:model.live.debounce.300ms' => $wireModel]
                        : ['wire:model.defer' => $wireModel]
                );
            }
        @endphp
        <div class="{{ $field->fullWidth ? 'sm:col-span-2' : '' }}">
            <x-forms.input-label for="{{ $field->id }}" :value="$field->label"
                                 class="mb-2 !text-sm !font-medium !text-gray-900 dark:!text-white"/>

            @if($field->type === 'textarea')
                <x-forms.textarea
                    id="{{ $field->id }}"
                    name="{{ $field->htmlName ?? $field->name }}"
                    rows="{{ $field->rows }}"
                    placeholder="{{ $field->placeholder }}"
                    class="{{ $controlClass }} {{ $errorClass }}"
                    :x-model="$field->alpineModel"
                    :attributes="$modelBinding"
                >{{ $field->value }}</x-forms.textarea>
            @elseif($field->type === 'select')
                <x-forms.select
                    id="{{ $field->id }}"
                    name="{{ $field->htmlName ?? $field->name }}"
                    class="{{ $controlClass }} {{ $errorClass }}"
                    :x-model="$field->alpineModel"
                    :attributes="$modelBinding"
                >
                    @foreach($field->options as $optionValue => $optionLabel)
                        @php
                            $normalizedValue = is_array($optionLabel) ? data_get($optionLabel, 'value') : $optionValue;
                            $normalizedLabel = is_array($optionLabel) ? data_get($optionLabel, 'label', $normalizedValue) : $optionLabel;
                        @endphp
                        <option
                            value="{{ $normalizedValue }}" @selected((string)$field->value === (string)$normalizedValue)>{{ $normalizedLabel }}</option>
                    @endforeach
                </x-forms.select>
            @else
                <x-forms.text-input
                    :type="$field->type"
                    id="{{ $field->id }}"
                    name="{{ $field->htmlName ?? $field->name }}"
                    :value="$field->value"
                    placeholder="{{ $field->placeholder }}"
                    class="{{ $controlClass }} {{ $errorClass }}"
                    :x-model="$field->alpineModel"
                    :attributes="$modelBinding"
                />
            @endif

            <x-forms.input-error :messages="$errorBag->get($errorKey)" class="mt-2"/>
        </div>
    @endforeach
</div>
