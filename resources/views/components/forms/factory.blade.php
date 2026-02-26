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
        @endphp
        <div class="{{ $field->fullWidth ? 'sm:col-span-2' : '' }}">
            <x-forms.input-label for="{{ $field->id }}" :value="$field->label"
                                 class="mb-2 !text-sm !font-medium !text-gray-900 dark:!text-white"/>

            @if($field->type === 'textarea')
                @if($wireModel)
                    <x-forms.textarea
                        id="{{ $field->id }}"
                        name="{{ $field->htmlName ?? $field->name }}"
                        rows="{{ $field->rows }}"
                        placeholder="{{ $field->placeholder }}"
                        :required="$field->required"
                        class="{{ $controlClass }}"
                        :x-model="$field->alpineModel"
                        wire:model.defer="{{ $wireModel }}"
                    >{{ $field->value }}</x-forms.textarea>
                @else
                    <x-forms.textarea
                        id="{{ $field->id }}"
                        name="{{ $field->htmlName ?? $field->name }}"
                        rows="{{ $field->rows }}"
                        placeholder="{{ $field->placeholder }}"
                        :required="$field->required"
                        class="{{ $controlClass }}"
                        :x-model="$field->alpineModel"
                    >{{ $field->value }}</x-forms.textarea>
                @endif
            @elseif($field->type === 'select')
                @if($wireModel)
                    <x-forms.select
                        id="{{ $field->id }}"
                        name="{{ $field->htmlName ?? $field->name }}"
                        :required="$field->required"
                        class="{{ $controlClass }}"
                        :x-model="$field->alpineModel"
                        wire:model.defer="{{ $wireModel }}"
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
                    <x-forms.select
                        id="{{ $field->id }}"
                        name="{{ $field->htmlName ?? $field->name }}"
                        :required="$field->required"
                        class="{{ $controlClass }}"
                        :x-model="$field->alpineModel"
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
                @endif
            @else
                @if($wireModel)
                    <x-forms.text-input
                        :type="$field->type"
                        id="{{ $field->id }}"
                        name="{{ $field->htmlName ?? $field->name }}"
                        :value="$field->value"
                        placeholder="{{ $field->placeholder }}"
                        :required="$field->required"
                        class="{{ $controlClass }}"
                        :x-model="$field->alpineModel"
                        wire:model.defer="{{ $wireModel }}"
                    />
                @else
                    <x-forms.text-input
                        :type="$field->type"
                        id="{{ $field->id }}"
                        name="{{ $field->htmlName ?? $field->name }}"
                        :value="$field->value"
                        placeholder="{{ $field->placeholder }}"
                        :required="$field->required"
                        class="{{ $controlClass }}"
                        :x-model="$field->alpineModel"
                    />
                @endif
            @endif

            <x-forms.input-error :messages="$errorBag->get($errorKey)" class="mt-2"/>
        </div>
    @endforeach
</div>
